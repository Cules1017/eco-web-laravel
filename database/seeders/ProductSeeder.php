<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categoryImages = $this->getCategoryImages();
        $categoryNames = $this->getCategoryProductNames();
        $categoryDescriptions = $this->getCategoryDescriptions();
        $categories = Category::all();
        $totalProducts = 100;
        $categoryCount = $categories->count();
        $productsPerCategory = (int) ceil($totalProducts / $categoryCount);
        $productIndex = 1;
        foreach ($categories as $category) {
            $images = $categoryImages[$category->name] ?? $categoryImages['default'];
            $names = $categoryNames[$category->name] ?? $categoryNames['default'];
            $descriptions = $categoryDescriptions[$category->name] ?? $categoryDescriptions['default'];
            shuffle($images);
            for ($i = 0; $i < $productsPerCategory && $productIndex <= $totalProducts; $i++, $productIndex++) {
                $name = $names[array_rand($names)] . ' ' . (rand(1, 1000));
                $desc = $descriptions[array_rand($descriptions)];
            $price = rand(100000, 50000000);
            Product::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $desc,
                'price' => $price,
                    'stock' => rand(5, 100),
                'category_id' => $category->id,
                    'image' => $images[$i % count($images)],
                'is_active' => true,
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
                'is_featured' => rand(0, 1) == 1,
            ]);
            }
        }
    }

    private function getCategoryImages()
    {
        return [
            'Điện thoại' => [
                'https://th.bing.com/th/id/OIP.zndktJVCQO8B4PpT2zTe7wHaEm?w=316&h=196&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.b5jNxB-SLFKRyKt3iLY77wHaHa?w=197&h=196&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP._TNU1Jevs3BuaHZ0drjwywHaGN?w=207&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.JG-a7UDKnhlWiB18sBLaPAHaEw?w=208&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.N8pGIVTkaEDa-fxbE37yFAHaDD?w=289&h=144&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th?id=OIF.kqdNM3d%2fihEAgPkB4WFhZQ&w=184&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'Laptop' => [
                'https://th.bing.com/th/id/OIP.VKgu7VPcY6IrtLdd64zgvgHaEK?w=297&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.5oYd7xs2reD3H5gBFUOw2gHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.jXa6_P7aiOOS_LJ7ekjL3gHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.kdIiAvKxZ7D6eOkGEIF-lwHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.1Dr8Gi8s37jJ00UB06cE2wHaEK?w=307&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.zDmoN7FQEW8t4NrDOAhW9QHaEK?w=290&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.fo3-36w66u4ViiIDEKLBxQHaEK?w=316&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
            ],
            'Phụ kiện' => [
                'https://th.bing.com/th/id/OIP.VKgu7VPcY6IrtLdd64zgvgHaEK?w=297&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.5oYd7xs2reD3H5gBFUOw2gHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.jXa6_P7aiOOS_LJ7ekjL3gHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.kdIiAvKxZ7D6eOkGEIF-lwHaEK?w=320&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.1Dr8Gi8s37jJ00UB06cE2wHaEK?w=307&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.zDmoN7FQEW8t4NrDOAhW9QHaEK?w=290&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.fo3-36w66u4ViiIDEKLBxQHaEK?w=316&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',],
            'Đồng hồ' => [
                'https://th.bing.com/th/id/OIP.i0x_quZgUxYVq1zoLZNeWAHaE7?w=274&h=183&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.T1eMmsYFofKiUWVHdw4ipAHaHa?w=196&h=196&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.hhK994maL_FxrZDM57SURgHaHa?w=190&h=190&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIF.J3C7jbf9YvYic3Usn9Qpfg?w=194&h=194&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'Tủ lạnh' => [
                'https://th.bing.com/th/id/OIP.DWwW39XzEAgsazcEsfdkyAHaHa?w=181&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.ki5NZCi1IE6ndReCBbLvJwHaE7?w=334&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.XbYNHdtoMCEovgzwfc-PgAHaHa?w=196&h=197&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.VySVZHoh-eY7bViwN4muQgHaHa?w=149&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'Máy lạnh' => [
                'https://th.bing.com/th/id/OIP.IIJa-MddMmDzt-iVZ3O5HwHaET?w=275&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.ukBJpBd1ZtdknzZy-f6UtAHaET?w=271&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP._X3wSY0FlpI7R8fomgLV1QHaEK?w=332&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.zheRJTy1T5H1b8WQ8KB90AHaE8?w=267&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'Máy giặt' => [
                'https://th.bing.com/th/id/OIP.RoKSxE9_4tX2xcgZ8CQZ8QHaEo?w=251&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.wsLQf5fjwknPeuev5hjXkAHaEa?w=271&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.BLvE4rrAw14LgE9_27a_JwHaEH?w=289&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.yUIxG_mW34gkTOvNnv3GOQHaEo?w=257&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'Thời trang' => [
                'https://th.bing.com/th/id/OIP.kXG9RmXwH3C4L1TEK8xymwHaDt?w=344&h=175&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.wZxQQmY8Nql8ZDIdpsxKFAHaE8?w=273&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.WwtSVnfi_sLtUgULj7VJ-gHaEo?w=355&h=183&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.sDrQaGeTzMiEemlFcksxkQHaEo?w=251&h=183&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
            'default' => [
                'https://th.bing.com/th/id/OIP.HPpnarMGBl1-wu4otSTjLQHaEK?w=323&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.L7QZFmOgU_HVD8Et4B8bBgHaF7?w=233&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3',
                'https://th.bing.com/th/id/OIP.U6JogBrujHphgGByGg-hrgHaHa?w=182&h=182&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'
            ],
        ];
    }

    private function getCategoryProductNames()
    {
        return [
            'Điện thoại' => ['iPhone 15 Pro Max', 'Samsung Galaxy S24 Ultra', 'Xiaomi 14 Ultra', 'OPPO Find X7', 'Vivo X100', 'Realme GT Neo', 'Asus ROG Phone', 'Nokia G50'],
            'Laptop' => ['MacBook Pro M3', 'Dell XPS 13', 'HP Spectre x360', 'Asus ZenBook 14', 'Lenovo ThinkPad X1', 'Acer Swift 5', 'MSI Modern 15', 'LG Gram 17'],
            'Phụ kiện' => ['AirPods Pro 2', 'Sạc dự phòng Xiaomi', 'Loa Bluetooth JBL', 'Bàn phím cơ Logitech', 'Chuột không dây Razer', 'Thẻ nhớ SanDisk', 'Cáp sạc Anker', 'Ốp lưng Spigen'],
            'Đồng hồ' => ['Apple Watch Ultra 2', 'Samsung Galaxy Watch6', 'Garmin Fenix 7', 'Xiaomi Watch S1', 'Casio G-Shock', 'Fossil Gen 6', 'Amazfit GTR 4', 'Huawei Watch GT'],
            'Tủ lạnh' => ['LG Inverter 420L', 'Samsung Inverter 300L', 'Panasonic PrimeFresh', 'Toshiba Inverter 250L', 'Sharp SJ-X176E', 'Aqua AQR-IW338EB', 'Electrolux EME3700BG', 'Mitsubishi Electric MR-FV24J'],
            'Máy lạnh' => ['Daikin Inverter 1HP', 'Panasonic Inverter 1.5HP', 'LG DualCool 2HP', 'Samsung WindFree 1HP', 'Toshiba Inverter 1.5HP', 'Mitsubishi Heavy 1HP', 'Sharp Inverter 1HP', 'Aqua AQA-KCRV9WN'],
            'Máy giặt' => ['LG Inverter 9kg', 'Samsung AddWash 8kg', 'Panasonic NA-F90A4GRV', 'Toshiba AW-DUH1100GV', 'Electrolux EWF1024BDWA', 'Aqua AQW-FR100ET', 'Sharp ES-U102HV-S', 'Beko WCV10746M'],
            'Thời trang' => ['Áo thun nam basic', 'Váy nữ dáng dài', 'Quần jeans rách', 'Áo sơ mi caro', 'Giày sneaker trắng', 'Áo khoác bomber', 'Chân váy xếp ly', 'Áo hoodie oversize'],
            'default' => ['Sản phẩm đặc biệt', 'Mẫu mã mới', 'Phiên bản giới hạn', 'Sản phẩm cao cấp', 'Hàng nhập khẩu', 'Sản phẩm phổ biến', 'Mẫu mã hot', 'Sản phẩm tiêu chuẩn'],
        ];
    }

    private function getCategoryDescriptions()
    {
        return [
            'Điện thoại' => [
                'Điện thoại thông minh với hiệu năng mạnh mẽ, camera sắc nét, pin lâu.',
                'Thiết kế sang trọng, màn hình lớn, hỗ trợ 5G.',
                'Bảo mật vân tay, nhận diện khuôn mặt, sạc nhanh.',
                'Hệ điều hành mới nhất, nhiều tính năng thông minh.',
            ],
            'Laptop' => [
                'Laptop mỏng nhẹ, pin trâu, hiệu năng vượt trội cho công việc và giải trí.',
                'Màn hình sắc nét, bàn phím êm, bảo mật vân tay.',
                'Hỗ trợ sạc nhanh, nhiều cổng kết nối hiện đại.',
                'Thiết kế sang trọng, phù hợp doanh nhân.',
            ],
            'Phụ kiện' => [
                'Phụ kiện công nghệ chính hãng, bảo hành 12 tháng.',
                'Thiết kế nhỏ gọn, tiện lợi mang theo.',
                'Tương thích nhiều thiết bị, chất lượng cao.',
                'Giá tốt, nhiều ưu đãi hấp dẫn.',
            ],
            'Đồng hồ' => [
                'Đồng hồ thông minh, theo dõi sức khỏe, pin bền.',
                'Chống nước, nhiều chế độ luyện tập.',
                'Thiết kế thời trang, dây đeo thoải mái.',
                'Kết nối Bluetooth, thông báo thông minh.',
            ],
            'Tủ lạnh' => [
                'Tủ lạnh tiết kiệm điện, dung tích lớn.',
                'Công nghệ làm lạnh nhanh, khử mùi hiệu quả.',
                'Thiết kế hiện đại, ngăn đá rộng.',
                'Bảo hành chính hãng 2 năm.',
            ],
            'Máy lạnh' => [
                'Máy lạnh inverter tiết kiệm điện, làm lạnh nhanh.',
                'Chế độ lọc không khí, khử mùi.',
                'Thiết kế nhỏ gọn, dễ lắp đặt.',
                'Bảo hành chính hãng 2 năm.',
            ],
            'Máy giặt' => [
                'Máy giặt cửa trước, giặt sạch sâu, tiết kiệm nước.',
                'Nhiều chế độ giặt, vận hành êm ái.',
                'Thiết kế hiện đại, dễ sử dụng.',
                'Bảo hành chính hãng 2 năm.',
            ],
            'Thời trang' => [
                'Chất liệu cao cấp, form dáng chuẩn.',
                'Màu sắc trẻ trung, dễ phối đồ.',
                'Thiết kế hiện đại, hợp xu hướng.',
                'Giá tốt, nhiều size lựa chọn.',
            ],
            'default' => [
                'Sản phẩm chất lượng cao, nhiều tính năng nổi bật.',
                'Thiết kế hiện đại, phù hợp nhiều nhu cầu.',
                'Bảo hành chính hãng, giá tốt.',
                'Ưu đãi hấp dẫn, số lượng có hạn.',
            ],
        ];
    }
} 