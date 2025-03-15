import threading
import queue
import time
import os

from picamera2 import Picamera2
import cv2
import numpy as np
import tflite_runtime.interpreter as tflite

# ---------------- Configuration ----------------
MODEL_PATH = "models/efficientdet_lite0.tflite"  # Path to the TFLite model
MIN_CONFIDENCE = 0.5                              # Confidence threshold for detections
INPUT_SIZE = (320, 320)                           # Model input size for inference
MATCH_THRESHOLD = 80                              # Matching threshold (in pixels)
DISPLAY_RESOLUTION = (640, 480)                   # Display resolution

# ---------------- Initialize TFLite Interpreter ----------------
interpreter = tflite.Interpreter(model_path=MODEL_PATH)
interpreter.allocate_tensors()
input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# ---------------- Define Labels ----------------
labels = ["person"]  # "person" at index 0

# ---------------- Initialize Picamera2 ----------------
picam2 = Picamera2()
config = picam2.create_video_configuration(main={"size": DISPLAY_RESOLUTION, "format": "RGB888"})
picam2.configure(config)
picam2.start()

# ---------------- Tracking and Counting Variables ----------------
tracked_objects = {}  # Stores tracked objects with keys: centroid, bbox, last_y, state, counted, lost
next_object_id = 0
counter = 0
counter_lock = threading.Lock()
script_dir = os.path.dirname(os.path.realpath(__file__))  # Get script directory

# ---------------- Frame Capture Thread ----------------
frame_queue = queue.Queue(maxsize=1)

def capture_frames():
    while True:
        frame = picam2.capture_array()
        if frame_queue.full():
            try:
                frame_queue.get_nowait()
            except queue.Empty:
                pass
        frame_queue.put(frame)

capture_thread = threading.Thread(target=capture_frames, daemon=True)
capture_thread.start()

# ---------------- Counter File Writing Thread ----------------
def write_counter_to_file():
    while True:
        time.sleep(5)
        with counter_lock:
            current_counter = counter
        
        file_path = os.path.join(script_dir, "counter.txt")
        try:
            with open(file_path, "w") as f:
                f.write(str(current_counter))
                f.flush()  # Force immediate write
                os.fsync(f.fileno())  # Ensure write to disk
            print(f"Counter updated: {current_counter}")  # Debug output
        except Exception as e:
            print(f"Error writing to file: {e}")

write_thread = threading.Thread(target=write_counter_to_file, daemon=True)
write_thread.start()

# ---------------- Utility Functions ----------------
def draw_lines(frame):
    """Draw two horizontal lines on the frame and return their y positions."""
    h, w, _ = frame.shape
    top_line = int(h * 0.3)
    bottom_line = int(h * 0.4)
    cv2.line(frame, (0, top_line), (w, top_line), (255, 0, 0), 2)
    cv2.line(frame, (0, bottom_line), (w, bottom_line), (0, 0, 255), 2)
    return top_line, bottom_line

def update_tracking(detections, tracked_objects):
    global next_object_id
    new_tracked = {}
    used_detections = set()
    
    # Existing object matching
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
                "last_y": obj["centroid"][1],
                "state": obj["state"],
                "counted": obj["counted"],
                "lost": 0
            }
            used_detections.add(best_match)
        else:
            obj["lost"] += 1
            if obj["lost"] < 5:
                new_tracked[obj_id] = obj
                
    # Add new detections
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
    global counter
    for obj in tracked_objects.values():
        current_y = obj["centroid"][1]
        last_y = obj["last_y"]
        if obj["state"] is None:
            if last_y < top_line and current_y >= top_line:
                obj["state"] = "top_crossed"
            elif last_y > bottom_line and current_y <= bottom_line:
                obj["state"] = "bottom_crossed"
        else:
            if not obj["counted"]:
                if obj["state"] == "top_crossed" and current_y >= bottom_line:
                    with counter_lock:
                        counter -= 1  # Decrements for top-to-bottom crossing
                    obj["counted"] = True
                elif obj["state"] == "bottom_crossed" and current_y <= top_line:
                    with counter_lock:
                        counter += 1  # Increments for bottom-to-top crossing
                    obj["counted"] = True

def draw_tracked_objects(frame, tracked_objects):
    for obj_id, obj in tracked_objects.items():
        left, top_coord, right, bottom = obj["bbox"]
        cv2.putText(frame, f"ID {obj_id}", (left, top_coord - 10),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

# ---------------- Main Loop ----------------
try:
    while True:
        try:
            frame = frame_queue.get(timeout=1)
        except queue.Empty:
            continue

        display_frame = frame.copy()
        top_line, bottom_line = draw_lines(display_frame)

        # Inference processing
        img_resized = cv2.resize(frame, INPUT_SIZE)
        input_data = np.expand_dims(img_resized.astype(np.uint8), axis=0)
        
        interpreter.set_tensor(input_details[0]['index'], input_data)
        interpreter.invoke()

        # Process detections
        boxes = interpreter.get_tensor(output_details[0]['index'])[0]
        classes = interpreter.get_tensor(output_details[1]['index'])[0]
        scores = interpreter.get_tensor(output_details[2]['index'])[0]

        detections = []
        for i in range(len(scores)):
            if scores[i] > MIN_CONFIDENCE and int(classes[i]) == 0:
                ymin, xmin, ymax, xmax = boxes[i]
                left = int(xmin * display_frame.shape[1])
                top_coord = int(ymin * display_frame.shape[0])
                right = int(xmax * display_frame.shape[1])
                bottom = int(ymax * display_frame.shape[0])
                cx = int((left + right) / 2)
                cy = int((top_coord + bottom) / 2)
                detections.append({
                    "bbox": (left, top_coord, right, bottom),
                    "centroid": (cx, cy)
                })
                cv2.rectangle(display_frame, (left, top_coord), (right, bottom), (0, 255, 0), 2)
                label_text = f"person: {scores[i]*100:.1f}%"
                cv2.putText(display_frame, label_text, (left, top_coord - 10),
                            cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

        # Update tracking and counts
        tracked_objects = update_tracking(detections, tracked_objects)
        check_crossings(tracked_objects, top_line, bottom_line)
        for obj in tracked_objects.values():
            obj["last_y"] = obj["centroid"][1]
        draw_tracked_objects(display_frame, tracked_objects)

        # Display counter
        cv2.putText(display_frame, f"Count: {counter}", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 0, 255), 2)
        cv2.imshow("Human Detection and Counting", display_frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

finally:
    picam2.stop()
    cv2.destroyAllWindows()
