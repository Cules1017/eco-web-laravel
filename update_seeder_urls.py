import os
import json
import glob
import time
import requests
import re
import urllib.parse

data_dir = 'database/data'
json_files = glob.glob(os.path.join(data_dir, '*.json'))

def search_bing_image(query):
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
    }
    url = f"https://www.bing.com/images/search?q={urllib.parse.quote(query)}&form=HDRSC3&first=1"
    try:
        resp = requests.get(url, headers=headers, timeout=10)
        matches = re.findall(r'murl&quot;:&quot;(http.*?)&quot;', resp.text)
        if matches:
            # Return up to 4 unique images
            unique = list(dict.fromkeys(matches))
            return unique[:4]
    except Exception as e:
        print(f"Bing error: {e}")
    return []

for file in json_files:
    print(f"Processing {file}...")
    try:
        with open(file, 'r', encoding='utf-8') as f:
            items = json.load(f)
            
        updated = False
        for item in items:
            name = item.get('name', '')
            if name:
                print(f"  Searching image for {name}...")
                urls = search_bing_image(f"{name} sản phẩm")
                if urls and len(urls) > 0:
                    item['image'] = urls[0]
                    if len(urls) > 1:
                        item['gallery'] = urls[1:]
                    updated = True
                    print(f"    -> Found {len(urls)} images")
                else:
                    print("    -> Not found.")
                time.sleep(1)
                
        if updated:
            with open(file, 'w', encoding='utf-8') as f:
                json.dump(items, f, ensure_ascii=False, indent=4)
            print(f"  Saved {file}")
            
    except Exception as e:
        print(f"Error processing {file}: {e}")

print("Done updating JSON files. Running seeder...")
os.system('php artisan db:seed --class=ProductSeeder')
