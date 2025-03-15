import cv2
import numpy as np
import tflite_runtime.interpreter as tflite

# Configuration
MODEL_PATH = "models/efficientdet_lite0.tflite"  # Human detection model
LABELS_PATH = "labels.txt"  # Class labels
MIN_CONFIDENCE = 0.5
LINE_POSITIONS = (0.3, 0.7)  # Relative Y positions for entry/exit lines

# Initialize TFLite
interpreter = tflite.Interpreter(model_path=MODEL_PATH)
interpreter.allocate_tensors()
input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# Load labels
with open(LABELS_PATH, 'r') as f:
    labels = [line.strip() for line in f.readlines()]

# Camera setup
cap = cv2.VideoCapture(0)
cap.set(3, 640)
cap.set(4, 480)

# Tracking variables
tracked_objects = {}
entry_count = 0
exit_count = 0

def draw_lines(frame):
    h, w = frame.shape[:2]
    entry_line = int(h * LINE_POSITIONS[0])
    exit_line = int(h * LINE_POSITIONS[1])
    cv2.line(frame, (0, entry_line), (w, entry_line), (0, 255, 0), 2)
    cv2.line(frame, (0, exit_line), (w, exit_line), (0, 0, 255), 2)
    return entry_line, exit_line

def update_tracking(centroids):
    global tracked_objects
    updated_objects = {}
    for obj_id, centroid in enumerate(centroids):
        min_dist = float('inf')
        matched_key = None
        for key, value in tracked_objects.items():
            dist = np.linalg.norm(np.array(value) - np.array(centroid))
            if dist < min_dist:
                min_dist = dist
                matched_key = key
        if matched_key is not None and min_dist < 50:
            updated_objects[matched_key] = centroid
        else:
            updated_objects[len(tracked_objects)] = centroid
    tracked_objects = updated_objects

def check_crossing(prev_pos, curr_pos, entry_line, exit_line):
    global entry_count, exit_count
    for obj_id, pos in curr_pos.items():
        if obj_id not in prev_pos:
            continue
        prev_y = prev_pos[obj_id][1]
        curr_y = pos[1]
        
        # Check entry line crossing (from top to bottom)
        if prev_y < entry_line and curr_y > entry_line:
            entry_count += 1
        # Check exit line crossing (from bottom to top)
        elif prev_y > exit_line and curr_y < exit_line:
            exit_count += 1

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # Draw lines and get their positions
    entry_line, exit_line = draw_lines(frame)
    
    # Run inference
    img_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    img_resized = cv2.resize(img_rgb, (320, 320))
    input_data = np.expand_dims(img_resized, axis=0)
    
    interpreter.set_tensor(input_details[0]['index'], input_data)
    interpreter.invoke()
    
    # Process detections
    boxes = interpreter.get_tensor(output_details[0]['index'])[0]
    classes = interpreter.get_tensor(output_details[1]['index'])[0]
    scores = interpreter.get_tensor(output_details[2]['index'])[0]
    
    centroids = []
    h, w = frame.shape[:2]
    
    for i in range(len(scores)):
        if scores[i] > MIN_CONFIDENCE and labels[int(classes[i])] == 'person':
            ymin, xmin, ymax, xmax = boxes[i]
            x_center = int((xmin + xmax) * w / 2)
            y_center = int((ymin + ymax) * h / 2)
            centroids.append((x_center, y_center))
            cv2.circle(frame, (x_center, y_center), 5, (255, 0, 0), -1)

    # Update tracking and check crossings
    prev_positions = tracked_objects.copy()
    update_tracking(centroids)
    check_crossing(prev_positions, tracked_objects, entry_line, exit_line)
    
    # Display counts
    cv2.putText(frame, f"Entries: {entry_count}", (10, 30),
                cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)
    cv2.putText(frame, f"Exits: {exit_count}", (10, 60),
                cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 0, 255), 2)
    
    cv2.imshow('People Counter', frame)
    
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
