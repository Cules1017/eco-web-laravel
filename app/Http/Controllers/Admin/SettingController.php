<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Site name
        Setting::updateOrCreate(['key' => 'site_name'], [
            'value' => $request->site_name,
            'type' => 'string',
            'description' => 'Tên website'
        ]);

        // Site description
        Setting::updateOrCreate(['key' => 'site_description'], [
            'value' => $request->site_description,
            'type' => 'text',
            'description' => 'Mô tả website'
        ]);

        // Logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $path = $logo->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], [
                'value' => $path,
                'type' => 'image',
                'description' => 'Logo website'
            ]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Cập nhật cấu hình thành công!');
    }
} 