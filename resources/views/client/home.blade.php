@extends('layouts.eshopper')

@section('title', __('messages.home'))

@section('content')
@php
    $banners = \App\Models\Banner::where('is_active', true)->orderBy('order')->get();
    $featuredCategories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('order')->get();
@endphp
@if($banners->count())
<div class="mb-5" style="padding-top: 100px;">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $i => $banner)
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner rounded-4 shadow-lg overflow-hidden" style="max-height:420px;">
            @foreach($banners as $i => $banner)
            <div class="carousel-item{{ $i === 0 ? ' active' : '' }}">
                <a href="{{ $banner->link ?: '#' }}">
                    <img src="{{ Str::startsWith($banner->image, ['http://', 'https://']) ? $banner->image : asset('storage/'.$banner->image) }}" class="d-block w-100" alt="{{ $banner->title }}" style="object-fit:cover; width:100%; height:420px;">
                </a>
                <div class="position-absolute" style="top:30px; left:40px; text-align:left; max-width: 50%;">
                    <h3 class="fw-bold text-white mb-2" style="text-shadow: 1px 1px 8px #000; background:rgba(0,0,0,0.0); display:inline-block;">{{ $banner->title }}</h3>
                    @if($banner->description)
                        <br>
                        <span class="text-white" style="font-size:1.1rem; text-shadow: 1px 1px 8px #000; background:rgba(0,0,0,0.0); display:inline-block;">{{ $banner->description }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<script>console.log('layout loaded');</script>
{{-- <div class="row align-items-center mb-5">
    <div class="col-md-7">
        <h1 class="display-4 fw-bold mb-3">{{ __('messages.welcome') }}</h1>
        <p class="lead mb-4">{{ __('messages.discover_products') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">{{ __('messages.shop_now') }}</a>
    </div>
    <div class="col-md-5 text-center">
        <img src="https://themewagon.github.io/eshopper/img/hero.png" alt="Hero" class="img-fluid">
    </div>
</div> --}}

<h2 class="mb-4">{{ __('messages.featured_categories') }}</h2>
<div class="featured-categories-scroll mb-5 position-relative">
    <button class="scroll-btn left d-none" type="button" aria-label="Scroll left">
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#fff"/><polyline points="19,9 13,16 19,23" fill="none" stroke="#aaa" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <button class="scroll-btn right d-none" type="button" aria-label="Scroll right">
        <svg width="32" height="32" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16" fill="#fff"/><polyline points="13,9 19,16 13,23" fill="none" stroke="#aaa" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <div class="featured-cate-grid d-grid flex-nowrap overflow-auto gap-3 py-2" style="grid-auto-flow: column; grid-template-rows: repeat(2, 1fr);">
        @foreach($featuredCategories as $category)
            <div class="text-center flex-shrink-0" style="width:120px;">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none text-dark d-block h-100">
                    <div class="rounded-4 shadow-sm mx-auto mb-2 bg-white d-flex align-items-center justify-content-center" style="width:90px; height:90px; overflow:hidden;">
                        <img src="{{ $category->image ? $category->image : 'https://via.placeholder.com/90x90?text=Category' }}" alt="{{ $category->name }}" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="fw-semibold small" style="min-height:38px;">{{ $category->name }}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>




@foreach($sections as $section)
<section class="product-section" id="{{ $section->slug }}">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h2>{{ $section->title }}</h2>
                @if($section->description)
                    <p>{{ $section->description }}</p>
                @endif
            </div>
            @php
                $seeMoreUrl = '#';
                if ($section->type == 1) {
                    $seeMoreUrl = route('products.index', ['featured' => 1]);
                } elseif ($section->type == 2) {
                    $seeMoreUrl = route('products.index');
                } elseif ($section->type == 3 && $section->list_categories) {
                    $firstCatId = explode(',', $section->list_categories)[0] ?? null;
                    $cat = $firstCatId ? \App\Models\Category::find($firstCatId) : null;
                    $seeMoreUrl = $cat ? route('products.index', ['category' => $cat->slug]) : route('products.index');
                }
            @endphp
            <a href="{{ $seeMoreUrl }}" class="btn btn-outline-primary btn-sm">Xem thêm</a>
        </div>
        <div class="row">
            @foreach($section->getProducts() as $product)
                <div class="col-md-3 col-sm-6 mb-4 flex justify-center">
                    @include('client.products.ProductItem', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach
@endsection

<style>
    .eshopper-logo { max-height: 50px; }
    .dropdown-menu { z-index: 1051 !important; }
    .navbar, .container-fluid, .container { position: relative; }
</style> 

<style>
    /* Tuỳ chỉnh indicator cho carousel */
    .carousel-indicators [data-bs-target] {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        background-color: rgba(176, 176, 172, 0.4); /* xám nhạt trong suốt */
        border: none;
        margin: 0 6px;
        opacity: 1;
        transition: none;
        box-shadow: none;
        cursor: pointer;
    }
    .carousel-indicators .active {
        background-color: rgba(50, 205, 50, 0.5); /* xanh lá cây nhạt trong suốt */
        width: 22px;
        height: 22px;
        border-radius: 6px;
        box-shadow: none;
        opacity: 1;
        transition: none;
    }
    .carousel-indicators {
        position: absolute;
        bottom: 23px; /* 18px + 5px nâng lên */
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
        margin: 0;
        padding: 0;
        pointer-events: auto;
    }
    .carousel-indicators [data-bs-target]:focus {
        outline: none;
        box-shadow: none;
    }
    .carousel-indicators [data-bs-target]:active {
        outline: none;
        box-shadow: none;
        background-color: rgba(50, 205, 50, 0.5);
    }
</style> 

<style>
    .featured-category-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        background: #fff;
    }
</style> 

<style>
    .featured-categories-scroll {
        background: #fcfcf7;
        border-radius: 16px;
        padding: 18px 0 8px 0;
        border: 1.5px solid #f5eecb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 2rem;
        position: relative;
    }
    .featured-cate-grid {
        display: grid !important;
        grid-auto-flow: column;
        grid-template-rows: repeat(2, 1fr);
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: #e0e0e0 #fcfcf7;
    }
    .featured-cate-grid::-webkit-scrollbar {
        height: 8px;
    }
    .featured-cate-grid::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 4px;
    }
    .scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: opacity 0.2s;
        opacity: 0.85;
    }
    .scroll-btn.left { left: -16px; }
    .scroll-btn.right { right: -16px; }
    .scroll-btn svg { display: block; }
    @media (max-width: 600px) {
        .featured-cate-grid .text-center { width: 90px !important; }
    }
</style>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.querySelector('.featured-cate-grid');
        const btnLeft = document.querySelector('.scroll-btn.left');
        const btnRight = document.querySelector('.scroll-btn.right');
        function updateScrollBtns() {
            if (!grid) return;
            const isOverflowing = grid.scrollWidth > grid.clientWidth;
            btnLeft.classList.toggle('d-none', !isOverflowing || grid.scrollLeft <= 0);
            btnRight.classList.toggle('d-none', !isOverflowing || grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 2);
        }
        if (grid && btnLeft && btnRight) {
            updateScrollBtns();
            grid.addEventListener('scroll', updateScrollBtns);
            window.addEventListener('resize', updateScrollBtns);
            btnLeft.addEventListener('click', () => {
                grid.scrollBy({ left: -grid.clientWidth/1.2, behavior: 'smooth' });
            });
            btnRight.addEventListener('click', () => {
                grid.scrollBy({ left: grid.clientWidth/1.2, behavior: 'smooth' });
            });
        }
    });
</script>
@endpush 

<style>
.hover-shadow-lg:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.13)!important; }
.hover-bg-success-dark:hover { background:#218838!important; }
</style> 

<style>
.btn.bg-transparent:hover, .btn.bg-transparent:focus { background:rgba(0,0,0,0.06)!important; }
</style> 