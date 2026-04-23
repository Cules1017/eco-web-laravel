@extends('layouts.eshopper')

@section('title', __('messages.orders'))

@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">{{ __('messages.orders') }}</h1>

    <form method="GET" class="mb-4 d-flex align-items-center gap-2 flex-wrap" action="">
        <label class="fw-semibold mb-0">{{ __('messages.status') }}:</label>
        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
            <option value="">{{ __('messages.all') }}</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('messages.status_processing') }}</option>
            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>{{ __('messages.status_shipping') }}</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('messages.status_completed') }}</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
        </select>
        <noscript><button type="submit" class="btn btn-primary btn-sm">{{ __('messages.filter') }}</button></noscript>
    </form>

    @if($orders->isEmpty())
        <div class="vs-empty-state">
            <div class="vs-empty-icon"><i class="fas fa-box-open"></i></div>
            <h4 class="mb-2">{{ __('messages.no_orders') }}</h4>
            <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy khám phá sản phẩm và đặt đơn đầu tiên nhé!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                <i class="fas fa-bag-shopping me-1"></i> {{ __('messages.browse_products') }}
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.order_number') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th class="text-end">{{ __('messages.total') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.payment_status') }}</th>
                                <th class="text-end">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-semibold">#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-end vs-price-vnd">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
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
                                    <td>
                                        <span class="badge bg-{{
                                            $order->payment_status === 'pending' ? 'secondary' :
                                            ($order->payment_status === 'paid' ? 'success' :
                                            ($order->payment_status === 'failed' ? 'danger' : 'info'))
                                        }}">
                                            {{ __('messages.payment_status_' . $order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> {{ __('messages.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
