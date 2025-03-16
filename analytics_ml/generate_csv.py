# generate_data.py

import pandas as pd
import random
from datetime import datetime, timedelta

# Lista de locații
locations = [
    "Haufe Group",
    "GymOne 3",
    "Lidl",
    "Curtea Berarilor La Fabrica",
    "Jack's Bistro",
    "Spitalul Clinic Județean de Urgență",
    "Hotel Continental",
    "Kaufland",
    "Dedeman",
    "La Focacceria",
    "Pepper - Steak & Shake",
    "Starbucks"
]

# Setarea perioadei de 3 ani: de exemplu, 1 ianuarie 2021 până pe 31 decembrie 2023
start_date = datetime(2021, 1, 1)
end_date = datetime(2023, 12, 31)

data_records = []
current_date = start_date

while current_date <= end_date:
    # parcurgem fiecare oră din zi
    for hour in range(24):
        # pentru fiecare locație
        for loc in locations:
            # Generează un nivel de aglomerare integer între 1 și 4
            crowd_level = random.randint(1, 4)
            data_records.append({
                "year": current_date.year,
                "month": current_date.month,
                "day": current_date.day,
                "hour": hour,
                "location": loc,
                "crowd_level": crowd_level
            })
    current_date += timedelta(days=1)

# Crearea DataFrame și salvarea datelor într-un fișier CSV
df = pd.DataFrame(data_records)
df.to_csv("crowding_data.csv", index=False)
print("Fișierul 'crowding_data.csv' a fost generat cu succes.")