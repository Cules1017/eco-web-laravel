@extends('layouts.eshopper')
@section('title', 'Đổi mật khẩu')
@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">Đổi mật khẩu</h1>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('client.user.change_password.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1"></i> Mật khẩu hiện tại
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-key me-1"></i> Mật khẩu mới
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                            <small class="text-muted">Tối thiểu 6 ký tự.</small>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-check me-1"></i> Xác nhận mật khẩu mới
                            </label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex gap-2 mt-4 flex-wrap">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i> Đổi mật khẩu
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
