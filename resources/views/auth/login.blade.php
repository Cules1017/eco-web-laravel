@extends('layouts.auth')

@section('title', __('messages.login'))

@push('styles')
<style>
    /* Bầu không khí High-end */
    .high-end-box {
        background: #ffffff;
        padding: 3.5rem 3rem;
        border-radius: 20px;
        box-shadow: 0 24px 48px rgba(0, 0, 0, 0.06), 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin: 2rem 0;
    }

    .high-end-box .auth-header {
        margin-bottom: 3rem;
    }

    .high-end-box .auth-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
        color: #000;
    }

    .high-end-box .auth-header p {
        font-size: 1rem;
        color: #666;
    }

    /* Viền input ẩn, chỉ hiện viền dưới */
    .high-end-box .custom-input {
        background-color: transparent !important;
        border: none !important;
        border-bottom: 1.5px solid #e0e0e0 !important;
        border-radius: 0 !important;
        padding: 12px 0 !important;
        font-size: 1.05rem;
        font-weight: 500;
        color: #000;
        transition: all 0.3s ease;
        box-shadow: none !important;
    }

    .high-end-box .custom-input::placeholder {
        color: #adb5bd;
        font-weight: 400;
    }

    .high-end-box .custom-input:focus {
        border-bottom: 1.5px solid #000 !important;
        background-color: transparent !important;
        box-shadow: none !important;
    }

    .high-end-box .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #555;
        margin-bottom: 0.2rem;
    }

    /* Nút CTA bóng đổ, bo tròn */
    .high-end-box .btn-cta-black {
        border-radius: 999px !important;
        padding: 16px;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 1.5rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        border: none;
        background: #000;
        color: #fff;
    }

    .high-end-box .btn-cta-black:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        background: #111;
    }
</style>
@endpush

@section('content')
<div class="high-end-box">
    <div class="auth-header">
        <h1>Đăng nhập</h1>
        <p>Chào mừng bạn trở lại! Vui lòng nhập thông tin.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="border-radius: 12px; font-weight: 500; border: none; background: #fff5f5; color: #e53e3e;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px; font-weight: 500; border: none; background: #f0fff4; color: #38a169;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="login" class="form-label">{{ __('messages.email_or_username') }}</label>
            <input id="login" type="text" class="form-control custom-input @error('login') is-invalid @enderror"
                   name="login" value="{{ old('login') }}" required autofocus
                   placeholder="nguyenvana@example.com">
            @error('login')
                <span class="invalid-feedback" role="alert" style="font-size: 0.85rem; margin-top: 8px;"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">{{ __('messages.password') }}</label>
                <a href="#" class="text-decoration-none" style="font-size: 0.85rem; color: #666; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#000'" onmouseout="this.style.color='#666'">Quên mật khẩu?</a>
            </div>
            <input id="password" type="password" class="form-control custom-input @error('password') is-invalid @enderror"
                   name="password" required placeholder="••••••••">
            @error('password')
                <span class="invalid-feedback" role="alert" style="font-size: 0.85rem; margin-top: 8px;"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        
        <button type="submit" class="btn-cta-black">
            {{ __('messages.login') }}
        </button>
    </form>

    <div class="text-center mt-5">
        <p style="color: #666; font-size: 0.95rem;">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="text-decoration-none" style="color: #000; font-weight: 700; border-bottom: 2px solid #000; padding-bottom: 2px; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                {{ __('messages.create_account') }}
            </a>
        </p>
    </div>
</div>
@endsection
