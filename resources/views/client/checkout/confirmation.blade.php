@extends('layouts.app')

@section('title', __('messages.order_confirmation'))

@section('content')
<div class="container" style="padding-top: 100px;">
    <!-- Success Message -->
    <div class="text-center mb-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h1 class="mb-3">{{ __('messages.thank_you') }}</h1>
        <p class="lead">{{ __('messages.order_success') }}</p>
        <p class="text-muted">{{ __('messages.order_number') }}: #{{ $order->id }}</p>
    </div>

    <!-- Order Details -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.order_details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">{{ __('messages.shipping_information') }}</h6>
                            <p class="mb-1"><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                            <p class="mb-1">{{ $order->email }}</p>
                            <p class="mb-1">{{ $order->phone }}</p>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">{{ __('messages.order_information') }}</h6>
                            <p class="mb-1">
                                <strong>{{ __('messages.order_date') }}:</strong> 
                                {{ $order->created_at->format('F j, Y H:i') }}
                            </p>
                            <p class="mb-1">
                                <strong>{{ __('messages.order_status') }}:</strong>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'completed' ? 'success' : 'danger')) }}">
                                    {{ __('messages.status_' . $order->status) }}
                                </span>
                            </p>
                            <p class="mb-1">
                                <strong>{{ __('messages.payment_method') }}:</strong>
                                {{ __('messages.' . $order->payment_method) }}
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th class="text-end">{{ __('messages.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/50x50' }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px;">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>{{ __('messages.subtotal') }}:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>{{ __('messages.tax') }}:</strong></td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('products.index') }}" class="btn btn-primary me-2">
                    {{ __('messages.continue_shopping') }}
                </a>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">
                    {{ __('messages.view_order_details') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 