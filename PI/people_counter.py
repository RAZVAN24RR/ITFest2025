from picamera2 import Picamera2
import cv2
import numpy as np
import tflite_runtime.interpreter as tflite

# ---------------- Configuration ----------------
MODEL_PATH = "models/efficientdet_lite0.tflite"  # Path to the TFLite model
MIN_CONFIDENCE = 0.5                              # Confidence threshold for detections
INPUT_SIZE = (320, 320)                           # Model input size
MATCH_THRESHOLD = 80                              # Increased matching threshold (in pixels)

# ---------------- Initialize TFLite Interpreter ----------------
interpreter = tflite.Interpreter(model_path=MODEL_PATH)
interpreter.allocate_tensors()
input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# ---------------- Define Labels ----------------
# Manually define the label list to ensure "person" is at index 0.
labels = ["person"]

# ---------------- Initialize Picamera2 ----------------
picam2 = Picamera2()
config = picam2.create_video_configuration(
    main={"size": (640, 480), "format": "RGB888"}
)
picam2.configure(config)
picam2.start()

# ---------------- Tracking and Counting Variables ----------------
# Each tracked object is stored as:
# "centroid": (x,y), "bbox": (left, top, right, bottom),
# "last_y": previous y position, "state": None/"top_crossed"/"bottom_crossed",
# "counted": whether the crossing event was registered, "lost": frames missed.
tracked_objects = {}
next_object_id = 0
counter = 0

# ---------------- Utility Functions ----------------
def draw_lines(frame):
    """Draw two horizontal lines and return their y positions."""
    h, w, _ = frame.shape
    top_line = int(h * 0.3)
    bottom_line = int(h * 0.7)
    cv2.line(frame, (0, top_line), (w, top_line), (255, 0, 0), 2)    # Blue top line
    cv2.line(frame, (0, bottom_line), (w, bottom_line), (0, 0, 255), 2)  # Red bottom line
    return top_line, bottom_line

def update_tracking(detections, tracked_objects):
    """
    Update tracked objects using simple nearest neighbor matching.
    Each detection is a dict with keys "centroid" and "bbox".
    """
    global next_object_id
    new_tracked = {}
    used_detections = set()
    # Update existing objects
    for obj_id, obj in tracked_objects.items():
        best_match = None
        best_distance = float('inf')
        for i, det in enumerate(detections):
            if i in used_detections:
                continue
            dist = np.linalg.norm(np.array(obj["centroid"]) - np.array(det["centroid"]))
            if dist < best_distance:
                best_distance = dist
                best_match = i
        if best_match is not None and best_distance < MATCH_THRESHOLD:
            det = detections[best_match]
            new_tracked[obj_id] = {
                "centroid": det["centroid"],
                "bbox": det["bbox"],
                "last_y": obj["centroid"][1],  # Use previous frame's centroid for comparison
                "state": obj["state"],
                "counted": obj["counted"],
                "lost": 0
            }
            used_detections.add(best_match)
        else:
            obj["lost"] += 1
            if obj["lost"] < 5:
                new_tracked[obj_id] = obj
    # Add new detections as new tracked objects
    for i, det in enumerate(detections):
        if i not in used_detections:
            new_tracked[next_object_id] = {
                "centroid": det["centroid"],
                "bbox": det["bbox"],
                "last_y": det["centroid"][1],
                "state": None,
                "counted": False,
                "lost": 0
            }
            next_object_id += 1
    return new_tracked

def check_crossings(tracked_objects, top_line, bottom_line):
    """
    Check each tracked object's vertical movement relative to the lines.
    If an object that first crosses the top line later crosses the bottom line,
    increment the counter. If it first crosses the bottom line then crosses the top line,
    decrement the counter.
    """
    global counter
    for obj in tracked_objects.values():
        current_y = obj["centroid"][1]
        last_y = obj["last_y"]
        if obj["state"] is None:
            # Check if moving downward and crosses the top line
            if last_y < top_line and current_y >= top_line:
                obj["state"] = "top_crossed"
            # Check if moving upward and crosses the bottom line
            elif last_y > bottom_line and current_y <= bottom_line:
                obj["state"] = "bottom_crossed"
        else:
            if not obj["counted"]:
                if obj["state"] == "top_crossed" and current_y >= bottom_line:
                    counter += 1
                    obj["counted"] = True
                elif obj["state"] == "bottom_crossed" and current_y <= top_line:
                    counter -= 1
                    obj["counted"] = True

def draw_tracked_objects(frame, tracked_objects):
    """Optionally draw tracking IDs on the frame."""
    for obj_id, obj in tracked_objects.items():
        left, top_coord, right, bottom = obj["bbox"]
        cv2.putText(frame, f"ID {obj_id}", (left, top_coord - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

# ---------------- Main Loop ----------------
try:
    while True:
        # Capture frame from camera
        frame = picam2.capture_array()
        h, w, _ = frame.shape

        # Draw horizontal lines on the frame
        top_line, bottom_line = draw_lines(frame)

        # Prepare input for the model
        img_resized = cv2.resize(frame, INPUT_SIZE)
        input_data = np.expand_dims(img_resized.astype(np.uint8), axis=0)

        # Run inference
        interpreter.set_tensor(input_details[0]['index'], input_data)
        interpreter.invoke()

        # Retrieve detection outputs
        boxes = interpreter.get_tensor(output_details[0]['index'])[0]
        classes = interpreter.get_tensor(output_details[1]['index'])[0]
        scores = interpreter.get_tensor(output_details[2]['index'])[0]

        detections = []
        # Process detections and draw bounding boxes
        for i in range(len(scores)):
            if scores[i] > MIN_CONFIDENCE:
                if int(classes[i]) < len(labels) and labels[int(classes[i])] == 'person':
                    ymin, xmin, ymax, xmax = boxes[i]
                    left = int(xmin * w)
                    top_coord = int(ymin * h)
                    right = int(xmax * w)
                    bottom = int(ymax * h)
                    cx = int((left + right) / 2)
                    cy = int((top_coord + bottom) / 2)
                    detections.append({
                        "bbox": (left, top_coord, right, bottom),
                        "centroid": (cx, cy)
                    })
                    cv2.rectangle(frame, (left, top_coord), (right, bottom), (0, 255, 0), 2)
                    label_text = f"person: {scores[i]*100:.1f}%"
                    cv2.putText(frame, label_text, (left, top_coord - 10),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

        # Update tracking with current detections
        tracked_objects = update_tracking(detections, tracked_objects)
        # Check if any tracked object has crossed the lines in the defined order
        check_crossings(tracked_objects, top_line, bottom_line)
        # After checking crossings, update last_y for the next frame
        for obj in tracked_objects.values():
            obj["last_y"] = obj["centroid"][1]

        # Optionally, draw tracking IDs
        draw_tracked_objects(frame, tracked_objects)

        # Display the current counter on the frame
        cv2.putText(frame, f"Count: {counter}", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 0, 255), 2)

        cv2.imshow("Human Detection and Counting", frame)
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

finally:
    picam2.stop()
    cv2.destroyAllWindows()
