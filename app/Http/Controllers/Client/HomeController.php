<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', 1)->orderByDesc('created_at')->take(8)->get();
        $featuredProducts = Product::where('is_active', 1)->where('is_featured', 1)->orderByDesc('created_at')->take(8)->get();
         // Danh mục luôn luôn hiển thị
         $categories = Category::where('is_active', true)
         ->whereNull('parent_id')
         ->with('children')
         ->get();

     // Lấy sản phẩm theo từng danh mục cha
     $categoriesWithProducts = Category::where('is_active', true)
         ->whereNull('parent_id')
         ->with(['products' => function($q) {
             $q->where('is_active', true)->take(8);
         }])
         ->get();

     // Các section khác được cấu hình
     $sections = HomeSection::where('is_active', true)
         ->orderBy('order')
         ->get();
        return view('client.home', compact('categories', 'products', 'featuredProducts', 'sections', 'categoriesWithProducts'));
    }
}
