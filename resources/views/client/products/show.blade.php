@extends('layouts.eshopper')

@section('title', $product->name)

@section('content')
<div class="container my-4" style="max-width: 1100px;">
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
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <div class="product-img-modern d-flex align-items-center justify-content-center" style="background: transparent; box-shadow: none; padding: 0; aspect-ratio: 1/1; max-height: 550px; width: 100%;">
                <a href="#" data-bs-toggle="modal" data-bs-target="#productImageModal" class="w-100 h-100 d-flex align-items-center justify-content-center" onclick="updateModalImage('{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/600x600' }}')">
                    <img id="product-img-{{ $product->id }}"
                         src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==' }}"
                         onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';"
                         alt="{{ $product->name }}"
                         style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px; cursor: zoom-in;" />
                </a>
            </div>
            
            @if($product->images && $product->images->count() > 0)
            <div class="mt-3 d-flex flex-wrap" style="gap: 10px;">
                <!-- Thumbnail for main image -->
                <div class="gallery-thumb active-thumb" style="width: 80px; height: 80px; border: 2px solid #333; border-radius: 8px; overflow: hidden; cursor: pointer;" onclick="changeMainImage('{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==' }}', this)">
                    <img src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==' }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <!-- Thumbnails for additional images -->
                @foreach($product->images as $img)
                <div class="gallery-thumb" style="width: 80px; height: 80px; border: 2px solid transparent; border-radius: 8px; overflow: hidden; cursor: pointer;" onclick="changeMainImage('{{ Str::startsWith($img->image_path, ['http://', 'https://']) ? $img->image_path : asset('storage/' . $img->image_path) }}', this)">
                    <img src="{{ Str::startsWith($img->image_path, ['http://', 'https://']) ? $img->image_path : asset('storage/' . $img->image_path) }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endforeach
            </div>
            <script>
                function changeMainImage(src, element) {
                    document.getElementById('product-img-{{ $product->id }}').src = src;
                    document.getElementById('productImageModal').querySelector('img').src = src;
                    
                    // Update border
                    document.querySelectorAll('.gallery-thumb').forEach(el => el.style.borderColor = 'transparent');
                    if (element) {
                        element.style.borderColor = '#333';
                    }
                }
                function updateModalImage(src) {
                    // Cập nhật modal image từ main image hiện tại (vì modal nó sẽ lấy src ở thời điểm click)
                    let currentSrc = document.getElementById('product-img-{{ $product->id }}').src;
                    document.getElementById('modal-product-img-{{ $product->id }}').src = currentSrc;
                }
            </script>
            @endif
        </div>
        <!-- Product Details -->
        <div class="col-md-6 ps-md-4">
            <div class="d-flex align-items-center mb-2 gap-2">
                @if($product->stock > 0)
                    <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span> <span class="text-success fw-semibold small">Còn hàng</span>
                @else
                    <span class="d-inline-block rounded-circle bg-danger" style="width: 8px; height: 8px;"></span> <span class="text-danger fw-semibold small">Hết hàng</span>
                @endif
            </div>
            <div class="d-flex align-items-start mb-2" style="gap: 16px;">
                <h1 class="product-title-modern flex-grow-1 mb-0">{{ $product->name }}</h1>
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
                <div class="d-flex align-items-center gap-3 mb-3">
                    <!-- Quantity Control -->
                    <div class="d-flex align-items-center border rounded-pill" style="height: 54px; background: #fff; border-color: #ddd !important; overflow: hidden;">
                        <button type="button" class="btn btn-link text-dark px-3 text-decoration-none" style="font-size: 1.2rem;" onclick="document.getElementById('quantity').stepDown()">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control border-0 text-center fw-bold p-0 quantity-input-modern" style="width: 40px; height: 100%; box-shadow: none; font-size: 1.1rem; background: transparent;">
                        <button type="button" class="btn btn-link text-dark px-3 text-decoration-none" style="font-size: 1.2rem;" onclick="document.getElementById('quantity').stepUp()">+</button>
                    </div>

                    <!-- Add to Cart -->
                    <button type="button" class="btn flex-fill fw-bold rounded-pill shadow-sm btn-add-to-cart" style="height: 54px; background: #28a745; color: #fff; font-size: 1.1rem; border: none;" data-product-id="{{ $product->id }}" data-image-id="product-img-{{ $product->id }}">
                        <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ
                    </button>
                </div>
                <!-- Buy Now -->
                <button type="button" class="btn w-100 fw-bold rounded-pill shadow-sm btn-buy-now mb-3" style="height: 54px; background: #111; color: #fff; font-size: 1.1rem; border: none;" data-product-id="{{ $product->id }}" data-image-id="product-img-{{ $product->id }}" data-buy-now="1">
                    <i class="fas fa-bolt me-2"></i>Mua ngay
                </button>
                
                <!-- Action Links -->
                <div class="d-flex justify-content-center gap-4 mt-2">
                    <button type="button" onclick="toggleWishlist('{{ $product->id }}', '{{ addslashes($product->name) }}', '{{ $product->price }}', '{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/600' }}', '{{ route('products.show', $product) }}')" class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center btn-wishlist" data-id="{{ $product->id }}" style="font-size: 0.95rem; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                        <i class="far fa-heart me-2" style="font-size: 1.1rem;"></i> Yêu thích
                    </button>
                    <button type="button" id="btn-share" class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center" style="font-size: 0.95rem; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                        <i class="fas fa-share-alt me-2" style="font-size: 1.1rem;"></i> Chia sẻ
                    </button>
                </div>
            </form>
            <!-- Mô tả sản phẩm kiểu Shopee -->
            <div class="product-desc-toggle mt-4">
                <h5 class="fw-bold mb-3 text-uppercase border-bottom pb-2">Mô tả sản phẩm</h5>
                <div id="desc-content" class="product-desc-modern shopee-desc-collapsed">{!! $product->description !!}</div>
                <div class="text-center mt-3">
                    <button id="btn-toggle-desc" class="btn btn-outline-dark rounded-pill px-4" style="display:none;">Xem thêm <i class="fas fa-chevron-down ms-1"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for image zoom -->
    <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body d-flex justify-content-center align-items-center p-0 position-relative">
                    <img id="modal-product-img-{{ $product->id }}"
                         src="{{ $product->image ? (Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) : 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==' }}"
                         onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MDAiIGhlaWdodD0iODAwIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';"
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
    .product-img-modern {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }
    .product-img-modern img {
        transition: transform 0.3s ease;
    }
    .product-img-modern:hover img {
        transform: scale(1.02);
    }
    .btn-add-to-cart {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    .btn-add-to-cart:active {
        transform: scale(0.96) translateY(0);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .product-title-modern {
        font-size: 2.75rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: #111;
        line-height: 1.1;
    }
    .product-price-modern {
        color: #0a0a0a;
        font-size: 3.2rem;
        font-weight: 900;
        text-shadow: 0px 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 12px;
    }
    .product-desc-modern {
        color: #333;
        font-size: 1.08em;
        line-height: 1.6;
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
        .product-title-modern {
            font-size: 2rem;
        }
    }
    .shopee-desc-collapsed {
        max-height: 250px;
        overflow: hidden;
        position: relative;
        color: #333;
        font-size: 1.08em;
        transition: max-height 0.3s ease;
    }
    .shopee-desc-collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
        pointer-events: none;
    }
    .shopee-desc-expanded {
        max-height: none; /* Không giới hạn để tránh lỗi đè nút khi bài viết quá dài */
        overflow: visible;
    }
    .product-desc-modern img {
        max-width: 100% !important;
        height: auto !important;
    }

    /* Hide spin buttons for modern quantity input */
    .quantity-input-modern::-webkit-inner-spin-button,
    .quantity-input-modern::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-input-modern {
        -moz-appearance: textfield;
    }
    .btn-buy-now {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-buy-now:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.3) !important;
        background: #333 !important;
    }
    .btn-add-to-cart {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(40,167,69,0.3) !important;
        background: #218838 !important;
    }
    </style>
    <script>
    // Khởi tạo các sự kiện khi DOM đã load xong
    document.addEventListener('DOMContentLoaded', function() {
        // Zoom on click for modal image
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

        // Chỉ hiện nút Xem thêm nếu nội dung mô tả dài
        const btnToggleDesc = document.getElementById('btn-toggle-desc');
        const descContent = document.getElementById('desc-content');
        let expanded = false;
        
        // Đợi 500ms để hình ảnh trong mô tả load bớt (nếu có) để tính chiều cao chuẩn hơn
        setTimeout(function() {
            if(descContent.scrollHeight > 250) {
                btnToggleDesc.style.display = 'inline-block';
            } else {
                btnToggleDesc.style.display = 'none';
                descContent.classList.remove('shopee-desc-collapsed');
            }
        }, 500);

        btnToggleDesc.addEventListener('click', function() {
            expanded = !expanded;
            if(expanded) {
                descContent.classList.remove('shopee-desc-collapsed');
                descContent.classList.add('shopee-desc-expanded');
                btnToggleDesc.innerHTML = 'Thu gọn <i class="fas fa-chevron-up ms-1"></i>';
            } else {
                descContent.classList.remove('shopee-desc-expanded');
                descContent.classList.add('shopee-desc-collapsed');
                btnToggleDesc.innerHTML = 'Xem thêm <i class="fas fa-chevron-down ms-1"></i>';
                
                // Cuộn mượt về phần mô tả nếu đang ở quá xa bên dưới
                const rect = descContent.getBoundingClientRect();
                if(rect.top < 0) {
                    descContent.scrollIntoView({behavior: "smooth", block: "nearest"});
                }
            }
        });
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