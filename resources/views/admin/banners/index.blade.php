@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Quản lý Banner</h1>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-success mb-3">Thêm Banner</a>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <table class="table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Link</th>
                <th>Thứ tự</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
            <tr>
                <td>{{ $banner->title }}</td>
                <td><img src="{{ Str::startsWith($banner->image, 'http') ? $banner->image : asset('storage/'.$banner->image) }}" width="120"></td>
                <td>{{ $banner->link }}</td>
                <td>{{ $banner->order }}</td>
                <td>{{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}</td>
                <td>
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm">Sửa</a>
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa banner này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 