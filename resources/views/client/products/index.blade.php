@extends('layouts.eshopper')

@section('title', request('featured') ? __('messages.featured_products') : __('messages.products'))

@section('content')
<div class="container vs-page-wrapper">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ request('featured') ? __('messages.featured_products') : __('messages.products') }}
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar lọc -->
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header">{{ __('messages.price_range') }}</div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('featured'))
                            <input type="hidden" name="featured" value="1">
                        @endif
                        <div class="mb-3">
                            <label for="min_price" class="form-label">{{ __('messages.min_price') }}</label>
                            <input type="number" class="form-control" id="min_price" name="min_price"
                                   value="{{ request('min_price') }}" min="0" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="max_price" class="form-label">{{ __('messages.max_price') }}</label>
                            <input type="number" class="form-control" id="max_price" name="max_price"
                                   value="{{ request('max_price') }}" min="0" placeholder="0">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> {{ __('messages.apply_filter') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">{{ __('messages.categories') }}</div>
                <div class="card-body p-2">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.index', request('featured') ? ['featured' => 1] : []) }}"
                           class="list-group-item list-group-item-action rounded mb-1 {{ !request('category') ? 'active' : '' }}">
                            {{ __('messages.all_categories') }}
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('products.index', array_merge(['category' => $category->slug], request('featured') ? ['featured' => 1] : [])) }}"
                           class="list-group-item list-group-item-action rounded mb-1 {{ request('category') == $category->slug ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Lưới sản phẩm -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h2 class="vs-section-title mb-0">
                    {{ request('featured') ? __('messages.featured_products') : __('messages.products') }}
                    <small class="text-muted fs-6 fw-normal">({{ $products->total() }})</small>
                </h2>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort me-1"></i> {{ __('messages.sort_by') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item {{ request('sort') == 'price_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">{{ __('messages.price_low_to_high') }}</a></li>
                        <li><a class="dropdown-item {{ request('sort') == 'price_desc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">{{ __('messages.price_high_to_low') }}</a></li>
                        <li><a class="dropdown-item {{ request('sort') == 'name_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}">{{ __('messages.name_a_to_z') }}</a></li>
                        <li><a class="dropdown-item {{ request('sort') == 'name_desc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}">{{ __('messages.name_z_to_a') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="row g-3">
                @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    @include('client.products.ProductItem', ['product' => $product])
                </div>
                @empty
                <div class="col-12">
                    <div class="vs-empty-state">
                        <div class="vs-empty-icon"><i class="fas fa-box-open"></i></div>
                        <h5 class="mb-2">{{ __('messages.no_products') }}</h5>
                        <p class="text-muted">Thử chọn danh mục hoặc khoảng giá khác.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-rotate-left me-1"></i> Xoá bộ lọc
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
