@extends('layouts.admin')

@section('title', __('messages.order_management'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.order_management') }}</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <form method="GET" class="row g-2 mb-3 align-items-end">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Từ ngày</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Đến ngày</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">Khách hàng</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                    </form>
                    <!-- End Filter Form -->

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.order_id') }}</th>
                                    <th>{{ __('messages.customer') }}</th>
                                    <th>{{ __('messages.total') }}</th>
                                    <th>{{ __('messages.payment_method') }}</th>
                                    <th>{{ __('messages.order_status') }}</th>
                                    <th>{{ __('messages.payment_status') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>
                                            {{ $order->user->first_name }} {{ $order->user->last_name }}<br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </td>
                                        <td>{{ number_format($order->total_amount) }}đ</td>
                                        <td>{{ __('messages.payment_' . $order->payment_method) }}</td>
                                        <td>
                                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                                        {{ __('messages.status_pending') }}
                                                    </option>
                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                                        {{ __('messages.status_processing') }}
                                                    </option>
                                                    <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>
                                                        {{ __('messages.status_shipping') }}
                                                    </option>
                                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                                        {{ __('messages.status_completed') }}
                                                    </option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                                        {{ __('messages.status_cancelled') }}
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{
                                                $order->payment_status === 'pending' ? 'secondary' :
                                                ($order->payment_status === 'paid' ? 'success' :
                                                ($order->payment_status === 'failed' ? 'danger' : 'info'))
                                            }}">
                                                {{ __('messages.payment_status_' . $order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" 
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('messages.no_orders') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
        min-width: 120px;
    }
</style>
@endpush
@endsection 