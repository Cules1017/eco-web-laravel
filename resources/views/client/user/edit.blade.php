@extends('layouts.eshopper')
@section('title', 'Sửa thông tin cá nhân')
@section('content')
<div class="container py-80" style="padding-top: 100px;">
    <h2>Sửa thông tin cá nhân</h2>
    <form method="POST" action="{{ route('client.user.update') }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="first_name" class="form-label">Họ</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>
        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="{{ route('client.user.profile') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
</div>
@endsection 