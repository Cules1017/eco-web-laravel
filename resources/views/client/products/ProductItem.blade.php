@props(['product'])
<div class="product-card-modern">
    <a href="{{ route('products.show', $product->slug) }}" class="product-img-link">
        <img id="product-img-{{ $product->id }}"
             src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/300x300' }}"
             alt="{{ $product->name }}"
             onerror="this.onerror=null;this.src='https://via.placeholder.com/300x300?text=No+Image';"
        />
    </a>
    <div class="product-info">
        <a href="{{ route('products.show', $product->slug) }}" class="product-title" title="{{ $product->name }}">
            {{ \Illuminate\Support\Str::limit($product->name, 60) }}
        </a>
        <div class="product-price-row">
            <span class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
        </div>
        <button type="button" class="btn-modern-cart btn-add-to-cart"
                data-product-id="{{ $product->id }}"
                data-image-id="product-img-{{ $product->id }}">
            <i class="fas fa-cart-plus me-1"></i> {{ __('messages.add_to_cart') }}
        </button>
    </div>
</div>

@once
    @push('styles')
    <style>
    .product-img-link {
        display: block;
        width: 100%;
        background: #fafafa;
        text-align: center;
        padding: 16px 0 0 0;
        min-height: 180px;
    }
    .product-img-link img {
        max-width: 90%;
        max-height: 160px;
        object-fit: contain;
        margin: 0 auto;
        display: block;
        background: #fff;
        border-radius: 12px;
    }
    .product-info {
        padding: 12px 16px 16px;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .product-title {
        font-size: 1.0rem;
        font-weight: 600;
        color: var(--vs-text);
        margin-bottom: 8px;
        line-height: 1.35em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 2.7em;
        text-decoration: none;
    }
    .product-title:hover { color: var(--vs-primary); }
    .product-price-row {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .product-price { font-size: 1.15rem; }
    </style>
    @endpush
@endonce
