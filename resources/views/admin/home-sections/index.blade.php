@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý Section trang chủ</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.home-sections.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Tiêu đề</th>
                                <th>Loại</th>
                                <th>Số lượng</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $section)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->title }}</td>
                                <td>
                                    @switch($section->type)
                                        @case(1)
                                            Sản phẩm nổi bật
                                            @break
                                        @case(2)
                                            Sản phẩm mới
                                            @break
                                        @case(3)
                                            Sản phẩm theo danh mục
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $section->num }}</td>
                                <td>{{ $section->order }}</td>
                                <td>
                                    <span class="badge badge-{{ $section->is_active ? 'success' : 'danger' }}">
                                        {{ $section->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.home-sections.edit', $section) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.home-sections.destroy', $section) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 