<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $adminCount = User::where('is_admin', true)->count();
        return view('admin.users.index', compact('users', 'adminCount'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        // Prevent deleting the last admin
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Không thể xóa admin cuối cùng trong hệ thống.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công.');
    }

    public function toggleAdmin(User $user)
    {
        // Không cho phép hạ vai trò admin cuối cùng
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Không thể hạ vai trò admin cuối cùng.');
        }
        $user->is_admin = !$user->is_admin;
        $user->save();
        return back()->with('success', 'Cập nhật vai trò thành công.');
    }
} 