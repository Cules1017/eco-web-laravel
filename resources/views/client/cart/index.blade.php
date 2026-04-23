@extends('layouts.eshopper')

@section('title', __('messages.shopping_cart'))

@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">{{ __('messages.shopping_cart') }}</h1>

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

    @if(count($cartItems) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th>{{ __('messages.price') }}</th>
                            <th>{{ __('messages.quantity') }}</th>
                            <th>{{ __('messages.subtotal') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->product->image ? (Str::startsWith($item->product->image, ['http://', 'https://']) ? $item->product->image : asset('storage/' . $item->product->image)) : 'https://via.placeholder.com/100x100' }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-thumbnail me-3" 
                                         style="width: 100px; max-width: 100px; max-height: 100px; object-fit: contain;">
                                    <div>
                                        <h5 class="mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-0">{{ $item->product->category->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="vs-price-vnd">{{ number_format($item->product->price, 0, ',', '.') }}₫</td>
                            <td>
                                <form class="cart-update-form d-flex align-items-center" data-product-id="{{ $item->product->id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="{{ $item->product->stock }}" 
                                           class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="vs-price-vnd">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}₫</td>
                            <td>
                                <form class="cart-remove-form" data-product-id="{{ $item->product->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                            <td><strong class="vs-price-vnd" style="font-size: 1.1rem;">{{ number_format($total, 0, ',', '.') }}₫</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('messages.continue_shopping') }}
                </a>
                <a href="{{ route('checkout') }}" class="btn btn-primary">
                    {{ __('messages.proceed_to_checkout') }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="vs-empty-state">
        <div class="vs-empty-icon"><i class="fas fa-shopping-cart"></i></div>
        <h4 class="mb-2">{{ __('messages.empty_cart') }}</h4>
        <p class="text-muted">{{ __('messages.add_products') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
            <i class="fas fa-bag-shopping me-1"></i> {{ __('messages.browse_products') }}
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
var showCartToast = window.showCartToast || function(){};

function setBtnLoading(btn, loading) {
    if (loading) {
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>' + btn.innerHTML;
    } else {
        btn.disabled = false;
        if (btn.dataset.originalHtml) btn.innerHTML = btn.dataset.originalHtml;
    }
}

document.querySelectorAll('.cart-update-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const productId = form.getAttribute('data-product-id');
        const quantity = form.querySelector('input[name="quantity"]').value;
        const btn = form.querySelector('button[type="submit"]');
        setBtnLoading(btn, true);
        fetch("{{ route('cart.update') }}", {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            setBtnLoading(btn, false);
            showCartToast(data.message, data.success);
            if (data.success) setTimeout(() => window.location.reload(), 800);
        })
        .catch(() => {
            setBtnLoading(btn, false);
            showCartToast('Có lỗi xảy ra, vui lòng thử lại!', false);
        });
    });
});
document.querySelectorAll('.cart-remove-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const productId = form.getAttribute('data-product-id');
        const btn = form.querySelector('button[type="submit"]');
        setBtnLoading(btn, true);
        fetch("{{ route('cart.remove') }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            setBtnLoading(btn, false);
            showCartToast(data.message, data.success);
            if (data.success) setTimeout(() => window.location.reload(), 800);
        })
        .catch(() => {
            setBtnLoading(btn, false);
            showCartToast('Có lỗi xảy ra, vui lòng thử lại!', false);
        });
    });
});
</script>
@endpush 