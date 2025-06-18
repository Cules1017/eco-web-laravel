@extends('layouts.eshopper')
@section('title', 'Thông tin cá nhân')
@section('content')
<div class="container py-80" style="padding-top: 100px;">
    <h2>Thông tin cá nhân</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <tr><th>Họ:</th><td>{{ $user->first_name }}</td></tr>
        <tr><th>Tên:</th><td>{{ $user->last_name }}</td></tr>
        <tr><th>Email:</th><td>{{ $user->email }}</td></tr>
        <tr><th>Số điện thoại:</th><td>{{ $user->phone }}</td></tr>
    </table>
    <a href="{{ route('client.user.edit') }}" class="btn btn-primary">Sửa thông tin</a>
    <a href="{{ route('client.user.change_password') }}" class="btn btn-warning ms-2">Đổi mật khẩu</a>
</div>
@endsection 