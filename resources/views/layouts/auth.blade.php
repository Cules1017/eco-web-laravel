<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        /* Editorial Split Layout */
        .auth-split-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .auth-image-side {
            flex: 1;
            /* Premium fashion/lifestyle image */
            background: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            position: relative;
            display: none;
        }
        
        .auth-image-side::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.1), rgba(0,0,0,0.6));
        }

        .auth-quote {
            position: absolute;
            bottom: 15%;
            left: 10%;
            color: white;
            z-index: 10;
            max-width: 80%;
        }

        .auth-quote h2 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .auth-quote p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 400px;
        }
        
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
        }
        
        .auth-form-container {
            width: 100%;
            max-width: 420px;
        }
        
        @media (min-width: 992px) {
            .auth-image-side {
                display: block;
            }
        }
        
        /* Input styling requested: no border, bg-black/5, thin border on focus */
        .custom-input {
            background-color: rgba(0, 0, 0, 0.05) !important;
            border: 1px solid transparent !important;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: none !important;
            width: 100%;
        }
        
        .custom-input:focus {
            background-color: white !important;
            border-color: rgba(0,0,0,0.8) !important;
            outline: none;
        }
        
        /* CTA button: Solid black, rounded-full */
        .btn-cta-black {
            background-color: #000;
            color: #fff;
            border-radius: 9999px !important;
            padding: 14px 24px;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
            width: 100%;
            font-size: 1.05rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
        }
        
        .btn-cta-black:hover {
            background-color: #333;
            color: #fff;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #222;
        }

        .auth-header {
            margin-bottom: 2.5rem;
        }

        .auth-header h1 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            color: #000;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            color: #666;
            font-size: 1rem;
        }

        .back-home {
            position: absolute;
            top: 2rem;
            right: 2.5rem;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-home:hover {
            color: #000;
        }
        
        .auth-brand-logo {
            margin-bottom: 2rem;
            display: inline-block;
        }
        
        .auth-brand-logo img {
            max-height: 40px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-split-layout">
        <!-- 50% Image Side -->
        <div class="auth-image-side">
            <div class="auth-quote">
                <h2>Phong cách<br>Định hình tương lai</h2>
                <p>Khám phá bộ sưu tập mới nhất và nâng tầm phong cách cá nhân của bạn cùng chúng tôi.</p>
            </div>
        </div>
        <!-- 50% Form Side -->
        <div class="auth-form-side">
            <a href="{{ url('/') }}" class="back-home"><i class="fas fa-arrow-left"></i> Trang chủ</a>
            
            <div class="auth-form-container">
                @php
                    $logoRaw = \App\Models\Setting::getValue('site_logo');
                    $logo = null;
                    if ($logoRaw) {
                        $logo = str_starts_with($logoRaw, 'http') ? $logoRaw : asset('storage/' . ltrim($logoRaw, '/'));
                    }
                @endphp
                
                @if($logo)
                    <div class="auth-brand-logo">
                        <img src="{{ $logo }}" alt="{{ config('app.name') }}">
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
