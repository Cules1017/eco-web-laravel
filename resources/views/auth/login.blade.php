@php
    use App\Models\Setting;
    $logoRaw = Setting::getValue('site_logo');
    $logo = null;
    if ($logoRaw) {
        $logo = str_starts_with($logoRaw, 'http') ? $logoRaw : asset('storage/' . ltrim($logoRaw, '/'));
    }
    $siteName = Setting::getValue('site_name', config('app.name', 'Venshop'));
@endphp

@extends('layouts.eshopper')

@section('title', __('messages.login'))

@section('content')
<div class="vs-auth-wrapper">
    <div class="vs-auth-card">
        <div class="text-center mb-4">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $siteName }}" style="max-height: 60px;">
            @else
                <div class="vs-auth-brand"><i class="fas fa-bag-shopping"></i></div>
            @endif
            <h3 class="fw-bold mt-3 mb-1">{{ __('messages.login') }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Chào mừng trở lại với {{ $siteName }}</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="login" class="form-label">{{ __('messages.email_or_username') }}</label>
                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror"
                       name="login" value="{{ old('login') }}" required autofocus
                       placeholder="nguyenvana@example.com">
                @error('login')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required placeholder="••••••••">
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                <i class="fas fa-right-to-bracket me-1"></i> {{ __('messages.login') }}
            </button>
        </form>

        <div class="text-center mt-3 text-muted" style="font-size: 0.9rem;">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color: var(--vs-primary);">
                {{ __('messages.create_account') }}
            </a>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('language.switch', ['locale' => app()->getLocale() === 'en' ? 'vi' : 'en']) }}"
               class="btn btn-link p-0 text-muted text-decoration-none" style="font-size: 0.85rem;">
                <i class="fas fa-globe me-1"></i>
                {{ app()->getLocale() === 'en' ? 'Tiếng Việt' : 'English' }}
            </a>
        </div>

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} {{ $siteName }}
        </div>
    </div>
</div>
@endsection
