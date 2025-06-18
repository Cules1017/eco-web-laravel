@extends('layouts.admin')

@section('title', __('messages.order_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.order_details') }} #{{ $order->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_orders') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Order Information -->
                        <div class="col-md-6">
                            <h4>{{ __('messages.order_information') }}</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('messages.order_id') }}</th>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.order_date') }}</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.order_status') }}</th>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ __('messages.status_' . $order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.payment_method') }}</th>
                                    <td>{{ __('messages.payment_' . $order->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.payment_status') }}</th>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status_color }}">
                                            {{ __('messages.payment_status_' . $order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h4>{{ __('messages.customer_information') }}</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ __('messages.customer_name') }}</th>
                                    <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.email') }}</th>
                                    <td>{{ $order->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.phone') }}</th>
                                    <td>{{ $order->shippingAddress ? $order->shippingAddress->phone : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.shipping_address') }}</th>
                                    <td>
                                        @if($order->shippingAddress)
                                            {{ $order->shippingAddress->address }}, 
                                            {{ $order->shippingAddress->ward_name }}, 
                                            {{ $order->shippingAddress->district_name }}, 
                                            {{ $order->shippingAddress->province_name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>{{ __('messages.order_items') }}</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.product') }}</th>
                                        <th class="text-center">{{ __('messages.quantity') }}</th>
                                        <th class="text-end">{{ __('messages.price') }}</th>
                                        <th class="text-end">{{ __('messages.subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2" style="width: 40px; height: 40px;">
                                                        @if(Str::startsWith($item->product->image, ['http://', 'https://']))
                                                            <img src="{{ $item->product->image }}" 
                                                                alt="{{ $item->product->name }}" 
                                                                class="w-100 h-100 object-fit-cover rounded">
                                                        @else
                                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                alt="{{ $item->product->name }}" 
                                                                class="w-100 h-100 object-fit-cover rounded">
                                                        @endif
                                                    </div>
                                                    <div>{{ $item->product->name }}</div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->price) }}đ</td>
                                            <td class="text-end">{{ number_format($item->subtotal) }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($order->total) }}đ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>{{ __('messages.update_status') }}</h4>
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('messages.order_status') }}</label>
                                            <select name="status" class="form-control">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                    {{ __('messages.status_pending') }}
                                                </option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                                    {{ __('messages.status_processing') }}
                                                </option>
                                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>
                                                    {{ __('messages.status_shipping') }}
                                                </option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                                    {{ __('messages.status_completed') }}
                                                </option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                    {{ __('messages.status_cancelled') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end" style="margin-bottom: 16px;">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('messages.update_status') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 