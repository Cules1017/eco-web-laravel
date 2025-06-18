<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ $logo ?? asset('favicon.ico') }}">
    <!-- EShopper CSS -->
    <link rel="stylesheet" href="https://themewagon.github.io/eshopper/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
    <style>
        .eshopper-logo { max-height: 50px; }
        /* Dropdown on hover for desktop */
        @media (min-width: 992px) {
            .navbar-nav .dropdown:hover .dropdown-menu {
                display: block;
                margin-top: 0;
            }
            .navbar-nav .dropdown .dropdown-toggle::after {
                transition: transform 0.2s;
            }
            .navbar-nav .dropdown.show .dropdown-toggle::after {
                transform: rotate(180deg);
            }
        }
        body, html {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE 10+ */
        }
        body::-webkit-scrollbar, html::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        nav.navbar .navbar-collapse { flex-wrap: nowrap !important; }
        nav.navbar .search-navbar-form { margin-left: auto !important; }
    </style>
</head>
<body>
    @php
        $logo = \App\Models\Setting::getValue('site_logo') ? asset('storage/' . \App\Models\Setting::getValue('site_logo')) : 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg';
        $siteName = \App\Models\Setting::getValue('site_name', config('app.name'));
        $parentCategories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('order')->get();
    @endphp
    <!-- Header -->
    <div class="container-fluid bg-light py-2 border-bottom position-fixed top-0 w-100" style="z-index: 1040; height: 56px;">
        <div class="container d-flex justify-content-between align-items-center h-100">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <img src="{{ $logo }}" alt="Logo" class="eshopper-logo me-2">
                {{-- <span class="fw-bold text-dark">{{ $siteName }}</span> --}}
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a class="btn btn-link dropdown-toggle text-dark" href="#" id="langDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-globe"></i> {{ app()->getLocale() == 'en' ? 'English' : 'Tiếng Việt' }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">English</a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}" href="{{ route('language.switch', 'vi') }}">Tiếng Việt</a></li>
                    </ul>
                </div>
                @auth
                    <div class="dropdown">
                        <a class="btn btn-link dropdown-toggle text-dark" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name ?? Auth::user()->username ?? Auth::user()->email }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('client.user.profile') }}">{{ __('messages.profile') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">{{ __('messages.orders') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('addresses.index') }}">{{ __('messages.addresses') }}</a></li>
                            @if(Auth::user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('messages.admin_dashboard') }}</a></li>
                            @endif
                            <li><form action="{{ route('logout') }}" method="POST">@csrf <button class="dropdown-item" type="submit">{{ __('messages.logout') }}</button></form></li>
                        </ul>
                    </div>
                    <a href="{{ route('cart.index') }}" class="btn btn-link text-dark position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-link text-dark">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-link text-dark">{{ __('messages.register') }}</a>
                @endauth
            </div>
        </div>
    </div>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100" style="z-index: 1039; top: 56px; height: 48px;">
        <div class="container h-100 d-flex align-items-center justify-content-end">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex align-items-center flex-nowrap" id="mainNav">
                <ul class="navbar-nav mb-2 mb-lg-0 flex-grow-1">
                    <li class="nav-item"><a class="nav-link" href="/">{{ __('messages.home') }}</a></li>
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="{{ route('products.index') }}" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.products') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="productsDropdown" style="min-width:220px; max-height:70vh; overflow-y:auto;">
                            @foreach($parentCategories as $cat)
                                <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">{{ __('messages.orders') }}</a></li>
                </ul>
                <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center ms-3 search-navbar-form" style="max-width: 340px; margin-left: auto; margin-bottom: 0px;">
                    <input type="text" name="q" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}" style="border-radius: 20px; background: #fff; border: 1.5px solid #e5e7eb; color: #222; font-size: 1em; padding: 6px 16px;">
                    <button class="btn" type="submit" style="border-radius: 20px; background: #f3f4f6; color: #444; border: 1.5px solid #e5e7eb; min-width: 44px; height: 38px;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="py-4 bg-white" style="padding-top: 170px;">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <div class="mb-2">&copy; {{ date('Y') }} {{ $siteName }}.</div>
            <div class="small">{{ __('messages.powered_by') }} {{ $siteName }} &copy; <a href="https://themewagon.github.io/eshopper/" class="text-white-50" target="_blank"></a></div>
        </div>
    </footer>
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    console.log('test');
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-add-to-cart').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var productId = btn.getAttribute('data-product-id');
                var imgId = btn.getAttribute('data-image-id');
                var img = document.getElementById(imgId);
                var cartIcon = document.querySelector('.fa-shopping-cart');
                var badge = document.getElementById('cart-badge');
                var buyNow = btn.getAttribute('data-buy-now') === '1';
                var quantity = 1;
                var qtyInput = document.getElementById('quantity');
                if (qtyInput) quantity = qtyInput.value;

                // Animation
                if (img && cartIcon) {
                    var imgRect = img.getBoundingClientRect();
                    var cartRect = cartIcon.getBoundingClientRect();
                    var flyingImg = img.cloneNode(true);
                    flyingImg.style.position = 'fixed';
                    flyingImg.style.left = imgRect.left + 'px';
                    flyingImg.style.top = imgRect.top + 'px';
                    flyingImg.style.width = imgRect.width + 'px';
                    flyingImg.style.height = imgRect.height + 'px';
                    flyingImg.style.zIndex = 9999;
                    flyingImg.style.transition = 'all 0.8s cubic-bezier(.4,2,.6,1)';
                    document.body.appendChild(flyingImg);

                    setTimeout(function() {
                        flyingImg.style.left = cartRect.left + 'px';
                        flyingImg.style.top = cartRect.top + 'px';
                        flyingImg.style.width = '30px';
                        flyingImg.style.height = '30px';
                        flyingImg.style.opacity = 0.5;
                    }, 10);

                    setTimeout(function() {
                        flyingImg.remove();
                    }, 900);
                }

                // AJAX thêm vào giỏ hàng
                fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        buy_now: buyNow ? 1 : 0
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cart_count !== undefined && badge) {
                        badge.textContent = data.cart_count;
                    }
                    if (buyNow && data.success) {
                        window.location.href = "{{ route('checkout') }}";
                    }
                    // Hiện toast khi thêm vào giỏ hàng thành công
                    if (data.success) {
                        if (typeof showCartToast === 'function') {
                            showCartToast('Đã thêm vào giỏ hàng!', true);
                        } else {
                            // Nếu chưa có hàm toast, dùng alert đơn giản
                            // alert('Đã thêm vào giỏ hàng!');
                        }
                    } else {
                        if (typeof showCartToast === 'function') {
                            showCartToast('Thêm vào giỏ hàng thất bại!', false);
                        }
                    }
                });
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html> 