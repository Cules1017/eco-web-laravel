<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $categories = [
            [
                'name' => 'Điện thoại',
                'slug' => 'dien-thoai',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'image' => 'https://images.unsplash.com/photo-1585123334904-845d60e97b29?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Đồng hồ',
                'slug' => 'dong-ho',
                'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Tủ lạnh',
                'slug' => 'tu-lanh',
                'image' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Máy lạnh',
                'slug' => 'may-lanh',
                'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Máy giặt',
                'slug' => 'may-giat',
                'image' => 'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Thú cưng',
                'slug' => 'thu-cung',
                'image' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Đồ ăn',
                'slug' => 'do-an',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Sách',
                'slug' => 'sach',
                'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Nhạc cụ',
                'slug' => 'nhac-cu',
                'image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Đồ chơi',
                'slug' => 'do-choi',
                'image' => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Mẹ và bé',
                'slug' => 'me-va-be',
                'image' => 'https://images.unsplash.com/photo-1519689680058-324335c77eba?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Dụng cụ thể thao',
                'slug' => 'dung-cu-the-thao',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Dụng cụ nhà bếp',
                'slug' => 'dung-cu-nha-bep',
                'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Đèn trang trí',
                'slug' => 'den-trang-tri',
                'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Cây cảnh',
                'slug' => 'cay-canh',
                'image' => 'https://images.unsplash.com/photo-1463154545680-d59320fd685d?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Dịch vụ sửa chữa',
                'slug' => 'dich-vu-sua-chua',
                'image' => 'https://images.unsplash.com/photo-1581092921461-39b9d08a9b21?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Văn phòng phẩm',
                'slug' => 'van-phong-pham',
                'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc36b9d?w=800&auto=format&fit=crop&q=60',
            ],
            [
                'name' => 'Thực phẩm hữu cơ',
                'slug' => 'thuc-pham-huu-co',
                'description' => 'Các sản phẩm thực phẩm được sản xuất theo phương pháp hữu cơ, không sử dụng hóa chất.',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Rau củ quả',
                'slug' => 'rau-cu-qua',
                'description' => 'Rau củ quả tươi ngon, được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1518843875459-f738682238a6?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Trái cây',
                'slug' => 'trai-cay',
                'description' => 'Trái cây tươi ngon, được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Gạo và ngũ cốc',
                'slug' => 'gao-va-ngu-coc',
                'description' => 'Gạo và các loại ngũ cốc được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Đồ uống hữu cơ',
                'slug' => 'do-uong-huu-co',
                'description' => 'Các loại đồ uống được làm từ nguyên liệu hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Gia vị hữu cơ',
                'slug' => 'gia-vi-huu-co',
                'description' => 'Các loại gia vị được trồng và chế biến theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Mật ong và sản phẩm từ ong',
                'slug' => 'mat-ong-va-san-pham-tu-ong',
                'description' => 'Mật ong và các sản phẩm từ ong được nuôi theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
            [
                'name' => 'Đồ ăn vặt hữu cơ',
                'slug' => 'do-an-vat-huu-co',
                'description' => 'Các loại đồ ăn vặt được làm từ nguyên liệu hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'is_active' => true,
                'parent_id' => null,
                'image' => $cat['image'],
            ]);
        }

        // Thêm danh mục con
        $subCategories = [
            [
                'name' => 'Rau xanh',
                'slug' => 'rau-xanh',
                'description' => 'Các loại rau xanh được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1518843875459-f738682238a6?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
                'parent_id' => 2, // ID của danh mục "Rau củ quả"
            ],
            [
                'name' => 'Củ quả',
                'slug' => 'cu-qua',
                'description' => 'Các loại củ quả được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1518843875459-f738682238a6?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
                'parent_id' => 2, // ID của danh mục "Rau củ quả"
            ],
            [
                'name' => 'Trái cây nhiệt đới',
                'slug' => 'trai-cay-nhiet-doi',
                'description' => 'Các loại trái cây nhiệt đới được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
                'parent_id' => 3, // ID của danh mục "Trái cây"
            ],
            [
                'name' => 'Trái cây ôn đới',
                'slug' => 'trai-cay-on-doi',
                'description' => 'Các loại trái cây ôn đới được trồng theo phương pháp hữu cơ.',
                'image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=800&auto=format&fit=crop&q=60',
                'is_active' => true,
                'parent_id' => 3, // ID của danh mục "Trái cây"
            ],
        ];

        foreach ($subCategories as $category) {
            Category::create($category);
        }
    }
} 