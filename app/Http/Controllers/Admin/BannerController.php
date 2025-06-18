<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_file' => 'nullable|image',
            'image_url' => 'nullable|string',
            'link' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if ($request->input('upload_type') === 'file' && $request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('banners', 'public');
        } elseif ($request->input('upload_type') === 'url' && $request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        } else {
            return back()->withInput()->withErrors(['image_file' => 'Vui lòng chọn ảnh hoặc nhập link ảnh.']);
        }

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'link' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công!');
    }
} 