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
            'bank_bin' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:150',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
        ]);

        Setting::updateOrCreate(['key' => 'site_name'], [
            'value' => $request->site_name,
            'type' => 'string',
            'description' => 'Tên website'
        ]);

        Setting::updateOrCreate(['key' => 'site_description'], [
            'value' => $request->site_description,
            'type' => 'text',
            'description' => 'Mô tả website'
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $path = $logo->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], [
                'value' => $path,
                'type' => 'image',
                'description' => 'Logo website'
            ]);
        }

        // Thông tin ngân hàng nhận chuyển khoản (VietQR / NAPAS247)
        foreach ([
            'bank_bin'          => 'Mã BIN ngân hàng (VD: 970436 = Vietcombank)',
            'bank_name'         => 'Tên ngân hàng',
            'bank_account_no'   => 'Số tài khoản',
            'bank_account_name' => 'Tên chủ tài khoản',
        ] as $key => $desc) {
            Setting::updateOrCreate(['key' => $key], [
                'value'       => $request->input($key),
                'type'        => 'string',
                'description' => $desc,
            ]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Cập nhật cấu hình thành công!');
    }
} 