@extends('layouts.eshopper')
@section('title', 'Thông tin cá nhân')
@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">Thông tin cá nhân</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="profile-avatar me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 mb-1">{{ trim($user->first_name . ' ' . $user->last_name) ?: ($user->username ?? $user->email) }}</div>
                            <div class="text-muted small"><i class="fas fa-envelope me-1"></i>{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="profile-grid">
                        <div class="profile-item">
                            <div class="lbl"><i class="fas fa-user me-1"></i> Họ</div>
                            <div class="val">{{ $user->first_name ?: '—' }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="lbl"><i class="fas fa-user me-1"></i> Tên</div>
                            <div class="val">{{ $user->last_name ?: '—' }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="lbl"><i class="fas fa-envelope me-1"></i> Email</div>
                            <div class="val">{{ $user->email }}</div>
                        </div>
                        <div class="profile-item">
                            <div class="lbl"><i class="fas fa-phone me-1"></i> Số điện thoại</div>
                            <div class="val">{{ $user->phone ?: '—' }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 flex-wrap">
                        <a href="{{ route('client.user.edit') }}" class="btn btn-primary">
                            <i class="fas fa-user-pen me-1"></i> Sửa thông tin
                        </a>
                        <a href="{{ route('client.user.change_password') }}" class="btn btn-outline-primary">
                            <i class="fas fa-key me-1"></i> Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-compass me-1"></i> Lối tắt</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action px-0">
                            <i class="fas fa-box me-2 text-primary"></i> Đơn hàng của tôi
                        </a>
                        <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action px-0">
                            <i class="fas fa-location-dot me-2 text-primary"></i> Địa chỉ giao hàng
                        </a>
                        <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action px-0">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i> Giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.profile-avatar {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: var(--vs-gradient);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px;
    box-shadow: 0 8px 18px rgba(99,102,241,.3);
}
.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
}
.profile-item {
    padding: 14px 16px;
    border: 1px solid var(--vs-border);
    border-radius: 12px;
    background: #fafbff;
}
.profile-item .lbl { color: var(--vs-text-muted); font-size: 0.85rem; margin-bottom: 4px; }
.profile-item .val { font-weight: 600; color: var(--vs-text); }
</style>
@endpush
@endsection
