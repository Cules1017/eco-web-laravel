@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết người dùng #{{ $user->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông tin cơ bản</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Họ và tên</th>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại</th>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Vai trò</th>
                                    <td>
                                        <span class="badge bg-{{ $user->is_admin ? 'danger' : 'info' }}">
                                            {{ $user->is_admin ? 'Admin' : 'User' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày đăng ký</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Địa chỉ</h4>
                            @if($user->addresses->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Họ tên</th>
                                                <th>Số điện thoại</th>
                                                <th>Địa chỉ</th>
                                                <th>Mặc định</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->addresses as $address)
                                                <tr>
                                                    <td>{{ $address->full_name }}</td>
                                                    <td>{{ $address->phone }}</td>
                                                    <td>
                                                        {{ $address->address }},
                                                        {{ $address->ward_name }},
                                                        {{ $address->district_name }},
                                                        {{ $address->province_name }}
                                                    </td>
                                                    <td>
                                                        @if($address->is_default)
                                                            <span class="badge bg-success">Mặc định</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Chưa có địa chỉ nào</p>
                            @endif

                            <h4 class="mt-4">Đơn hàng</h4>
                            @if($user->orders->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn</th>
                                                <th>Ngày đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->orders as $order)
                                                <tr>
                                                    <td>{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                                    <td>
                                                        <span class="badge bg-{{
                                                            $order->status === 'pending' ? 'warning' :
                                                            ($order->status === 'processing' ? 'info' :
                                                            ($order->status === 'shipping' ? 'primary' :
                                                            ($order->status === 'completed' ? 'success' : 'danger')))
                                                        }}">
                                                            {{ __('messages.status_' . $order->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Chưa có đơn hàng nào</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 