import os
import json
import random
import time
import requests
import re
import urllib.parse
from itertools import product

categories_data = {
    "Điện thoại": {
        "prefixes": ["Điện thoại", "Smartphone", "iPhone", "Samsung Galaxy", "Xiaomi Redmi"],
        "cores": ["Pro", "Max", "Ultra", "Plus", "Lite", "5G"],
        "suffixes": ["128GB", "256GB", "512GB", "Chính Hãng", "Quốc Tế", "Mới 100%"]
    },
    "Dụng cụ thể thao": {
        "prefixes": ["Vợt", "Bóng", "Thảm", "Tạ", "Găng tay", "Dây nhảy", "Đai lưng", "Con lăn"],
        "cores": ["Tennis", "Cầu lông", "Yoga", "Tập gym", "Bóng đá", "Bóng rổ", "Tập bụng"],
        "suffixes": ["Cao cấp", "Chuyên nghiệp", "Nhập khẩu", "Siêu nhẹ", "Chống trượt", "Bền bỉ"]
    },
    "Đèn trang trí": {
        "prefixes": ["Đèn", "Dây đèn", "Chụp đèn", "Đèn ngủ", "Đèn bàn", "Đèn chùm", "Đèn thả"],
        "cores": ["LED", "Decor", "Vintage", "RGB", "Năng lượng mặt trời", "Pha lê", "Neon"],
        "suffixes": ["Phòng ngủ", "Sân vườn", "Gắn tường", "Siêu sáng", "Sang trọng", "Cảm ứng"]
    },
    "Đồ chơi": {
        "prefixes": ["Bộ xếp hình", "Mô hình", "Gấu bông", "Đồ chơi", "Búp bê", "Xe điều khiển", "Con quay"],
        "cores": ["Lego", "Gỗ", "Giáo dục", "Lắp ráp", "Thông minh", "Luyện trí nhớ", "Siêu nhân"],
        "suffixes": ["An toàn cho bé", "Cao cấp", "Cỡ lớn", "Chính hãng", "Nhập khẩu", "Vui nhộn"]
    },
    "Đồng hồ": {
        "prefixes": ["Đồng hồ", "Smartwatch", "Đồng hồ thông minh", "Đồng hồ cơ", "Đồng hồ điện tử"],
        "cores": ["Nam", "Nữ", "Thể thao", "Dây da", "Dây kim loại", "Mặt vuông", "Mặt tròn"],
        "suffixes": ["Chống nước", "Cao cấp", "Chính hãng", "Mặt Sapphire", "Bảo hành 2 năm", "Thời trang"]
    },
    "Đồ ăn": {
        "prefixes": ["Mì", "Bánh", "Xúc xích", "Đồ hộp", "Snack", "Pizza", "Cơm cháy", "Khô gà"],
        "cores": ["Cay", "Phô mai", "Hải sản", "Bò", "Gà", "Heo", "Chà bông", "Lá chanh"],
        "suffixes": ["Ăn liền", "Cao cấp", "Đặc biệt", "Combo", "Siêu to khổng lồ", "Đậm vị"]
    },
    "Dụng cụ nhà bếp": {
        "prefixes": ["Chảo", "Nồi", "Dao", "Thớt", "Bộ muỗng nĩa", "Kéo", "Bộ vá súp", "Hộp đựng"],
        "cores": ["Chống dính", "Inox 304", "Thủy tinh", "Nhôm", "Thép không gỉ", "Nhựa nguyên sinh"],
        "suffixes": ["Đáy từ", "Cao cấp", "Nhập khẩu Đức", "Chính hãng", "Bền đẹp", "Chịu nhiệt"]
    },
    "Đồ uống hữu cơ": {
        "prefixes": ["Trà", "Cà phê", "Nước ép", "Sinh tố", "Sữa hạt", "Sữa tươi", "Nước khoáng"],
        "cores": ["Xanh", "Đen", "Robusta", "Arabica", "Cam", "Táo", "Đậu nành", "Hạnh nhân", "Macca"],
        "suffixes": ["Hữu cơ", "Nguyên chất", "Tự nhiên", "Tươi", "Không đường", "Giảm cân", "Thanh lọc"]
    },
    "Đồ ăn vặt hữu cơ": {
        "prefixes": ["Bánh", "Kẹo", "Mứt", "Hạt", "Trái cây sấy", "Rong biển sấy"],
        "cores": ["Hữu cơ", "Nguyên cám", "Thuần chay", "Giảm cân", "Mix"],
        "suffixes": ["Đà Lạt", "Việt Nam", "Healthy", "Không đường", "Ăn kiêng"]
    }
}

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

# Generate products
products = []
for cat, data in categories_data.items():
    combos = list(product(data["prefixes"], data["cores"], data["suffixes"]))
    random.shuffle(combos)
    selected = combos[:10] # 10 per category = 90 products
    for combo in selected:
        name = f"{combo[0]} {combo[1]} {combo[2]}"
        name = " ".join([w.capitalize() for w in name.split()])
        
        desc = f"{name} chất lượng vượt trội. Mẫu mã hiện đại, tiện dụng và đáp ứng mọi nhu cầu của bạn."
        price = random.randint(10, 2000) * 1000
        
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

print(f"Generated {len(products)} products to process...")

success_count = 0
chunk_size = 50
for i in range(0, len(products), chunk_size):
    chunk = products[i:i+chunk_size]
    
    for idx, item in enumerate(chunk):
        real_idx = i + idx + 1
        name = item['name']
        print(f"[{real_idx}/{len(products)}] Searching image for: {name}...")
        
        # Add modifier to get accurate products
        urls = search_bing_image(f"{name} sản phẩm chính hãng")
        if urls and len(urls) > 0:
            item['image'] = urls[0]
            if len(urls) > 1:
                item['gallery'] = urls[1:]
            success_count += 1
            print(f"  -> Found {len(urls)} images")
        else:
            print("  -> Not found.")
            
        time.sleep(1)
        
    filename = f"database/data/generated_specific_categories_part_{i//chunk_size + 1}.json"
    with open(filename, 'w', encoding='utf-8') as f:
        json.dump(chunk, f, ensure_ascii=False, indent=4)
    print(f"Saved {filename}")

print(f"\nDone! Found images for {success_count}/{len(products)} products.")
print("Running seeder...")
os.system('php artisan db:seed --class=ProductSeeder')
