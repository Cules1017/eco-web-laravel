<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
        ]);
        $address = Address::create([
            'user_id' => $request->user()->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'is_default' => false,
        ]);
        return response()->json(['success' => true, 'message' => 'Đã thêm địa chỉ thành công!', 'address' => $address]);
    }
} 