@extends('layouts.eshopper')

@section('title', $product->name)

@section('content')
<div class="container product-detail-modern" style="padding-top: 60px; max-width: 1100px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
            @if($product->category)
            <li class="breadcrumb-item">
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}">
                    {{ $product->category->name }}
                </a>
            </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>
    <div class="row g-5">
        <!-- Product Image + Badge -->
        <div class="col-md-5 mb-4 position-relative">
            <div class="product-img-modern d-flex align-items-center justify-content-center position-relative">
                <a href="#" data-bs-toggle="modal" data-bs-target="#productImageModal">
                    <img id="product-img-{{ $product->id }}"
                         src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/600x600' }}"
                         alt="{{ $product->name }}"
                         style="max-width:340px; max-height:340px; object-fit:contain; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,0.08); background:#fff; cursor: zoom-in; transition: box-shadow 0.2s;" />
                </a>
                @if($product->stock > 0)
                    <span class="badge badge-modern badge-overlay bg-success">Còn hàng</span>
                @else
                    <span class="badge badge-modern badge-overlay bg-danger">Hết hàng</span>
                @endif
            </div>
        </div>
        <!-- Product Details -->
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                <h1 class="product-title-modern mb-0">{{ $product->name }}</h1>
                <button id="btn-favorite" class="btn p-0" style="font-size: 2rem; color: #ee4d2d; background: none; border: none; outline: none; margin-left: 4px;" title="Yêu thích">
                    <i id="icon-favorite" class="fa{{ in_array($product->id, json_decode(request()->cookie('favorite_products', '[]'), true) ?? []) ? 's' : 'r' }} fa-heart"></i>
                </button>
                <button id="btn-share" class="btn p-0" style="font-size: 2rem; color: #22c55e; background: none; border: none; outline: none; margin-left: 4px;" title="Chia sẻ">
                    <i class="fas fa-share-alt"></i>
                </button>
            </div>
            <div class="mb-2 text-muted" style="font-size:1.1em;">
                <span>Danh mục:
                @if($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none text-primary fw-semibold">{{ $product->category->name }}</a>
                @else
                    <span class="text-secondary">Uncategorized</span>
                @endif
                </span>
            </div>
            <div class="product-price-modern mb-4">{{ number_format($product->price) }}₫</div>
            <form id="add-to-cart-form" class="mb-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="row align-items-center g-2 mb-2">
                    <div class="col-auto">
                        <label for="quantity" class="form-label mb-0">Số lượng</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="width: 80px; display:inline-block;">
                    </div>
                    <div class="col">
                        <div class="d-flex flex-row gap-3">
                            <button type="button" class="btn btn-modern-cart flex-fill btn-add-to-cart" style="margin-right: 10px; height: 60px;" data-product-id="{{ $product->id }}" data-image-id="product-img-{{ $product->id }}">Thêm vào giỏ</button>
                            <button type="button" class="btn btn-modern-buy flex-fill btn-add-to-cart" style="height: 60px;" data-product-id="{{ $product->id }}" data-image-id="product-img-{{ $product->id }}" data-buy-now="1">Mua ngay</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Mô tả sản phẩm kiểu Shopee -->
            <div class="product-desc-toggle mt-2">
                <h5 class="fw-semibold mb-2">Mô tả sản phẩm</h5>
                <div id="desc-content" class="product-desc-modern shopee-desc-collapsed">{!! $product->description !!}</div>
                <button id="btn-toggle-desc" class="btn btn-link px-0 mt-2" style="font-weight:600; color:#1677ff; display:none;">Xem thêm <i class="fas fa-chevron-down"></i></button>
            </div>
        </div>
    </div>
    <!-- Modal for image zoom -->
    <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body d-flex justify-content-center align-items-center p-0 position-relative">
                    <img id="modal-product-img-{{ $product->id }}"
                         src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/600x600' }}"
                         alt="{{ $product->name }}"
                         style="max-width:90vw; max-height:80vh; object-fit:contain; border-radius:16px; background:#fff; cursor: zoom-in; transition: transform 0.2s;" />
                    <button type="button" class="btn-close-custom position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Chia sẻ sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="share-link" value="{{ url()->current() }}" readonly>
                        <button class="btn btn-outline-primary" type="button" id="btn-copy-link">Copy</button>
                    </div>
                    <div id="copy-success" class="text-success mt-2" style="display:none;">Đã copy link!</div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .product-detail-modern {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        padding: 32px 24px 32px 24px;
        margin-bottom: 32px;
    }
    .product-img-modern {
        background: #f7f7f7;
        border-radius: 24px;
        min-height: 340px;
        min-width: 340px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        padding: 16px;
        position: relative;
    }
    .badge-overlay {
        position: absolute;
        top: 18px;
        left: 18px;
        z-index: 2;
        font-size: 1em;
        padding: 6px 22px;
        border-radius: 12px;
        background: rgba(34,197,94,0.95) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .badge-overlay.bg-danger { background: rgba(239,68,68,0.95) !important; }
    .product-title-modern {
        font-size: 2.2rem;
        font-weight: 700;
        color: #222;
        line-height: 1.2;
    }
    .product-price-modern {
        color: #ee4d2d;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .product-desc-modern {
        color: #444;
        font-size: 1.08em;
        background: #f9f9f9;
        border-radius: 10px;
        padding: 12px 16px;
        min-height: 56px;
    }
    .btn-modern-cart {
        background: #fff;
        color: #ee4d2d;
        border: 2px solid #ee4d2d;
        border-radius: 8px;
        padding: 10px 0;
        font-weight: 600;
        font-size: 1.08em;
        transition: all 0.18s;
        cursor: pointer;
    }
    .btn-modern-cart:hover {
        background: #ee4d2d;
        color: #fff;
        border-color: #ee4d2d;
    }
    .btn-modern-buy {
        background: #22c55e;
        color: #fff;
        border: 2px solid #22c55e;
        border-radius: 8px;
        padding: 10px 0;
        font-weight: 600;
        font-size: 1.08em;
        transition: all 0.18s;
        cursor: pointer;
    }
    .btn-modern-buy:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
    }
    .badge-modern {
        font-size: 1em;
        padding: 0.5em 1.2em;
        border-radius: 8px;
        font-weight: 600;
    }
    @media (max-width: 900px) {
        .product-img-modern, .product-detail-modern { min-width: unset; }
    }
    .btn-close-custom {
        background: rgba(0,0,0,0.65) !important;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1052;
        border: none;
    }
    .btn-close-custom:after {
        content: '\00d7';
        color: #fff;
        font-size: 2rem;
        font-weight: bold;
        line-height: 1;
    }
    .btn-close-custom:hover, .btn-close-custom:focus {
        background: rgba(0,0,0,0.85) !important;
        outline: none;
    }
    @media (max-width: 600px) {
        .d-flex.flex-row.gap-3 > .btn {
            min-width: 0;
            width: 100%;
            margin-bottom: 8px;
        }
        .d-flex.flex-row.gap-3 {
            flex-direction: column !important;
            gap: 10px !important;
        }
    }
    .shopee-desc-collapsed {
        max-height: 4.8em;
        overflow: hidden;
        position: relative;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        background: #f9f9f9;
        border-radius: 10px;
        padding: 12px 16px;
        color: #444;
        font-size: 1.08em;
        transition: max-height 0.2s;
    }
    .shopee-desc-expanded {
        max-height: 1000px;
        overflow: visible;
        -webkit-line-clamp: unset;
    }
    </style>
    <script>
        console.log('show');
        
    // Zoom on click for modal image
    document.addEventListener('DOMContentLoaded', function() {
        var modalImg = document.getElementById('modal-product-img-{{ $product->id }}');
        var zoomed = false;
        if(modalImg) {
            modalImg.addEventListener('click', function() {
                zoomed = !zoomed;
                if(zoomed) {
                    modalImg.style.transform = 'scale(2)';
                    modalImg.style.cursor = 'zoom-out';
                } else {
                    modalImg.style.transform = 'scale(1)';
                    modalImg.style.cursor = 'zoom-in';
                }
            });
        }
        // Reset zoom when modal closes
        var modal = document.getElementById('productImageModal');
        if(modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                if(modalImg) {
                    modalImg.style.transform = 'scale(1)';
                    modalImg.style.cursor = 'zoom-in';
                    zoomed = false;
                }
            });
        }
    });
    // Yêu thích sản phẩm với localStorage
    const favoriteKey = 'favorite_products';
    function getFavorites() {
        try {
            return JSON.parse(localStorage.getItem(favoriteKey)) || [];
        } catch { return []; }
    }
    function setFavorites(arr) {
        localStorage.setItem(favoriteKey, JSON.stringify(arr));
    }
    document.getElementById('btn-favorite').addEventListener('click', function() {
        let favs = getFavorites();
        const id = {{ $product->id }};
        const icon = document.getElementById('icon-favorite');
        if (favs.includes(id)) {
            favs = favs.filter(x => x !== id);
            icon.classList.remove('fas');
            icon.classList.add('far');
        } else {
            favs.push(id);
            icon.classList.remove('far');
            icon.classList.add('fas');
        }
        setFavorites(favs);
    });
    // Share sản phẩm
    const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
    document.getElementById('btn-share').addEventListener('click', function() {
        shareModal.show();
    });
    document.getElementById('btn-copy-link').addEventListener('click', function() {
        const input = document.getElementById('share-link');
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.getElementById('copy-success').style.display = 'block';
        setTimeout(() => { document.getElementById('copy-success').style.display = 'none'; }, 1500);
    });
    // Chỉ hiện nút Xem thêm nếu mô tả bị cắt (kể cả HTML)
    function isClamped(el) {
        // Tạo 1 clone ẩn để đo chiều cao thực tế nếu không bị clamp
        const clone = el.cloneNode(true);
        clone.style.visibility = 'hidden';
        clone.style.position = 'absolute';
        clone.style.maxHeight = 'none';
        clone.style.height = 'auto';
        clone.style.pointerEvents = 'none';
        clone.style.zIndex = -1;
        clone.classList.remove('shopee-desc-collapsed');
        clone.classList.remove('shopee-desc-expanded');
        document.body.appendChild(clone);
        const realHeight = clone.scrollHeight;
        document.body.removeChild(clone);
        // So sánh với chiều cao clamp
        return el.scrollHeight < realHeight - 2;
    }
    const btnToggleDesc = document.getElementById('btn-toggle-desc');
    const descContent = document.getElementById('desc-content');
    let expanded = false;
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() { // Đợi render HTML xong
            if(isClamped(descContent)) {
                btnToggleDesc.style.display = 'inline-block';
            } else {
                btnToggleDesc.style.display = 'none';
                descContent.classList.remove('shopee-desc-collapsed');
            }
        }, 100);
    });
    btnToggleDesc.addEventListener('click', function() {
        expanded = !expanded;
        if(expanded) {
            descContent.classList.remove('shopee-desc-collapsed');
            descContent.classList.add('shopee-desc-expanded');
            btnToggleDesc.innerHTML = 'Thu gọn <i class="fas fa-chevron-up"></i>';
        } else {
            descContent.classList.remove('shopee-desc-expanded');
            descContent.classList.add('shopee-desc-collapsed');
            btnToggleDesc.innerHTML = 'Xem thêm <i class="fas fa-chevron-down"></i>';
        }
    });
    </script>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">{{ __('messages.related_products') }}</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                @include('client.products.ProductItem', ['product' => $relatedProduct])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection 