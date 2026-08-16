import os
import pymysql
import requests
import time
import re
import urllib.parse

# DB Connection
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

# Output directory for images
storage_path = 'storage/app/public/products'
os.makedirs(storage_path, exist_ok=True)

def search_bing_image(query):
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
    }
    url = f"https://www.bing.com/images/search?q={urllib.parse.quote(query)}&form=HDRSC3&first=1"
    try:
        resp = requests.get(url, headers=headers, timeout=10)
        matches = re.findall(r'murl&quot;:&quot;(.*?)&quot;', resp.text)
        if matches:
            # Return up to 3 matches to try if the first one fails to download
            return matches[:3]
    except Exception as e:
        print(f"Bing error: {e}")
    return []

def download_image(url, product_id):
    try:
        response = requests.get(url, timeout=10)
        if response.status_code == 200:
            content_type = response.headers.get('content-type', '')
            ext = 'jpg'
            if 'png' in content_type: ext = 'png'
            elif 'webp' in content_type: ext = 'webp'
            elif 'gif' in content_type: ext = 'gif'
            else:
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
    cursor.execute("SELECT id, name FROM products")
    products = cursor.fetchall()
    
    print(f"Found {len(products)} products to process...")
    
    for prod in products:
        prod_id = prod['id']
        prod_name = prod['name']
        
        print(f"Searching image for: {prod_name}...")
        
        # Add "sản phẩm" to get better results
        search_query = f"{prod_name} sản phẩm"
        
        image_urls = search_bing_image(search_query)
        if image_urls:
            saved_path = None
            for img_url in image_urls:
                print(f"  Found URL: {img_url}")
                saved_path = download_image(img_url, prod_id)
                if saved_path:
                    break # Success
            
            if saved_path:
                # Update DB
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
            
        time.sleep(1.5) # Be nice to Bing

print("\nDone! All products processed.")
print("Running 'php artisan storage:link' just in case...")
os.system('php artisan storage:link')
connection.close()
