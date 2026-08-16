import os
import json
import random
import time
import requests
import re
import urllib.parse
from itertools import product

categories_data = {
    "Đồ ăn vặt hữu cơ": {
        "prefixes": ["Bánh", "Kẹo", "Mứt", "Hạt", "Trái cây sấy", "Ngũ cốc", "Rong biển", "Snack", "Khô", "Bỏng ngô"],
        "cores": ["hữu cơ", "nguyên cám", "không đường", "diet", "thuần chay", "giảm cân", "mix", "ngũ cốc", "sấy giòn", "sấy dẻo"],
        "suffixes": ["Đà Lạt", "Tây Bắc", "nhà làm", "cao cấp", "đặc biệt", "Việt Nam", "tự nhiên", "healthy", "nhập khẩu", "truyền thống"]
    },
    "Rau củ quả Việt": {
        "prefixes": ["Rau", "Cải", "Bí", "Bầu", "Cà chua", "Dưa leo", "Khoai tây", "Khoai lang", "Cà rốt", "Nấm", "Xà lách", "Bắp cải"],
        "cores": ["hữu cơ", "thuỷ canh", "sạch", "an toàn", "VietGAP", "GlobalGAP", "trồng chậu", "baby", "khổng lồ", "ngọt"],
        "suffixes": ["Đà Lạt", "Mộc Châu", "Sapa", "nhà kính", "xuất khẩu", "miền Tây", "tươi mới", "chất lượng cao"]
    },
    "Trái cây Việt": {
        "prefixes": ["Xoài", "Mít", "Sầu riêng", "Bơ", "Thanh long", "Nhãn", "Vải", "Bưởi", "Cam", "Quýt", "Dừa", "Dưa hấu", "Chôm chôm"],
        "cores": ["cát Hòa Lộc", "thái", "Ri6", "034", "ruột đỏ", "lồng", "thiều", "da xanh", "sành", "đường", "xiêm", "không hạt"],
        "suffixes": ["loại 1", "đặc sản", "chuẩn VietGAP", "hữu cơ", "miền Tây", "Tiền Giang", "Bến Tre", "Bắc Giang", "xuất khẩu"]
    },
    "Mỹ phẩm hữu cơ": {
        "prefixes": ["Xà bông", "Dầu gội", "Sữa tắm", "Kem dưỡng", "Son", "Toner", "Nước hoa hồng", "Mặt nạ", "Serum", "Kem chống nắng", "Tẩy trang"],
        "cores": ["bồ kết", "vỏ bưởi", "nghệ", "mật ong", "trà xanh", "nha đam", "hoa hồng", "than tre", "cám gạo", "dầu dừa", "gấc"],
        "suffixes": ["handmade", "thiên nhiên", "hữu cơ", "cho da nhạy cảm", "trị mụn", "dưỡng ẩm", "trắng da", "chống lão hóa", "thuần chay"]
    },
    "Gạo & Ngũ cốc": {
        "prefixes": ["Gạo", "Nếp", "Đậu", "Hạt", "Ngũ cốc", "Yến mạch", "Mè", "Hạt điều", "Hạt óc chó", "Hạnh nhân", "Hạt sen"],
        "cores": ["ST25", "ST24", "lứt", "đỏ", "đen", "xanh", "đỏ", "hữu cơ", "rang", "nguyên cám", "mix", "sấy"],
        "suffixes": ["Sóc Trăng", "Đồng Tháp", "Tây Bắc", "xuất khẩu", "dinh dưỡng", "cho người tiểu đường", "ăn kiêng", "thượng hạng"]
    },
    "Gia vị Việt": {
        "prefixes": ["Tiêu", "Tỏi", "Hành", "Gừng", "Nghệ", "Quế", "Hồi", "Ớt", "Muối", "Nước mắm", "Tương ớt", "Sa tế"],
        "cores": ["đen", "sọ", "cô đơn", "khô", "bột", "nguyên hoa", "chỉ thiên", "tôm", "nhỉ", "truyền thống", "cay", "chua ngọt"],
        "suffixes": ["Phú Quốc", "Lý Sơn", "Thái Nguyên", "Tây Ninh", "Thanh Hóa", "Lạng Sơn", "Tây Bắc", "hữu cơ", "nhà làm", "đặc sản"]
    },
    "Đồ dùng nhà bếp": {
        "prefixes": ["Đũa", "Muỗng", "Ống hút", "Túi", "Hộp", "Dao", "Thớt", "Khay", "Rổ", "Tạp dề", "Nước rửa chén", "Giẻ lau"],
        "cores": ["tre", "dừa", "gỗ", "vải canvas", "thủy tinh", "inox", "sứ", "nhựa sinh học", "silicone", "bã mía", "sinh học"],
        "suffixes": ["tự nhiên", "xuất khẩu", "cao cấp", "chống khuẩn", "bảo vệ môi trường", "tái sử dụng", "hữu cơ", "an toàn"]
    },
    "Mật ong Việt": {
        "prefixes": ["Mật ong", "Sữa ong chúa", "Phấn hoa", "Sáp ong", "Mật ong lên men", "Mật ong ngâm"],
        "cores": ["rừng", "hoa nhãn", "hoa cà phê", "hoa bạc hà", "U Minh", "Tây Nguyên", "nguyên chất", "tươi", "tỏi", "chanh đào", "đông trùng hạ thảo"],
        "suffixes": ["hữu cơ", "đặc biệt", "loại 1", "tự nhiên", "xuất khẩu", "bổ dưỡng", "chữa bệnh", "tăng đề kháng"]
    }
}

