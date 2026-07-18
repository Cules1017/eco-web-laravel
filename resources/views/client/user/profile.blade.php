@extends('layouts.eshopper')
@section('title', 'Thông tin cá nhân')
@section('content')
<style>
    .profile-info-block {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .profile-avatar-large {
        width: 120px;
        height: 120px;
        font-size: 3.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .profile-shortcut-link {
        transition: all 0.2s ease;
    }
    .profile-shortcut-link:hover {
        padding-left: 5px;
    }
</style>
<div class="container py-5">
    <h1 class="mb-5 fw-normal text-uppercase fs-3">Thông tin cá nhân</h1>

    @if(session('success'))
        <div class="alert alert-success rounded-0 mb-5">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="profile-info-block p-4 p-md-5">
                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start mb-4 pb-4 border-bottom text-center text-sm-start">
                    <div class="profile-avatar-large me-sm-4 mb-3 mb-sm-0 bg-light text-dark rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                        {{ strtoupper(substr(trim($user->first_name . ' ' . $user->last_name) ?: ($user->username ?? $user->email), 0, 1)) }}
                    </div>
                    <div class="pt-sm-2">
                        <div class="fs-3 fw-bold mb-1">{{ trim($user->first_name . ' ' . $user->last_name) ?: ($user->username ?? $user->email) }}</div>
                        <div class="text-muted fs-5">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-sm-6">
                        <div class="text-uppercase text-muted small fw-bold mb-2">Họ</div>
                        <div class="fs-5">{{ $user->first_name ?: '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-uppercase text-muted small fw-bold mb-2">Tên</div>
                        <div class="fs-5">{{ $user->last_name ?: '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-uppercase text-muted small fw-bold mb-2">Email</div>
                        <div class="fs-5">{{ $user->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-uppercase text-muted small fw-bold mb-2">Số điện thoại</div>
                        <div class="fs-5">{{ $user->phone ?: '—' }}</div>
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap border-top pt-4 justify-content-center justify-content-sm-start">
                    <a href="{{ route('client.user.edit') }}" class="btn btn-dark px-4 py-2 rounded-pill text-uppercase fw-bold">
                        Sửa thông tin
                    </a>
                    <a href="{{ route('client.user.change_password') }}" class="btn btn-light border px-4 py-2 rounded-pill text-uppercase fw-bold text-muted">
                        Đổi mật khẩu
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="profile-info-block p-4 p-md-5 h-100">
                <div class="text-uppercase text-muted small fw-bold mb-4">Lối tắt</div>
                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('orders.index') }}" class="profile-shortcut-link text-dark text-decoration-none fs-5 fw-medium pb-2 border-bottom">Đơn hàng của tôi</a>
                    <a href="{{ route('addresses.index') }}" class="profile-shortcut-link text-dark text-decoration-none fs-5 fw-medium pb-2 border-bottom">Địa chỉ giao hàng</a>
                    <a href="{{ route('cart.index') }}" class="profile-shortcut-link text-dark text-decoration-none fs-5 fw-medium pb-2 border-bottom">Giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
