# -*- coding: utf-8 -*-
import time
from pymongo import MongoClient
from bson.objectid import ObjectId

def read_counter():
    try:
        with open('counter.txt', 'r') as f:
            return int(f.read().strip())
    except Exception as e:
        print(f"Eroare la citirea counterului: {e}")
        return None

# Conectare la MongoDB
client = MongoClient('mongodb+srv://dstezar06:itfest2025@crowdflow.9c2rs.mongodb.net/?retryWrites=true&w=majority&appName=CrowdFlow')
db = client['crowdFlow']

read_count = 0

try:
    while True:
        current_count = read_counter()
        print(f"Counter citit din fisier: {current_count}")
        
        if current_count is None:
            time.sleep(10)
            continue
        
        read_count += 1
        
        if read_count >= 6:  # 10 sec * 6 = 60 sec
            try:
                db.locations.update_one(
                    {"_id": ObjectId("67d56cc288ff8426b0a9d87e")},
                    {"$set": {"count": current_count}}
                )
                print(f"Counter actualizat in MongoDB: {current_count}")
            except Exception as e:
                print(f"Eroare la actualizarea MongoDB: {e}")
            
            read_count = 0
        
        time.sleep(10)

except KeyboardInterrupt:
    print("\nScript oprit manual")
