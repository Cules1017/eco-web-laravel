@extends('layouts.eshopper')

@section('title', __('messages.order_confirmation'))

@section('content')
<div class="container vs-page-wrapper py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <!-- Receipt Container -->
            <div class="receipt-container p-4 p-md-5" style="border: 1px solid #e0e0e0; background: #fff;">
                <!-- Success Message -->
                <div class="text-center mb-5 border-bottom pb-5">
                    <h1 class="mb-4 text-uppercase" style="font-weight: 300; font-size: 3.5rem; letter-spacing: 4px;">{{ __('messages.thank_you') }}</h1>
                    <p class="lead mb-2" style="font-weight: 300; letter-spacing: 1px; font-size: 1.25rem;">{{ __('messages.order_success') }}</p>
                    <p class="text-muted text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">{{ __('messages.order_number') }}: #{{ $order->id }}</p>
                </div>

                <!-- Order Details -->
                <div class="mb-5">
                    <div class="row mb-5">
                        <div class="col-sm-6 mb-4 mb-sm-0">
                            <h6 class="text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing: 1px; font-size: 0.8rem;">{{ __('messages.shipping_information') }}</h6>
                            <p class="mb-1" style="font-weight: 500;">{{ $order->first_name }} {{ $order->last_name }}</p>
                            <p class="mb-1 text-muted">{{ $order->email }}</p>
                            <p class="mb-1 text-muted">{{ $order->phone }}</p>
                            <p class="mb-1 text-muted">{{ $order->address }}</p>
                            <p class="mb-1 text-muted">{{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}</p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing: 1px; font-size: 0.8rem;">{{ __('messages.order_information') }}</h6>
                            <p class="mb-2 text-muted" style="font-size: 0.9rem;">
                                <span class="d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('messages.order_date') }}</span> 
                                {{ $order->created_at->format('F j, Y H:i') }}
                            </p>
                            <p class="mb-2 text-muted" style="font-size: 0.9rem;">
                                <span class="d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('messages.order_status') }}</span>
                                {{ __('messages.status_' . $order->status) }}
                            </p>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                <span class="d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">{{ __('messages.payment_method') }}</span>
                                {{ __('messages.' . $order->payment_method) }}
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr class="border-bottom text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                    <th class="px-0 pb-3">{{ __('messages.product') }}</th>
                                    <th class="px-0 pb-3 text-center">{{ __('messages.quantity') }}</th>
                                    <th class="px-0 pb-3 text-end">{{ __('messages.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td class="px-0 py-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/50x50' }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="me-3" 
                                                     style="width: 50px; height: 50px; object-fit: contain;">
                                                <div>
                                                    <h6 class="mb-1" style="font-size: 0.95rem; font-weight: 400;">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-0 py-4 text-center text-muted align-middle">{{ $item->quantity }}</td>
                                        <td class="px-0 py-4 text-end vs-price-vnd align-middle">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-0">
                                <tr>
                                    <td colspan="2" class="text-end px-0 pt-4" style="font-size: 0.9rem;">{{ __('messages.subtotal') }}:</td>
                                    <td class="text-end px-0 pt-4">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end px-0 pb-3" style="font-size: 0.9rem;">{{ __('messages.tax') }}:</td>
                                    <td class="text-end px-0 pb-3">{{ number_format($order->tax, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end px-0 py-4 text-uppercase" style="letter-spacing: 1px;"><strong>{{ __('messages.total') }}</strong></td>
                                    <td class="text-end px-0 py-4"><strong class="vs-price-vnd" style="font-size: 1.25rem;">{{ number_format($order->total, 0, ',', '.') }}₫</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="text-center mt-5 pt-4 border-top">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark px-4 py-2 rounded-0 text-uppercase me-2 mb-2" style="letter-spacing: 1px; font-size: 0.85rem;">
                        {{ __('messages.continue_shopping') }}
                    </a>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-dark px-4 py-2 rounded-0 text-uppercase mb-2" style="letter-spacing: 1px; font-size: 0.85rem;">
                        {{ __('messages.view_order_details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 