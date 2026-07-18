<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = database_path('data');
        if (!is_dir($dataPath)) {
            $this->command->warn("No data directory found at {$dataPath}");
            return;
        }

        $files = glob($dataPath . '/*.json');
        
        foreach ($files as $file) {
            $json = file_get_contents($file);
            $items = json_decode($json, true);

            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                // Ensure the category exists
                $categoryName = $item['category'] ?? 'Uncategorized';
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    [
                        'slug' => Str::slug($categoryName),
                        'description' => 'Beautiful eco-friendly products for ' . $categoryName,
                        'is_active' => true,
                    ]
                );

                // Create the product
                $product = Product::updateOrCreate(
                    ['name' => $item['name']],
                    [
                        'slug' => Str::slug($item['name']),
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'stock' => $item['stock'] ?? rand(10, 100),
                        'image' => $item['image'],
                        'category_id' => $category->id,
                        'is_active' => true,
                        'is_featured' => $item['is_featured'] ?? false,
                    ]
                );

                // Add 2-3 random extra images for gallery
                if ($product->images()->count() === 0) {
                    $numExtraImages = rand(2, 3);
                    $keywords = ['eco', 'nature', 'green', 'clean', 'organic'];
                    for ($i = 0; $i < $numExtraImages; $i++) {
                        $keyword = $keywords[array_rand($keywords)];
                        $randomUrl = "https://loremflickr.com/800/800/{$keyword}?lock=" . rand(1, 10000);
                        $product->images()->create([
                            'image_path' => $randomUrl
                        ]);
                    }
                }
            }
        }
        
        $this->command->info('Successfully seeded categories and products from JSON files!');
    }
}