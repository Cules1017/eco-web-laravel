@extends('layouts.eshopper')

@section('title', __('messages.order_details') . ' #' . $order->id)

@section('content')
<div class="container py-5"  style="margin-top: 100px;">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.order_details') }} #{{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>{{ __('messages.order_number') }}:</strong></p>
                            <p>{{ $order->order_number }}</p>
                            <p class="mb-1"><strong>{{ __('messages.order_date') }}:</strong></p>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>{{ __('messages.status') }}:</strong></p>
                            <p>
                                <span class="badge bg-{{
                                    $order->status === 'pending' ? 'warning' :
                                    ($order->status === 'processing' ? 'info' :
                                    ($order->status === 'shipping' ? 'primary' :
                                    ($order->status === 'completed' ? 'success' : 'danger')))
                                }}">
                                    {{ __('messages.status_' . $order->status) }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>{{ __('messages.payment_status') }}:</strong></p>
                            <p>
                                <span class="badge bg-{{
                                    $order->payment_status === 'pending' ? 'secondary' :
                                    ($order->payment_status === 'paid' ? 'success' :
                                    ($order->payment_status === 'failed' ? 'danger' : 'info'))
                                }}">
                                    {{ __('messages.payment_status_' . $order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.price') }}</th>
                                    <th class="text-end">{{ __('messages.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                    <img src="{{ Str::startsWith($item->product->image, ['http://', 'https://']) ? $item->product->image : asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div style="padding-left: 4px;">
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-decoration-none">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td class="text-end">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.shipping_address') }}</h5>
                </div>
                <div class="card-body">
                    @if($order->shippingAddress)
                        <p class="mb-1"><strong>{{ $order->shippingAddress->full_name }}</strong></p>
                        <p class="mb-1">{{ $order->shippingAddress->phone }}</p>
                        <p class="mb-1">{{ $order->shippingAddress->address }}</p>
                        <p class="mb-1">
                            {{ $order->shippingAddress->ward_name }},
                            {{ $order->shippingAddress->district_name }},
                            {{ $order->shippingAddress->province_name }}
                        </p>
                    @else
                        <p class="text-muted">{{ __('messages.no_shipping_address') }}</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.payment_method') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ __('messages.payment_' . $order->payment_method) }}</p>
                </div>
            </div>

            @if($order->notes)
                <div class="alert alert-info">{{ $order->notes }}</div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_orders') }}
        </a>
    </div>
</div>
@endsection 