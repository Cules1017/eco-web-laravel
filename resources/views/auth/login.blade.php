@php
    use App\Models\Setting;
    $logo = Setting::getValue('site_logo', 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg');
    if ($logo && !str_starts_with($logo, 'http')) {
        $logo = asset('storage/' . ltrim($logo, '/'));
    }
@endphp

@extends('layouts.eshopper')

@section('title', __('messages.login'))

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="w-100" style="max-width: 400px;">
        <div class="text-center mb-4">
            <img src="{{ $logo }}" alt="Logo" style="max-height: 60px;">
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-3">{{ __('messages.login') }}</h3>
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
                        <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autofocus>
                        @error('login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('messages.password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-warning w-100 mb-2">{{ __('messages.login') }}</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100">{{ __('messages.create_account') }}</a>
                </div>
                <div class="text-center mt-3">
                    <form action="{{ route('language.switch', ['locale' => app()->getLocale() === 'en' ? 'vi' : 'en']) }}" method="get" style="display:inline;">
                        <button type="submit" class="btn btn-link p-0 border-0 align-baseline">
                            <span class="me-1">🌐</span>
                            {{ app()->getLocale() === 'en' ? 'Tiếng Việt' : 'English' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} {{ config('app.name', 'Your App') }}
        </div>
    </div>
</div>
@endsection 