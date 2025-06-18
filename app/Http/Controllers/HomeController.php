<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\HomeSection;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Banner luôn luôn hiển thị
        $banners = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Danh mục luôn luôn hiển thị
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        // Các section khác được cấu hình
        $sections = HomeSection::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('home', compact('banners', 'categories', 'sections'));
    }
} 