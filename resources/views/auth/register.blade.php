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

@section('title', __('messages.register'))

@section('content')
<div class="vs-auth-wrapper">
    <div class="vs-auth-card wide">
        <div class="text-center mb-4">
            @if($logo)
                <img src="{{ $logo }}" alt="{{ $siteName }}" style="max-height: 60px;">
            @else
                <div class="vs-auth-brand"><i class="fas fa-user-plus"></i></div>
            @endif
            <h3 class="fw-bold mt-3 mb-1">{{ __('messages.register') }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Tạo tài khoản để mua sắm dễ dàng hơn</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="auth-form-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="first_name" class="form-label">{{ __('messages.first_name') }}</label>
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autofocus>
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="last_name" class="form-label">{{ __('messages.last_name') }}</label>
                            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">{{ __('messages.username') }}</label>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('messages.email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">{{ __('messages.password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('messages.address') }}</label>
                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="province" class="form-label">{{ __('messages.province') }}</label>
                            <select id="province" name="province" class="form-select @error('province') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_province') }}</option>
                            </select>
                            @error('province')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="district" class="form-label">{{ __('messages.district') }}</label>
                            <select id="district" name="district" class="form-select @error('district') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_district') }}</option>
                            </select>
                            @error('district')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="ward" class="form-label">{{ __('messages.ward') }}</label>
                            <select id="ward" name="ward" class="form-select @error('ward') is-invalid @enderror" required>
                                <option value="">{{ __('messages.select_ward') }}</option>
                            </select>
                            @error('ward')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                        <i class="fas fa-user-plus me-1"></i> {{ __('messages.register') }}
                    </button>
                </form>

                <div class="text-center mt-3 text-muted" style="font-size: 0.9rem;">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: var(--vs-primary);">
                        {{ __('messages.login') }}
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
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load provinces
    $.get('https://provinces.open-api.vn/api/p/', function(data) {
        $('#province').empty().append('<option value="">{{ __("messages.select_province") }}</option>');
        data.forEach(function(item) {
            $('#province').append('<option value="'+item.code+'">'+item.name+'</option>');
        });
    });

    // On province change, load districts
    $('#province').on('change', function() {
        var provinceCode = $(this).val();
        $('#district').empty().append('<option value="">{{ __("messages.select_district") }}</option>');
        $('#ward').empty().append('<option value="">{{ __("messages.select_ward") }}</option>');
        if(provinceCode) {
            $.get('https://provinces.open-api.vn/api/p/' + provinceCode + '?depth=2', function(data) {
                data.districts.forEach(function(item) {
                    $('#district').append('<option value="'+item.code+'">'+item.name+'</option>');
                });
            });
        }
    });

    // On district change, load wards
    $('#district').on('change', function() {
        var districtCode = $(this).val();
        $('#ward').empty().append('<option value="">{{ __("messages.select_ward") }}</option>');
        if(districtCode) {
            $.get('https://provinces.open-api.vn/api/d/' + districtCode + '?depth=2', function(data) {
                data.wards.forEach(function(item) {
                    $('#ward').append('<option value="'+item.code+'">'+item.name+'</option>');
                });
            });
        }
    });

    // Trước khi submit form, thêm tên tỉnh/quận/phường vào input hidden
    $('form').on('submit', function(e) {
        // Xóa input cũ nếu có
        $(this).find('input[name="province_name"],input[name="district_name"],input[name="ward_name"]').remove();
        // Lấy text option đã chọn
        var provinceName = $('#province option:selected').text();
        var districtName = $('#district option:selected').text();
        var wardName = $('#ward option:selected').text();
        // Thêm input hidden
        $(this).append('<input type="hidden" name="province_name" value="'+provinceName+'">');
        $(this).append('<input type="hidden" name="district_name" value="'+districtName+'">');
        $(this).append('<input type="hidden" name="ward_name" value="'+wardName+'">');
    });
});
</script>
@endpush 