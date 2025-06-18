<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSection;
use App\Models\Category;

class HomeSectionSeeder extends Seeder
{
    public function run()
    {
        // Xóa tất cả dữ liệu cũ
        HomeSection::truncate();

        $sections = [
            [
                'name' => 'Sản phẩm nổi bật',
                'slug' => 'featured-products',
                'title' => 'Sản phẩm nổi bật',
                'description' => 'Những sản phẩm được chọn lọc kỹ lưỡng',
                'type' => 1, // featured_products
                'num' => 8,
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'Sản phẩm mới',
                'slug' => 'new-products',
                'title' => 'Sản phẩm mới nhất',
                'description' => 'Những sản phẩm mới được thêm vào',
                'type' => 2, // new_products
                'num' => 8,
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'Sản phẩm theo danh mục',
                'slug' => 'category-products',
                'title' => 'Sản phẩm theo danh mục',
                'description' => 'Sản phẩm được chọn lọc theo danh mục',
                'type' => 3, // category_products
                'list_categories' => '1,2,3', // ID của các danh mục
                'num' => 8,
                'order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($sections as $section) {
            HomeSection::create($section);
        }
    }
} 