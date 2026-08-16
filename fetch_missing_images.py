import os
import pymysql
import requests
import time
import re
import urllib.parse

try:
    connection = pymysql.connect(
        host='127.0.0.1',
        user='root',
        password='',
        database='laravel',
        cursorclass=pymysql.cursors.DictCursor
    )
except Exception as e:
    print(f"Database connection failed: {e}")
    exit(1)

storage_path = 'storage/app/public/products'

def search_bing_image(query):
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
    }
    url = f"https://www.bing.com/images/search?q={urllib.parse.quote(query)}&form=HDRSC3&first=1"
    try:
        resp = requests.get(url, headers=headers, timeout=10)
        matches = re.findall(r'murl&quot;:&quot;(.*?)&quot;', resp.text)
        if matches:
            return matches[:5]
    except Exception as e:
        print(f"Bing error: {e}")
    return []

def download_image(url, product_id):
    try:
        response = requests.get(url, timeout=10)
        if response.status_code == 200:
            ext = 'jpg'
            if 'png' in url.lower(): ext = 'png'
            elif 'webp' in url.lower(): ext = 'webp'
            
            filename = f"product_{product_id}_{int(time.time())}.{ext}"
            filepath = os.path.join(storage_path, filename)
            
            with open(filepath, 'wb') as f:
                f.write(response.content)
            
            return f"products/{filename}"
    except Exception as e:
        print(f"Error downloading {url}: {e}")
    return None

with connection.cursor() as cursor:
    cursor.execute("SELECT id, name, image FROM products")
    products = cursor.fetchall()
    
    missing_prods = []
    for prod in products:
        img_path = prod['image']
        if not img_path or not os.path.exists(os.path.join('storage/app/public', img_path)):
            missing_prods.append(prod)
            
    print(f"Found {len(missing_prods)} products missing images...")
    
    for prod in missing_prods:
        prod_id = prod['id']
        prod_name = prod['name']
        
        print(f"Searching image for: {prod_name}...")
        
        search_query = f"{prod_name} sản phẩm"
        
        image_urls = search_bing_image(search_query)
        if image_urls:
            saved_path = None
            for img_url in image_urls:
                print(f"  Found URL: {img_url}")
                saved_path = download_image(img_url, prod_id)
                if saved_path:
                    break
            
            if saved_path:
                cursor.execute(
                    "UPDATE products SET image = %s WHERE id = %s",
                    (saved_path, prod_id)
                )
                connection.commit()
                print(f"  -> Saved and updated DB: {saved_path}")
            else:
                print("  -> Failed to download any images for this product.")
        else:
            print("  -> No image found.")
            
        time.sleep(2)

print("\nDone! All missing products processed.")
connection.close()
