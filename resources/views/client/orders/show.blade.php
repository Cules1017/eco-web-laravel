@extends('layouts.eshopper')

@section('title', __('messages.order_details') . ' #' . $order->id)

@section('content')
<style>
.status-badge-pending { background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.status-badge-processing { background: #cff4fc; color: #055160; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.status-badge-shipping { background: #cfe2ff; color: #084298; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.status-badge-completed { background: #d1e7dd; color: #0f5132; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.status-badge-cancelled { background: #f8d7da; color: #842029; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }

.payment-badge-pending { background: #e2e3e5; color: #41464b; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.payment-badge-paid { background: #d1e7dd; color: #0f5132; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.payment-badge-failed { background: #f8d7da; color: #842029; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
.payment-badge-refunded { background: #cff4fc; color: #055160; padding: 6px 12px; border-radius: 50px; font-weight: 500; display: inline-block; }
</style>
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-5 pb-4 border-bottom">
        <div>
            <h1 class="mb-1 fw-normal text-uppercase fs-3">{{ __('messages.order_details') }} #{{ $order->id }}</h1>
            <div class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <a href="{{ route('orders.index') }}" class="text-dark text-decoration-none text-uppercase fw-bold pb-1 border-bottom border-dark small">
            {{ __('messages.back_to_orders') }}
        </a>
    </div>

    <div class="row g-5">
        <div class="col-md-8">
            <div class="mb-5">
                <h4 class="text-uppercase text-muted small fw-bold mb-4">Chi tiết đơn hàng</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-bottom mb-0">
                        <thead class="bg-transparent">
                            <tr>
                                <th class="border-top-0 border-bottom-2 text-uppercase text-muted small fw-bold py-3">{{ __('messages.product') }}</th>
                                <th class="border-top-0 border-bottom-2 text-uppercase text-muted small fw-bold py-3 text-center">{{ __('messages.quantity') }}</th>
                                <th class="border-top-0 border-bottom-2 text-uppercase text-muted small fw-bold py-3 text-end">{{ __('messages.price') }}</th>
                                <th class="border-top-0 border-bottom-2 text-uppercase text-muted small fw-bold py-3 text-end">{{ __('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-4 border-bottom border-light">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($item->product->image)
                                                <img src="{{ Str::startsWith($item->product->image, ['http://', 'https://']) ? $item->product->image : asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover;" class="bg-light">
                                            @else
                                                <div style="width: 60px; height: 60px;" class="bg-light d-flex align-items-center justify-content-center text-muted small">No IMG</div>
                                            @endif
                                            <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none fs-5">
                                                {{ $item->product->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-4 border-bottom border-light text-center fs-5">{{ $item->quantity }}</td>
                                    <td class="py-4 border-bottom border-light text-end text-muted">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="py-4 border-bottom border-light text-end fs-5">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end py-4 text-uppercase text-muted small fw-bold">{{ __('messages.total') }}</td>
                                <td class="text-end py-4 fs-3">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-light p-5 mb-5">
                <h4 class="text-uppercase text-muted small fw-bold mb-4">Thông tin trạng thái</h4>
                
                <div class="mb-4">
                    <div class="text-uppercase text-muted small mb-2">{{ __('messages.status') }}</div>
                    <span class="fs-6 {{ $order->status === 'pending' ? 'status-badge-pending' :
                        ($order->status === 'processing' ? 'status-badge-processing' :
                        ($order->status === 'shipping' ? 'status-badge-shipping' :
                        ($order->status === 'completed' ? 'status-badge-completed' : 'status-badge-cancelled'))) }}">
                        {{ __('messages.status_' . $order->status) }}
                    </span>
                </div>
                
                <div>
                    <div class="text-uppercase text-muted small mb-2">{{ __('messages.payment_status') }}</div>
                    <span class="fs-6 {{ $order->payment_status === 'pending' ? 'payment-badge-pending' :
                        ($order->payment_status === 'paid' ? 'payment-badge-paid' :
                        ($order->payment_status === 'failed' ? 'payment-badge-failed' : 'payment-badge-refunded')) }}">
                        {{ __('messages.payment_status_' . $order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="mb-5">
                <h4 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">{{ __('messages.shipping_address') }}</h4>
                @if($order->shippingAddress)
                    <div class="fs-5 mb-1">{{ $order->shippingAddress->full_name }}</div>
                    <div class="text-muted mb-3">{{ $order->shippingAddress->phone }}</div>
                    <div class="fs-5 lh-base">
                        {{ $order->shippingAddress->address }}<br>
                        {{ $order->shippingAddress->ward_name }},
                        {{ $order->shippingAddress->district_name }},
                        {{ $order->shippingAddress->province_name }}
                    </div>
                @else
                    <div class="text-muted">{{ __('messages.no_shipping_address') }}</div>
                @endif
            </div>

            <div class="mb-5">
                <h4 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">{{ __('messages.payment_method') }}</h4>
                <div class="fs-5">{{ __('messages.payment_' . $order->payment_method) }}</div>
            </div>

            @if($order->notes)
                <div>
                    <h4 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">Ghi chú</h4>
                    <div class="text-muted lh-base">{{ $order->notes }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 