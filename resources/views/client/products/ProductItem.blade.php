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
            <span class="product-price">{{ number_format($product->price) }}₫</span>
        </div>
        <button type="button" class="btn-modern-cart btn-add-to-cart" data-product-id="{{ $product->id }}" data-image-id="product-img-{{ $product->id }}">
            Thêm vào giỏ
        </button>
    </div>
</div>

<style>
.product-card-modern {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden;
    transition: box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    padding: 0;
    height: 100%;
}
.product-card-modern:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.13);
}
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
    padding: 12px 16px 16px 16px;
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    align-items: stretch;
}
.product-title {
    font-size: 1.05em;
    font-weight: 600;
    color: #222;
    margin-bottom: 8px;
    line-height: 1.3em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.6em;
    text-decoration: none;
}
.product-price-row {
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.product-price {
    color: #ee4d2d;
    font-size: 1.2em;
    font-weight: bold;
}
.btn-modern-cart {
    background: #fff;
    color: #ee4d2d;
    border: 1.5px solid #ee4d2d;
    border-radius: 8px;
    padding: 8px 0;
    font-weight: 600;
    font-size: 1em;
    transition: all 0.18s;
    cursor: pointer;
    margin-top: auto;
}
.btn-modern-cart:hover {
    background: #ee4d2d;
    color: #fff;
    border-color: #ee4d2d;
}
</style> 