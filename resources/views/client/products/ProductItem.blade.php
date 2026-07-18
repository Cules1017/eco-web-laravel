@props(['product'])
<div class="product-card-modern">
    <a href="{{ route('products.show', $product->slug) }}" class="product-img-link">
        <img id="product-img-{{ $product->id }}"
             src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image' }}"
             alt="{{ $product->name }}"
             onerror="this.onerror=null;this.src='https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';"
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
        aspect-ratio: 1 / 1; /* Cố định tỷ lệ 1:1 cho tất cả hình ảnh */
        background: var(--vs-bg-soft);
        overflow: hidden;
        position: relative;
        border-radius: 12px;
    }
    .product-img-link img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Cắt ảnh lấp đầy khung mà không bị méo */
        transition: transform var(--vs-transition);
    }
    .product-card-modern:hover .product-img-link img {
        transform: scale(1.05); /* Hiệu ứng zoom nhẹ khi hover */
    }
    .product-info {
        padding: 16px 20px 20px;
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
    .product-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--vs-text);
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 2.8em;
        text-decoration: none;
    }
    .product-title:hover { color: var(--vs-accent); }
    .product-price-row {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .product-price { font-size: 1.25rem; }
    .product-card-modern {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .btn-modern-cart {
        opacity: 0;
        transition: all 0.3s ease;
        width: 100%;
        padding: 10px 16px;
        background-color: var(--vs-accent, #10b981);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-modern-cart:hover {
        filter: brightness(0.9);
        transform: translateY(-2px);
    }
    .product-card-modern:hover .btn-modern-cart {
        opacity: 1;
    }
    </style>
    @endpush
@endonce
