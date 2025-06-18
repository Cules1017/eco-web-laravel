<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::ordered()->get();
        return view('admin.home-sections.index', compact('sections'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.home-sections.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|integer|in:1,2,3',
            'list_categories' => 'nullable|string',
            'num' => 'required|integer|min:1|max:20',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (HomeSection::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        HomeSection::create($validated);

        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Section đã được tạo thành công.');
    }

    public function edit(HomeSection $homeSection)
    {
        $categories = Category::all();
        return view('admin.home-sections.edit', compact('homeSection', 'categories'));
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|integer|in:1,2,3',
            'list_categories' => 'nullable|string',
            'num' => 'required|integer|min:1|max:20',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (HomeSection::where('slug', $slug)->where('id', '!=', $homeSection->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        $homeSection->update($validated);

        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Section đã được cập nhật thành công.');
    }

    public function destroy(HomeSection $homeSection)
    {
        $homeSection->delete();
        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Section đã được xóa thành công.');
    }

    public function updateOrder(Request $request)
    {
        $sections = $request->input('sections');
        foreach ($sections as $section) {
            HomeSection::where('id', $section['id'])->update(['order' => $section['order']]);
        }
        return response()->json(['message' => 'Order updated successfully']);
    }

    public function toggleActive(HomeSection $homeSection)
    {
        $homeSection->update(['is_active' => !$homeSection->is_active]);
        return redirect()->route('admin.home-sections.index')
            ->with('success', 'Section status updated successfully.');
    }
} 