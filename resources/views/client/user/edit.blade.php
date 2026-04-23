@extends('layouts.eshopper')
@section('title', 'Sửa thông tin cá nhân')
@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">Sửa thông tin cá nhân</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('client.user.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-semibold">Họ</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-semibold">Tên</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="VD: 0987654321">
                            @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="d-flex gap-2 mt-4 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('client.user.profile') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Huỷ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
