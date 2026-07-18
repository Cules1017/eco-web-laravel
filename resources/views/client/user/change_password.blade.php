@extends('layouts.eshopper')
@section('title', 'Đổi mật khẩu')
@section('content')
<div class="container py-5">
    <h1 class="mb-5 fw-normal text-uppercase fs-3">Đổi mật khẩu</h1>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-none border-0 bg-transparent">
                <div class="card-body p-0">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-0">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success rounded-0">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('client.user.change_password.update') }}">
                        @csrf
                        <div class="mb-5">
                            <label for="current_password" class="form-label text-uppercase text-muted small fw-bold mb-2">
                                Mật khẩu hiện tại
                            </label>
                            <input type="password" class="form-control form-control-lg border rounded-3 px-4 py-3" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-5">
                            <label for="password" class="form-label text-uppercase text-muted small fw-bold mb-2">
                                Mật khẩu mới
                            </label>
                            <input type="password" class="form-control form-control-lg border rounded-3 px-4 py-3" id="password" name="password" required minlength="6">
                            <small class="text-muted mt-2 d-block">Tối thiểu 6 ký tự.</small>
                        </div>
                        <div class="mb-5">
                            <label for="password_confirmation" class="form-label text-uppercase text-muted small fw-bold mb-2">
                                Xác nhận mật khẩu mới
                            </label>
                            <input type="password" class="form-control form-control-lg border rounded-3 px-4 py-3" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex gap-4 mt-5 pt-4 flex-wrap border-top">
                            <button type="submit" class="btn btn-dark text-uppercase rounded-0 px-5 py-3">
                                Đổi mật khẩu
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
