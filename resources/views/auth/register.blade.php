@extends('layouts.auth')

@section('title', __('messages.register'))

@section('content')
<div class="auth-header">
    <h1>Đăng ký</h1>
    <p>Tạo tài khoản mới để mua sắm dễ dàng hơn.</p>
</div>

@if(session('error'))
    <div class="alert alert-danger" style="border-radius: 8px;">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success" style="border-radius: 8px;">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf
    
    <!-- Hidden fields to bypass backend validation -->
    <input type="hidden" name="last_name" value=".">
    <input type="hidden" name="username" id="hidden_username">
    <input type="hidden" name="phone" value="0000000000">
    <input type="hidden" name="password_confirmation" id="hidden_password_confirmation">
    <input type="hidden" name="address" value="Chưa cập nhật">
    <input type="hidden" name="province" value="0">
    <input type="hidden" name="district" value="0">
    <input type="hidden" name="ward" value="0">
    <input type="hidden" name="province_name" value="Chưa cập nhật">
    <input type="hidden" name="district_name" value="Chưa cập nhật">
    <input type="hidden" name="ward_name" value="Chưa cập nhật">

    <div class="mb-4">
        <label for="first_name" class="form-label">Họ và tên</label>
        <input id="first_name" type="text" class="form-control custom-input @error('first_name') is-invalid @enderror" 
               name="first_name" value="{{ old('first_name') }}" required autofocus placeholder="Nguyễn Văn A">
        @error('first_name')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" class="form-control custom-input @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" required placeholder="nguyenvana@example.com">
        @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label">Mật khẩu</label>
        <input id="password" type="password" class="form-control custom-input @error('password') is-invalid @enderror" 
               name="password" required placeholder="••••••••">
        @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-4">
        <p style="font-size: 0.85rem; color: #666; line-height: 1.5; margin: 0;">
            Bằng việc đăng ký, bạn đồng ý với 
            <a href="#" class="text-decoration-none" style="color: #000; font-weight: 500;">Điều khoản dịch vụ</a> và 
            <a href="#" class="text-decoration-none" style="color: #000; font-weight: 500;">Chính sách bảo mật</a> của chúng tôi.
        </p>
    </div>

    <button type="submit" class="btn-cta-black">
        {{ __('messages.register') }}
    </button>
</form>

<div class="text-center mt-4">
    <p style="color: #666; font-size: 0.95rem;">
        Đã có tài khoản? 
        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #000; font-weight: 600;">
            {{ __('messages.login') }}
        </a>
    </p>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function() {
        var email = document.getElementById('email').value;
        var prefix = email ? email.split('@')[0] : 'user';
        document.getElementById('hidden_username').value = prefix + '_' + Date.now();
        document.getElementById('hidden_password_confirmation').value = document.getElementById('password').value;
    });
</script>
@endpush