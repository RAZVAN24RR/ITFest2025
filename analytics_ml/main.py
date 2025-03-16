import pandas as pd
from flask import Flask, request, jsonify
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Function to read data from a file
def load_data(file_path):
    try:
        df = pd.read_csv(file_path)
        return df
    except FileNotFoundError:
        print(f"Error: File '{file_path}' not found.")
        return None
    except Exception as e:
        print(f"Error loading file: {e}")
        return None

# Specify the path to your CSV file (adjust as necessary)
file_path = "crowding_data.csv"
df = load_data(file_path)
if df is None:
    raise FileNotFoundError("Failed to load data. Please check the file path and try again.")

# Preprocess data: Encode 'location' and create datetime and day-of-week features
df["location_encoded"] = df["location"].astype("category").cat.codes
df['datetime'] = pd.to_datetime(df[['year', 'month', 'day', 'hour']].astype(str).agg('-'.join, axis=1),
                               format='%Y-%m-%d-%H')
df['day_of_week'] = df['datetime'].dt.dayofweek  # 0 = Monday, 6 = Sunday

# Select features and target
X = df[["day", "hour", "location_encoded", "day_of_week"]]
y = df["crowd_level"]

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train the Random Forest Regressor
model = RandomForestRegressor(n_estimators=200, random_state=42)
model.fit(X_train, y_train)

# Optionally, evaluate the model (this prints evaluation metrics on startup)
y_pred = model.predict(X_test)
mae = mean_absolute_error(y_test, y_pred)
print(f"Eroare Medie Absolută (MAE): {mae:.2f}")

# Define a prediction function for the given inputs
def predict_crowd(year, month, day, hour, location):
    # Map the location to its encoded value
    location_map = dict(zip(df["location"], df["location_encoded"]))
    if location not in location_map:
        raise ValueError(f"Location '{location}' not found in the dataset.")
    location_encoded = location_map[location]

    # Calculate day of week for the input date
    datetime_input = pd.to_datetime(f"{year}-{month}-{day}-{hour}", format='%Y-%m-%d-%H')
    day_of_week = datetime_input.dayofweek

    # Create input data
    input_data = pd.DataFrame([[day, hour, location_encoded, day_of_week]],
                              columns=["day", "hour", "location_encoded", "day_of_week"])
    prediction = model.predict(input_data)
    # Clamp prediction to valid range (1-4)
    return max(1, min(4, round(prediction[0])))

# Define Flask endpoint for prediction
@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json(force=True)
    print(f"Received data: {data}")
    try:
        # Retrieve parameters from the JSON body
        month = data.get("month")
        day = data.get("day")
        hour = data.get("hour")
        location = data.get("location")

        if month is None or day is None or hour is None or location is None:
            return jsonify({
                "error": "Missing required parameter(s). Parameters needed: month, day, hour, location"
            }), 400

        try:
            month = int(month)
            day = int(day)
            hour = int(hour)
        except Exception as e:
            return jsonify({
                "error": "Parameters 'month', 'day', and 'hour' must be integers."
            }), 400

        # Hardcoded year 2025
        year = 2025

        prediction = predict_crowd(year, month, day, hour, location)
        return jsonify({"predicted_crowd_level": prediction}), 200

    except ValueError as ve:
        return jsonify({"error": str(ve)}), 400
    except Exception as e:
        return jsonify({"error": f"An error occurred during prediction: {str(e)}"}), 500

if __name__ == '__main__':
    # Run the Flask app (by default it will run on http://127.0.0.1:5000)
    app.run(debug=True)