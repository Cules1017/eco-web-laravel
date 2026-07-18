@extends('layouts.eshopper')
@section('title', 'Sửa thông tin cá nhân')
@section('content')
<style>
    .profile-form .form-control {
        transition: all 0.2s ease-in-out;
        padding: 12px !important;
        border-radius: 8px !important;
    }
    .profile-form .form-control:not(.is-invalid):focus {
        border-color: #333 !important;
        box-shadow: 0 0 0 0.2rem rgba(51, 51, 51, 0.25) !important;
    }
    .profile-form .btn-save {
        transition: all 0.3s ease-in-out;
    }
    .profile-form .btn-save:hover {
        background-color: #495057 !important;
        border-color: #495057 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .profile-form .btn-save:active {
        transform: scale(0.95) !important;
        box-shadow: none !important;
    }
</style>
<div class="container py-5">
    <h1 class="mb-5 fw-normal text-uppercase fs-3">Sửa thông tin cá nhân</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-none border-0 bg-transparent">
                <div class="card-body p-0">
                    <form method="POST" action="{{ route('client.user.update') }}" class="profile-form">
                        @csrf
                        @method('PUT')

                        <div class="row g-5 mb-5">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label text-uppercase text-muted small fw-bold mb-2 border-0">Họ</label>
                                <input type="text" class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label text-uppercase text-muted small fw-bold mb-2 border-0">Tên</label>
                                <input type="text" class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="phone" class="form-label text-uppercase text-muted small fw-bold mb-2 border-0">Số điện thoại</label>
                            <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                   id="phone" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="VD: 0987654321">
                            @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="d-flex gap-4 mt-5 pt-4 flex-wrap border-top">
                            <button type="submit" class="btn btn-dark btn-save text-uppercase rounded-0 px-5 py-3">
                                Lưu thay đổi
                            </button>
                            <a href="{{ route('client.user.profile') }}" class="btn btn-outline-dark text-uppercase rounded-0 px-5 py-3 border-0 border-bottom text-muted">
                                Huỷ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
