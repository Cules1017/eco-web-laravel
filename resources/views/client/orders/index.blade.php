@extends('layouts.eshopper')

@section('title', __('messages.orders'))

@section('content')
<div class="container py-5"  style="margin-top: 100px;">
    <h1 class="mb-4">{{ __('messages.orders') }}</h1>
    <form method="GET" class="mb-3 d-flex align-items-center" action="">
        <label class="me-2">{{ __('messages.status') }}:</label>
        <select name="status" class="form-select w-auto me-2"  style="margin-bottom: 8px; height: 38px;" onchange="this.form.submit()">
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
        <div class="alert alert-info">
            {{ __('messages.no_orders') }}
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.order_number') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.total') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.payment_status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_amount) }}đ</td>
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
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    {{ __('messages.view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection 