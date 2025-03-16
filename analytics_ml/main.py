import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error

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

# Specify the path to your CSV file
file_path = "crowding_data.csv"  # Replace with your actual file path

# Load the data
df = load_data(file_path)
if df is None:
    print("Failed to load data. Please check the file path and try again.")
    exit()

# Convert 'location' to a numerical format using Label Encoding
df["location_encoded"] = df["location"].astype("category").cat.codes

# Feature engineering: Create a datetime feature and extract day of week
df['datetime'] = pd.to_datetime(df[['year', 'month', 'day', 'hour']].astype(str).agg('-'.join, axis=1), 
                              format='%Y-%m-%d-%H')
df['day_of_week'] = df['datetime'].dt.dayofweek  # 0 = Monday, 6 = Sunday

# Select features
X = df[["day", "hour", "location_encoded", "day_of_week"]]
y = df["crowd_level"]

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train the Random Forest Regressor
model = RandomForestRegressor(n_estimators=200, random_state=42)
model.fit(X_train, y_train)

# Evaluate the model
y_pred = model.predict(X_test)
mae = mean_absolute_error(y_test, y_pred)
print(f"Eroare Medie Absolută (MAE): {mae:.2f}")

# Define a prediction function for day, hour, and location
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

# Example prediction for March 18, 2025 (Tuesday)
test_year = 2025
test_month = 6
test_day = 28
test_hour = 18
test_location = "Steak & Shake"
try:
    predicted_crowd = predict_crowd(test_year, test_month, test_day, test_hour, test_location)
    print(f"Predicted crowd level for {test_year}-{test_month:02d}-{test_day:02d} at {test_hour:00d}:00 "
          f"at {test_location}: {predicted_crowd}")
except ValueError as e:
    print(e)