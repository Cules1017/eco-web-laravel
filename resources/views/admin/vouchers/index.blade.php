@extends('layouts.admin')
@section('title', 'Quản lý Mã giảm giá')
@section('header', 'Danh sách Mã giảm giá')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm mã mới
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mã code</th>
                    <th>Mức giảm</th>
                    <th>Điều kiện</th>
                    <th>Thời hạn</th>
                    <th>Lượt dùng</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->id }}</td>
                    <td><span class="badge badge-info">{{ $voucher->code }}</span></td>
                    <td>
                        @if($voucher->discount_type === 'percent')
                            {{ $voucher->discount_value }}%
                        @else
                            {{ number_format($voucher->discount_value, 0, ',', '.') }}đ
                        @endif
                    </td>
                    <td>
                        Tối thiểu: {{ number_format($voucher->min_order_amount, 0, ',', '.') }}đ
                    </td>
                    <td>
                        @if($voucher->start_date)
                            Từ: {{ $voucher->start_date->format('d/m/Y H:i') }}<br>
                        @endif
                        @if($voucher->end_date)
                            Đến: {{ $voucher->end_date->format('d/m/Y H:i') }}
                        @endif
                    </td>
                    <td>{{ $voucher->used_count }} / {{ $voucher->usage_limit ?: '∞' }}</td>
                    <td>
                        @if($voucher->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Tạm ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa mã giảm giá này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có mã giảm giá nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-3">
            {{ $vouchers->links() }}
        </div>
    </div>
</div>
@endsection
