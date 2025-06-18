<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('client.user.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('client.user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($request->only('first_name', 'last_name', 'phone'));
        return redirect()->route('client.user.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function showChangePassword()
    {
        return view('client.user.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('client.user.profile')->with('success', 'Đổi mật khẩu thành công!');
    }
} 