def generate_products(target_count=500):
    products = []
    category_names = list(categories_data.keys())
    
    count_per_cat = target_count // len(category_names)
    
    for cat in category_names:
        data = categories_data[cat]
        # Generate all combinations
        all_combinations = list(product(data["prefixes"], data["cores"], data["suffixes"]))
        random.shuffle(all_combinations)
        
        selected = all_combinations[:count_per_cat + 5] # some buffer
        for combo in selected:
            name = f"{combo[0]} {combo[1]} {combo[2]}"
            name = " ".join([w.capitalize() for w in name.split()])
            
            desc = f"{name} là sản phẩm chất lượng cao, an toàn cho sức khỏe và thân thiện với môi trường. Phù hợp cho mọi gia đình Việt."
            price = random.randint(10, 500) * 1000
            
            products.append({
                "name": name,
                "description": desc,
                "price": price,
                "stock": random.randint(10, 200),
                "category": cat,
                "image": "",
                "gallery": [],
                "is_featured": random.choice([True, False, False])
            })
            if len(products) >= target_count:
                break
        if len(products) >= target_count:
            break
            
    return products[:target_count]

def search_bing_image(query):
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36"
    }
    url = f"https://www.bing.com/images/search?q={urllib.parse.quote(query)}&form=HDRSC3&first=1"
    try:
        resp = requests.get(url, headers=headers, timeout=10)
        matches = re.findall(r'murl&quot;:&quot;(http.*?)&quot;', resp.text)
        if matches:
            unique = list(dict.fromkeys(matches))
            return unique[:4]
    except Exception as e:
        print(f"Bing error: {e}")
    return []

print("Generating 500 products data...")
new_products = generate_products(500)
print(f"Generated {len(new_products)} products.")

print("Fetching images from Bing (this will take a while)...")
success_count = 0

# Split into chunks of 100 to save intermediate progress
chunk_size = 100
for i in range(0, len(new_products), chunk_size):
    chunk = new_products[i:i+chunk_size]
    
    for idx, item in enumerate(chunk):
        real_idx = i + idx + 1
        name = item['name']
        print(f"[{real_idx}/500] Searching image for: {name}...")
        
        urls = search_bing_image(f"{name} sản phẩm")
        if urls and len(urls) > 0:
            item['image'] = urls[0]
            if len(urls) > 1:
                item['gallery'] = urls[1:]
            success_count += 1
            print(f"  -> Found {len(urls)} images")
        else:
            print("  -> Not found.")
            
        time.sleep(1)
        
    # Save chunk to JSON file to be seeded later
    filename = f"database/data/generated_products_part_{i//chunk_size + 1}.json"
    with open(filename, 'w', encoding='utf-8') as f:
        json.dump(chunk, f, ensure_ascii=False, indent=4)
    print(f"Saved {filename}")

print(f"\nDone! Successfully found images for {success_count}/{len(new_products)} products.")
print("Running seeder...")
os.system('php artisan db:seed --class=ProductSeeder')
