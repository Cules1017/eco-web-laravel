<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ $logo ?? asset('favicon.ico') }}">
    <!-- EShopper CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://themewagon.github.io/eshopper/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}">
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
            /* Sản phẩm: hover chỉ bật display — Popper không chạy nên cần neo theo mục menu, không dùng position-static (full-width mega-menu). */
            .navbar-nav .products-categories-dropdown .dropdown-menu {
                left: 0 !important;
                top: 100% !important;
                transform: none !important;
                right: auto;
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
        .ai-assistant-btn {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 10050;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            box-shadow: 0 10px 28px rgba(99, 102, 241, 0.45);
            cursor: grab;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            user-select: none;
            touch-action: none;
        }
        .ai-assistant-btn:hover {
            transform: scale(1.06);
            box-shadow: 0 14px 32px rgba(236, 72, 153, 0.45);
        }
        .ai-assistant-btn.dragging {
            cursor: grabbing;
            transition: none;
        }
        .ai-assistant-btn .fa-robot { font-size: 24px; }
        .ai-assistant-btn::after {
            content: "";
            position: absolute;
            top: 6px; right: 6px;
            width: 12px; height: 12px;
            background: #22c55e;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .ai-assistant-panel {
            position: fixed;
            right: 22px;
            bottom: 96px;
            z-index: 10050;
            width: min(400px, calc(100vw - 24px));
            height: min(560px, calc(100vh - 120px));
            border-radius: 18px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
            background: #fff;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            display: none;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .ai-assistant-panel.open { display: flex; }

        .ai-chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
        }
        .ai-chat-header .ai-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 10px;
        }
        .ai-chat-header .ai-title { font-size: 14px; font-weight: 600; line-height: 1.2; }
        .ai-chat-header .ai-subtitle { font-size: 11px; opacity: 0.85; }
        .ai-chat-header .ai-header-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            width: 32px; height: 32px;
            border-radius: 8px;
            font-size: 13px;
            transition: background 0.15s;
        }
        .ai-chat-header .ai-header-btn:hover { background: rgba(255,255,255,0.35); }

        .ai-chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 14px 12px;
            background: #f5f7fb;
            scrollbar-width: thin;
        }
        .ai-chat-box::-webkit-scrollbar { width: 6px; }
        .ai-chat-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        .ai-msg-row {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            margin-bottom: 8px;
        }
        .ai-msg-row.user { justify-content: flex-end; }
        .ai-msg-row .ai-bot-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }
        .ai-msg {
            max-width: 78%;
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
            box-shadow: 0 1px 2px rgba(15,23,42,0.06);
        }
        .ai-msg-user {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border-bottom-right-radius: 6px;
        }
        .ai-msg-bot {
            background: #fff;
            color: #0f172a;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 6px;
        }
        .ai-msg-bot strong { color: #6366f1; font-weight: 700; }
        .ai-msg-bot em { color: #475569; }
        .ai-msg-bot ul { list-style: disc; }
        .ai-msg-bot li::marker { color: #ec4899; }
        .ai-msg-bot a { text-decoration: none; }
        .ai-msg-bot a:hover { text-decoration: underline; }
        .ai-msg-info {
            font-style: italic;
            opacity: 0.7;
            text-align: center;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            font-size: 12px;
            padding: 4px 8px !important;
            max-width: 100% !important;
        }

        .ai-suggested-products {
            display: grid;
            grid-template-columns: 1fr;
            gap: 6px;
            margin: 6px 0 8px 34px;
            max-width: 78%;
        }
        .ai-product-card {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            text-decoration: none;
            color: #0f172a;
            font-size: 13px;
            transition: background 0.15s, border-color 0.15s;
        }
        .ai-product-card:hover {
            background: #eef2ff;
            border-color: #6366f1;
            color: #0f172a;
        }
        .ai-product-card .ai-product-name {
            font-weight: 600;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ai-product-card .ai-product-price {
            color: #ec4899;
            font-weight: 700;
            white-space: nowrap;
        }

        .ai-chat-form {
            border-top: 1px solid #e5e7eb;
            padding: 10px 12px;
            background: #fff;
        }
        .ai-chat-form .form-control {
            border-radius: 24px;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .ai-chat-form .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .ai-chat-form .btn-send {
            border-radius: 50%;
            width: 40px; height: 40px;
            padding: 0;
            margin-left: 8px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            border: none;
            font-size: 14px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .ai-chat-form .btn-send:disabled { opacity: 0.55; cursor: not-allowed; }
        .ai-chat-meta { font-size: 11px; color: #64748b; margin-top: 6px; text-align: center; }

        .ai-typing {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 12px 14px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            border-bottom-left-radius: 6px;
        }
        .ai-typing span {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #94a3b8;
            opacity: 0.35;
            animation: aiTypingBounce 1.2s infinite ease-in-out;
        }
        .ai-typing span:nth-child(2) { animation-delay: 0.15s; }
        .ai-typing span:nth-child(3) { animation-delay: 0.3s; }
        @keyframes aiTypingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.35; }
            30% { transform: translateY(-5px); opacity: 1; }
        }

        @media (max-width: 480px) {
            .ai-assistant-panel {
                right: 8px !important;
                left: 8px !important;
                bottom: 88px !important;
                width: auto !important;
                height: 70vh;
            }
        }

        .ai-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 10060;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: aiFadeIn 0.18s ease-out;
        }
        .ai-modal-backdrop.open { display: flex; }
        .ai-modal {
            width: min(380px, 100%);
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
            animation: aiScaleIn 0.22s cubic-bezier(.2,.9,.3,1.2);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .ai-modal-icon {
            width: 64px; height: 64px;
            margin: 22px auto 12px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 10px 24px rgba(236, 72, 153, 0.35);
        }
        .ai-modal-body {
            padding: 0 22px 18px;
            text-align: center;
        }
        .ai-modal-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .ai-modal-text {
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }
        .ai-modal-footer {
            display: flex;
            gap: 10px;
            padding: 14px 18px 18px;
        }
        .ai-modal-btn {
            flex: 1;
            padding: 10px 14px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .ai-modal-btn-cancel {
            background: #f1f5f9;
            color: #334155;
        }
        .ai-modal-btn-cancel:hover { background: #e2e8f0; }
        .ai-modal-btn-confirm {
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            box-shadow: 0 6px 14px rgba(99, 102, 241, 0.35);
        }
        .ai-modal-btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(236, 72, 153, 0.4); }
        .ai-modal-btn-danger {
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: #fff;
            box-shadow: 0 6px 14px rgba(239, 68, 68, 0.35);
        }
        .ai-modal-btn-danger:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(239, 68, 68, 0.45); }
        @keyframes aiFadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes aiScaleIn {
            from { opacity: 0; transform: scale(0.9) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Navbar Underline Slide Effect */
        .navbar-nav .nav-item .nav-link {
            position: relative;
        }
        .navbar-nav .nav-item .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 4px;
            left: 8px;
            background-color: #fff;
            transition: width 0.3s ease-in-out;
        }
        .navbar-nav .nav-item .nav-link:hover::after,
        .navbar-nav .nav-item .nav-link:focus::after {
            width: calc(100% - 16px);
        }
        
        /* Tùy chỉnh scrollbar cho dropdown menu */
        .custom-scrollbar-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar-dropdown::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar-dropdown::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar-dropdown::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <!-- Interactive Particles Background -->
    <div id="particles-js" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; background-color: #f8fafc;"></div>

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
                <a href="{{ route('wishlist.index') }}" class="btn btn-link text-dark position-relative" title="Sản phẩm yêu thích">
                    <i class="fas fa-heart text-danger"></i>
                    <span id="wishlist-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">0</span>
                </a>
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
                        @php
                            $_cart = session('cart', []);
                            $_cartCount = array_sum(array_column($_cart, 'quantity'));
                        @endphp
                        <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="{{ $_cartCount > 0 ? '' : 'display:none;' }}">{{ $_cartCount }}</span>
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
                    <li class="nav-item dropdown position-relative products-categories-dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('products.index') }}" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.products') }}
                        </a>
                        <ul class="dropdown-menu custom-scrollbar-dropdown" aria-labelledby="productsDropdown" style="min-width:220px; max-height:70vh; overflow-y:auto; background: #ffffff; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border-radius: 12px; border: none;">
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
    <main style="background: transparent; padding-top: 130px; padding-bottom: 4rem; position: relative;">
        <div class="container" style="max-width: 1440px;">
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

    <!-- Global toast (dùng chung cho các thao tác giỏ hàng / thông báo nhanh) -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="globalCartToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="globalCartToastMsg">Đã thêm vào giỏ hàng!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
            </div>
        </div>
    </div>

    <button id="ai-assistant-btn" class="ai-assistant-btn" type="button" title="Tư vấn AI - Kéo để di chuyển">
        <i class="fas fa-robot"></i>
    </button>
    <div id="ai-assistant-panel" class="ai-assistant-panel" aria-hidden="true">
        <div class="ai-chat-header">
            <div class="d-flex align-items-center" style="min-width: 0;">
                <span class="ai-avatar"><i class="fas fa-robot"></i></span>
                <div style="min-width: 0;">
                    <div class="ai-title">Trợ lý AI tư vấn</div>
                    <div class="ai-subtitle"><span style="display:inline-block;width:7px;height:7px;background:#4ade80;border-radius:50%;margin-right:4px;"></span>Đang online • Trả lời ngay</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button id="ai-assistant-new" type="button" class="ai-header-btn" title="Cuộc trò chuyện mới" aria-label="Cuộc trò chuyện mới">
                    <i class="fas fa-pen-to-square"></i>
                </button>
                <button id="ai-assistant-close" type="button" class="ai-header-btn" aria-label="Đóng" title="Đóng">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="ai-chat-box" class="ai-chat-box"></div>
        <form id="ai-chat-form" class="ai-chat-form">
            <div class="d-flex align-items-center">
                <input id="ai-chat-input" type="text" class="form-control" placeholder="Nhập câu hỏi về sản phẩm..." maxlength="1000" autocomplete="off">
                <button id="ai-chat-submit" class="btn-send" type="submit" aria-label="Gửi">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div id="ai-chat-meta" class="ai-chat-meta">0/20 lượt • Lịch sử được lưu trên trình duyệt</div>
        </form>
    </div>

    <div id="ai-modal-backdrop" class="ai-modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="ai-modal">
            <div id="ai-modal-icon" class="ai-modal-icon"><i class="fas fa-rotate"></i></div>
            <div class="ai-modal-body">
                <div id="ai-modal-title" class="ai-modal-title">Bắt đầu cuộc trò chuyện mới?</div>
                <div id="ai-modal-text" class="ai-modal-text">Lịch sử chat hiện tại sẽ bị xóa và không thể khôi phục.</div>
            </div>
            <div class="ai-modal-footer">
                <button id="ai-modal-cancel" type="button" class="ai-modal-btn ai-modal-btn-cancel">Huỷ</button>
                <button id="ai-modal-ok" type="button" class="ai-modal-btn ai-modal-btn-danger">Đồng ý</button>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/wishlist.js') }}?v={{ time() }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const aiButton = document.getElementById('ai-assistant-btn');
        const aiPanel = document.getElementById('ai-assistant-panel');
        const aiClose = document.getElementById('ai-assistant-close');
        const aiNew = document.getElementById('ai-assistant-new');
        const aiChatForm = document.getElementById('ai-chat-form');
        const aiChatInput = document.getElementById('ai-chat-input');
        const aiChatBox = document.getElementById('ai-chat-box');
        const aiChatSubmit = document.getElementById('ai-chat-submit');
        const aiChatMeta = document.getElementById('ai-chat-meta');

        const AI_STORAGE_KEY = 'ai_assistant_history_v1';
        const AI_BTN_POS_KEY = 'ai_assistant_btn_pos_v1';
        const AI_MAX_TURNS = 20;
        const AI_WELCOME = 'Xin chào! Mình là trợ lý AI của shop, có thể tư vấn sản phẩm phù hợp với nhu cầu của bạn. Bạn đang quan tâm đến sản phẩm nào ạ?';

        let aiHistory = loadHistory();
        initDraggableButton();

        function loadHistory() {
            try {
                const raw = localStorage.getItem(AI_STORAGE_KEY);
                if (!raw) return [];
                const arr = JSON.parse(raw);
                return Array.isArray(arr) ? arr : [];
            } catch (e) {
                return [];
            }
        }

        function saveHistory() {
            try {
                localStorage.setItem(AI_STORAGE_KEY, JSON.stringify(aiHistory));
            } catch (e) {}
        }

        function countUserTurns() {
            return aiHistory.filter(x => x.role === 'user').length;
        }

        function updateMeta() {
            if (!aiChatMeta) return;
            const turns = countUserTurns();
            aiChatMeta.textContent = turns + '/' + AI_MAX_TURNS + ' lượt • Lịch sử được lưu trên trình duyệt';
        }

        function renderHistory() {
            if (!aiChatBox) return;
            aiChatBox.innerHTML = '';
            appendMessage(AI_WELCOME, 'bot', false);
            aiHistory.forEach(item => {
                appendMessage(item.text, item.role === 'bot' ? 'bot' : 'user', false);
            });
            aiChatBox.scrollTop = aiChatBox.scrollHeight;
            updateMeta();
        }

        function resetConversation(silent) {
            aiHistory = [];
            saveHistory();
            renderHistory();
            if (!silent) {
                appendInfo('Đã bắt đầu cuộc trò chuyện mới.');
            }
        }

        function appendInfo(text) {
            const row = document.createElement('div');
            row.className = 'ai-msg-row';
            row.style.justifyContent = 'center';
            const div = document.createElement('div');
            div.className = 'ai-msg ai-msg-bot ai-msg-info';
            div.textContent = text;
            row.appendChild(div);
            aiChatBox.appendChild(row);
            aiChatBox.scrollTop = aiChatBox.scrollHeight;
        }

        function appendMessage(text, type, autoscroll) {
            const row = document.createElement('div');
            row.className = 'ai-msg-row ' + (type === 'user' ? 'user' : 'bot');

            if (type !== 'user') {
                const avatar = document.createElement('span');
                avatar.className = 'ai-bot-avatar';
                avatar.innerHTML = '<i class="fas fa-robot"></i>';
                row.appendChild(avatar);
            }

            const div = document.createElement('div');
            div.className = 'ai-msg ' + (type === 'user' ? 'ai-msg-user' : 'ai-msg-bot');
            if (type === 'user') {
                div.textContent = text;
            } else {
                div.innerHTML = renderMarkdown(text);
            }
            row.appendChild(div);

            aiChatBox.appendChild(row);
            if (autoscroll !== false) {
                aiChatBox.scrollTop = aiChatBox.scrollHeight;
            }
            return div;
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function renderMarkdown(raw) {
            let text = escapeHtml(String(raw || ''));

            // Code block ```...```
            text = text.replace(/```([\s\S]*?)```/g, (_, code) =>
                '<pre style="background:#f1f5f9;padding:8px 10px;border-radius:8px;overflow-x:auto;margin:6px 0;font-size:12px;"><code>' + code + '</code></pre>'
            );
            // Inline code `x`
            text = text.replace(/`([^`\n]+)`/g, '<code style="background:#f1f5f9;padding:1px 5px;border-radius:4px;font-size:12px;">$1</code>');
            // Bold **x** or __x__
            text = text.replace(/\*\*([^*\n]+?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/__([^_\n]+?)__/g, '<strong>$1</strong>');
            // Italic *x* (không bắt giữa chữ)
            text = text.replace(/(^|[\s(])\*([^\s*][^*\n]*?)\*(?=[\s.,;:!?)]|$)/g, '$1<em>$2</em>');
            // Link [text](url)
            text = text.replace(/\[([^\]]+)\]\((https?:\/\/[^)\s]+)\)/g, '<a href="$2" target="_blank" rel="noopener" style="color:#6366f1;font-weight:600;">$1</a>');
            // Auto link url
            text = text.replace(/(^|[\s(])((?:https?:\/\/)[^\s)]+)(?=[\s.,;:!?)]|$)/g, '$1<a href="$2" target="_blank" rel="noopener" style="color:#6366f1;">$2</a>');

            // Bullet list: dòng bắt đầu "- " hoặc "* "
            const lines = text.split('\n');
            let html = '';
            let inList = false;
            lines.forEach(line => {
                const bullet = line.match(/^\s*[-*]\s+(.*)$/);
                const numbered = line.match(/^\s*(\d+)\.\s+(.*)$/);
                if (bullet) {
                    if (!inList) { html += '<ul style="margin:4px 0 4px 18px;padding:0;">'; inList = true; }
                    html += '<li style="margin:2px 0;">' + bullet[1] + '</li>';
                } else if (numbered) {
                    if (!inList) { html += '<ul style="margin:4px 0 4px 18px;padding:0;">'; inList = true; }
                    html += '<li style="margin:2px 0;"><strong>' + numbered[1] + '.</strong> ' + numbered[2] + '</li>';
                } else {
                    if (inList) { html += '</ul>'; inList = false; }
                    html += line + '<br>';
                }
            });
            if (inList) html += '</ul>';
            html = html.replace(/(<br>\s*){2,}/g, '<br><br>').replace(/<br>\s*$/, '');
            return html;
        }

        function appendSuggestedProducts(products) {
            if (!Array.isArray(products) || products.length === 0) return;
            const wrap = document.createElement('div');
            wrap.className = 'ai-suggested-products';
            products.slice(0, 4).forEach(p => {
                const card = document.createElement('a');
                card.className = 'ai-product-card';
                card.href = p.url || '#';
                card.target = '_blank';
                card.rel = 'noopener';

                const name = document.createElement('span');
                name.className = 'ai-product-name';
                name.textContent = p.name || '';

                const price = document.createElement('span');
                price.className = 'ai-product-price';
                const priceNum = Number(p.price) || 0;
                price.textContent = priceNum.toLocaleString('vi-VN') + ' đ';

                card.appendChild(name);
                card.appendChild(price);
                wrap.appendChild(card);
            });
            aiChatBox.appendChild(wrap);
            aiChatBox.scrollTop = aiChatBox.scrollHeight;
        }

        function initDraggableButton() {
            if (!aiButton) return;
            const saved = (function() {
                try { return JSON.parse(localStorage.getItem(AI_BTN_POS_KEY) || 'null'); }
                catch (e) { return null; }
            })();
            const clampBtnPos = (left, top) => {
                const size = aiButton.offsetWidth || 60;
                const pad = 6;
                return {
                    left: Math.max(pad, Math.min(window.innerWidth - size - pad, left)),
                    top: Math.max(pad, Math.min(window.innerHeight - size - pad, top))
                };
            };
            const applyPos = (left, top) => {
                const c = clampBtnPos(left, top);
                aiButton.style.right = 'auto';
                aiButton.style.bottom = 'auto';
                aiButton.style.left = c.left + 'px';
                aiButton.style.top = c.top + 'px';
                positionPanel();
            };
            if (saved && typeof saved.left === 'number' && typeof saved.top === 'number') {
                applyPos(saved.left, saved.top);
            }

            let dragging = false, moved = false;
            let startX = 0, startY = 0, startLeft = 0, startTop = 0;

            const onDown = (e) => {
                dragging = true;
                moved = false;
                aiButton.classList.add('dragging');
                const point = e.touches ? e.touches[0] : e;
                const rect = aiButton.getBoundingClientRect();
                startX = point.clientX;
                startY = point.clientY;
                startLeft = rect.left;
                startTop = rect.top;
                e.preventDefault();
            };
            const onMove = (e) => {
                if (!dragging) return;
                const point = e.touches ? e.touches[0] : e;
                const dx = point.clientX - startX;
                const dy = point.clientY - startY;
                if (Math.abs(dx) > 4 || Math.abs(dy) > 4) moved = true;
                let newLeft = startLeft + dx;
                let newTop = startTop + dy;
                const size = aiButton.offsetWidth;
                newLeft = Math.max(6, Math.min(window.innerWidth - size - 6, newLeft));
                newTop = Math.max(6, Math.min(window.innerHeight - size - 6, newTop));
                applyPos(newLeft, newTop);
            };
            const onUp = () => {
                if (!dragging) return;
                dragging = false;
                aiButton.classList.remove('dragging');
                if (moved) {
                    const rect = aiButton.getBoundingClientRect();
                    try { localStorage.setItem(AI_BTN_POS_KEY, JSON.stringify({ left: rect.left, top: rect.top })); } catch (e) {}
                }
            };

            aiButton.addEventListener('mousedown', onDown);
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
            aiButton.addEventListener('touchstart', onDown, { passive: false });
            document.addEventListener('touchmove', onMove, { passive: false });
            document.addEventListener('touchend', onUp);
            window.addEventListener('resize', function() {
                if (aiButton.style.left && aiButton.style.top) {
                    applyPos(parseFloat(aiButton.style.left) || 0, parseFloat(aiButton.style.top) || 0);
                }
                positionPanel();
            });

            aiButton.addEventListener('click', function(e) {
                if (moved) { e.preventDefault(); e.stopImmediatePropagation(); moved = false; return; }
            }, true);
        }

        function positionPanel() {
            if (!aiPanel || !aiButton) return;
            if (window.innerWidth <= 480) return;
            const rect = aiButton.getBoundingClientRect();
            const panelWidth = 400;
            const panelHeight = Math.min(560, window.innerHeight - 120);
            let left = rect.left + rect.width / 2 - panelWidth / 2;
            left = Math.max(8, Math.min(window.innerWidth - panelWidth - 8, left));
            let bottom = window.innerHeight - rect.top + 12;
            if (rect.top < panelHeight + 24) {
                bottom = Math.max(8, window.innerHeight - rect.bottom - panelHeight - 12);
            }
            aiPanel.style.right = 'auto';
            aiPanel.style.left = left + 'px';
            aiPanel.style.bottom = bottom + 'px';
        }

        function showTypingIndicator() {
            const row = document.createElement('div');
            row.className = 'ai-msg-row bot';
            row.id = 'ai-typing-indicator';
            const avatar = document.createElement('span');
            avatar.className = 'ai-bot-avatar';
            avatar.innerHTML = '<i class="fas fa-robot"></i>';
            const bubble = document.createElement('div');
            bubble.className = 'ai-typing';
            bubble.innerHTML = '<span></span><span></span><span></span>';
            row.appendChild(avatar);
            row.appendChild(bubble);
            aiChatBox.appendChild(row);
            aiChatBox.scrollTop = aiChatBox.scrollHeight;
        }

        function hideTypingIndicator() {
            document.getElementById('ai-typing-indicator')?.remove();
        }

        function collectPageContext() {
            const productName = document.querySelector('.product-title-modern')?.textContent?.trim() || '';
            const productPrice = document.querySelector('.product-price-modern')?.textContent?.trim() || '';
            const productDescription = document.querySelector('#desc-content')?.textContent?.trim()?.slice(0, 1500) || '';

            return {
                title: document.title || '',
                url: window.location.href,
                product_name: productName,
                product_price: productPrice,
                product_description: productDescription
            };
        }

        aiButton?.addEventListener('click', function() {
            aiPanel.classList.toggle('open');
            const isOpen = aiPanel.classList.contains('open');
            aiPanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            if (isOpen) {
                positionPanel();
                setTimeout(() => aiChatInput?.focus(), 100);
            }
        });

        aiClose?.addEventListener('click', function() {
            aiPanel.classList.remove('open');
            aiPanel.setAttribute('aria-hidden', 'true');
        });

        aiNew?.addEventListener('click', function() {
            if (aiHistory.length === 0) return;
            aiConfirm({
                title: 'Bắt đầu cuộc trò chuyện mới?',
                text: 'Lịch sử chat hiện tại sẽ bị xóa và không thể khôi phục.',
                icon: 'fa-rotate',
                okText: 'Xóa & bắt đầu lại',
                okStyle: 'danger',
                cancelText: 'Huỷ bỏ'
            }).then(ok => { if (ok) resetConversation(false); });
        });

        function aiConfirm(opts) {
            return new Promise(resolve => {
                const backdrop = document.getElementById('ai-modal-backdrop');
                const iconEl = document.getElementById('ai-modal-icon');
                const titleEl = document.getElementById('ai-modal-title');
                const textEl = document.getElementById('ai-modal-text');
                const okBtn = document.getElementById('ai-modal-ok');
                const cancelBtn = document.getElementById('ai-modal-cancel');
                if (!backdrop) { resolve(window.confirm(opts.text || '')); return; }

                titleEl.textContent = opts.title || 'Xác nhận';
                textEl.textContent = opts.text || '';
                iconEl.innerHTML = '<i class="fas ' + (opts.icon || 'fa-circle-question') + '"></i>';
                okBtn.textContent = opts.okText || 'Đồng ý';
                cancelBtn.textContent = opts.cancelText || 'Huỷ';
                okBtn.className = 'ai-modal-btn ' + (opts.okStyle === 'danger' ? 'ai-modal-btn-danger' : 'ai-modal-btn-confirm');

                backdrop.classList.add('open');
                backdrop.setAttribute('aria-hidden', 'false');

                const cleanup = (result) => {
                    backdrop.classList.remove('open');
                    backdrop.setAttribute('aria-hidden', 'true');
                    okBtn.removeEventListener('click', onOk);
                    cancelBtn.removeEventListener('click', onCancel);
                    backdrop.removeEventListener('click', onBackdrop);
                    document.removeEventListener('keydown', onKey);
                    resolve(result);
                };
                const onOk = () => cleanup(true);
                const onCancel = () => cleanup(false);
                const onBackdrop = (e) => { if (e.target === backdrop) cleanup(false); };
                const onKey = (e) => {
                    if (e.key === 'Escape') cleanup(false);
                    if (e.key === 'Enter') cleanup(true);
                };

                okBtn.addEventListener('click', onOk);
                cancelBtn.addEventListener('click', onCancel);
                backdrop.addEventListener('click', onBackdrop);
                document.addEventListener('keydown', onKey);
                setTimeout(() => okBtn.focus(), 50);
            });
        }

        renderHistory();

        aiChatForm?.addEventListener('submit', async function(event) {
            event.preventDefault();
            const message = aiChatInput.value.trim();
            if (!message) {
                return;
            }

            if (countUserTurns() >= AI_MAX_TURNS) {
                resetConversation(true);
                appendInfo('Đã đạt tối đa ' + AI_MAX_TURNS + ' lượt, mình bắt đầu lại cuộc trò chuyện mới nhé!');
            }

            appendMessage(message, 'user');
            aiChatInput.value = '';
            aiChatInput.disabled = true;
            aiChatSubmit.disabled = true;
            showTypingIndicator();

            try {
                const response = await fetch("{{ route('ai.consult') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        message: message,
                        history: aiHistory,
                        page_context: collectPageContext()
                    })
                });

                const data = await response.json().catch(() => ({}));
                hideTypingIndicator();

                if (!response.ok || !data.success) {
                    appendMessage(data.message || 'Hệ thống AI tạm thời gặp lỗi. Vui lòng thử lại sau.', 'bot');
                    return;
                }

                const reply = data.reply || 'Xin lỗi, mình chưa có câu trả lời phù hợp.';
                appendMessage(reply, 'bot');
                appendSuggestedProducts(data.suggested_products);

                aiHistory.push({ role: 'user', text: message });
                aiHistory.push({ role: 'bot', text: reply });
                saveHistory();
                updateMeta();
            } catch (error) {
                hideTypingIndicator();
                appendMessage('Không thể kết nối đến AI. Vui lòng thử lại sau.', 'bot');
            } finally {
                aiChatInput.disabled = false;
                aiChatSubmit.disabled = false;
                aiChatInput.focus();
            }
        });

        if (typeof window.showCartToast !== 'function') {
            window.showCartToast = function(msg, success) {
                var el = document.getElementById('globalCartToast');
                var body = document.getElementById('globalCartToastMsg');
                if (!el || !body || typeof bootstrap === 'undefined') return;
                body.textContent = msg;
                el.classList.remove('text-bg-success', 'text-bg-danger');
                el.classList.add(success === false ? 'text-bg-danger' : 'text-bg-success');
                bootstrap.Toast.getOrCreateInstance(el, { delay: 2500 }).show();
            };
        }

        window.updateCartBadge = function(count) {
            var badge = document.getElementById('cart-badge');
            if (!badge) return;
            badge.textContent = count;
            badge.style.display = count > 0 ? '' : 'none';
            badge.classList.remove('cart-badge-bump');
            void badge.offsetWidth;
            badge.classList.add('cart-badge-bump');
        };

        document.querySelectorAll('.btn-add-to-cart').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var productId = btn.getAttribute('data-product-id');
                var imgId = btn.getAttribute('data-image-id');
                var img = imgId ? document.getElementById(imgId) : null;
                var cartIcon = document.querySelector('.fa-shopping-cart');
                var buyNow = btn.getAttribute('data-buy-now') === '1';
                var quantity = 1;
                var qtyInput = document.getElementById('quantity');
                if (qtyInput) quantity = parseInt(qtyInput.value) || 1;

                // Chống double-click
                if (btn.dataset.loading === '1') return;
                btn.dataset.loading = '1';
                var originalHtml = btn.innerHTML;
                btn.disabled = true;

                // Flying image animation
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
                    flyingImg.style.pointerEvents = 'none';
                    flyingImg.style.transition = 'all 0.8s cubic-bezier(.4,2,.6,1)';
                    document.body.appendChild(flyingImg);

                    setTimeout(function() {
                        flyingImg.style.left = cartRect.left + 'px';
                        flyingImg.style.top = cartRect.top + 'px';
                        flyingImg.style.width = '30px';
                        flyingImg.style.height = '30px';
                        flyingImg.style.opacity = 0.3;
                    }, 10);

                    setTimeout(function() { flyingImg.remove(); }, 900);
                }

                var body = { product_id: productId, quantity: quantity };
                if (buyNow) body.buy_now = 1;

                fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    btn.dataset.loading = '0';
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;

                    if (data && data.success) {
                        if (typeof data.cart_count !== 'undefined') {
                            window.updateCartBadge(data.cart_count);
                        }
                        if (buyNow) {
                            window.location.href = "{{ route('checkout') }}";
                            return;
                        }
                        if (typeof showCartToast === 'function') {
                            showCartToast('Đã thêm vào giỏ hàng!', true);
                        }
                    } else {
                        if (typeof showCartToast === 'function') {
                            showCartToast((data && data.message) || 'Thêm vào giỏ hàng thất bại!', false);
                        }
                    }
                })
                .catch(function() {
                    btn.dataset.loading = '0';
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    if (typeof showCartToast === 'function') {
                        showCartToast('Có lỗi xảy ra, vui lòng thử lại!', false);
                    }
                });
            });
        });
    });
    </script>
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        if (typeof particlesJS !== 'undefined') {
            particlesJS('particles-js', {
              "particles": {
                "number": { "value": 70, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#94a3b8" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": false },
                "size": { "value": 3, "random": true },
                "line_linked": {
                  "enable": true,
                  "distance": 150,
                  "color": "#cbd5e1",
                  "opacity": 0.4,
                  "width": 1
                },
                "move": {
                  "enable": true,
                  "speed": 1.5,
                  "direction": "none",
                  "random": true,
                  "straight": false,
                  "out_mode": "out",
                  "bounce": false,
                }
              },
              "interactivity": {
                "detect_on": "window",
                "events": {
                  "onhover": { "enable": true, "mode": "grab" },
                  "onclick": { "enable": true, "mode": "push" },
                  "resize": true
                },
                "modes": {
                  "grab": { "distance": 180, "line_linked": { "opacity": 0.8, "color": "#6366f1" } },
                  "push": { "particles_nb": 3 }
                }
              },
              "retina_detect": true
            });
        }
    </script>
    @stack('scripts')
</body>
</html> 