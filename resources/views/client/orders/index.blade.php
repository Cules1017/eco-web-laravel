@extends('layouts.eshopper')

@section('title', __('messages.orders'))

@section('content')
<style>
    .nav-status a {
        transition: all 0.3s ease;
    }
    .nav-status li {
        margin-right: 15px;
        margin-bottom: 10px;
    }
    .nav-status a.bg-light:hover {
        background-color: #e9ecef !important;
    }
    tr td {
        padding: 20px 10px !important;
    }
    
    .status-badge-pending { background: #fff3cd; color: #856404; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .status-badge-processing { background: #cff4fc; color: #055160; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .status-badge-shipping { background: #cfe2ff; color: #084298; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .status-badge-completed { background: #d1e7dd; color: #0f5132; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .status-badge-cancelled { background: #f8d7da; color: #842029; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }

    .payment-badge-pending { background: #e2e3e5; color: #41464b; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .payment-badge-paid { background: #d1e7dd; color: #0f5132; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .payment-badge-failed { background: #f8d7da; color: #842029; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
    .payment-badge-refunded { background: #cff4fc; color: #055160; padding: 6px 16px; border-radius: 50px; font-weight: 500; display: inline-block; }
</style>
<div class="container py-5">
    <h1 class="mb-5 fw-normal text-uppercase fs-3">{{ __('messages.orders') }}</h1>

    <ul class="d-flex flex-wrap list-unstyled mb-5 nav-status">
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == '' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index') }}">{{ __('messages.all') }}</a>
        </li>
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == 'pending' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index', ['status' => 'pending']) }}">{{ __('messages.status_pending') }}</a>
        </li>
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == 'processing' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index', ['status' => 'processing']) }}">{{ __('messages.status_processing') }}</a>
        </li>
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == 'shipping' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index', ['status' => 'shipping']) }}">{{ __('messages.status_shipping') }}</a>
        </li>
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == 'completed' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index', ['status' => 'completed']) }}">{{ __('messages.status_completed') }}</a>
        </li>
        <li>
            <a class="text-uppercase fw-bold px-4 py-2 rounded-pill text-decoration-none {{ request('status') == 'cancelled' ? 'bg-dark text-white' : 'bg-light text-dark' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}">{{ __('messages.status_cancelled') }}</a>
        </li>
    </ul>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <h4 class="mb-3 fw-normal">Không tìm thấy đơn hàng nào.</h4>
            <p class="text-muted mb-4">Bạn chưa có đơn hàng nào trong trạng thái này.</p>
            <a href="{{ route('products.index') }}" class="btn btn-dark text-uppercase rounded-0 px-5 py-3">
                {{ __('messages.browse_products') }}
            </a>
        </div>
    @else
        <div class="table-responsive mb-5">
            <table class="table table-hover mb-0 align-middle border-bottom">
                <thead class="bg-transparent">
                    <tr>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4">{{ __('messages.order_number') }}</th>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4">{{ __('messages.date') }}</th>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4 text-end">{{ __('messages.total') }}</th>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4">{{ __('messages.status') }}</th>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4">{{ __('messages.payment_status') }}</th>
                        <th class="border-top-0 border-bottom text-uppercase text-muted small fw-bold py-4 text-end">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="fs-5 py-4 border-bottom border-light">#{{ $order->order_number }}</td>
                            <td class="py-4 border-bottom border-light text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-4 border-bottom border-light text-end fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                            <td class="py-4 border-bottom border-light">
                                <span class="fs-6 {{ $order->status === 'pending' ? 'status-badge-pending' :
                                    ($order->status === 'processing' ? 'status-badge-processing' :
                                    ($order->status === 'shipping' ? 'status-badge-shipping' :
                                    ($order->status === 'completed' ? 'status-badge-completed' : 'status-badge-cancelled'))) }}">
                                    {{ __('messages.status_' . $order->status) }}
                                </span>
                            </td>
                            <td class="py-4 border-bottom border-light">
                                <span class="fs-6 {{ $order->payment_status === 'pending' ? 'payment-badge-pending' :
                                    ($order->payment_status === 'paid' ? 'payment-badge-paid' :
                                    ($order->payment_status === 'failed' ? 'payment-badge-failed' : 'payment-badge-refunded')) }}">
                                    {{ __('messages.payment_status_' . $order->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 border-bottom border-light text-end">
                                <a href="{{ route('orders.show', $order) }}" class="text-dark text-decoration-none text-uppercase fw-bold small pb-1 border-bottom border-dark">
                                    {{ __('messages.view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
