@extends('layouts.eshopper')

@section('title', request('featured') ? 'Featured Products' : 'Products')

@section('content')
<div class="container" style="padding-top: 100px;">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Price Range</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="mb-3">
                            <label for="min_price" class="form-label">Min Price</label>
                            <input type="number" class="form-control" id="min_price" name="min_price" 
                                   value="{{ request('min_price') }}" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="max_price" class="form-label">Max Price</label>
                            <input type="number" class="form-control" id="max_price" name="max_price" 
                                   value="{{ request('max_price') }}" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('products.index', request('featured') ? ['featured' => 1] : []) }}" 
                           class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            All Categories
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('products.index', array_merge(['category' => $category->slug], request('featured') ? ['featured' => 1] : [])) }}" 
                           class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            
        </div>

        <!-- Products Grid -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>{{ request('featured') ? 'Featured Products' : 'Products' }}</h2>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price: Low to High</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price: High to Low</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}">Name: A to Z</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}">Name: Z to A</a></li>
                    </ul>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 mb-4">
                    @include('client.products.ProductItem', ['product' => $product])
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Không tìm thấy sản phẩm nào.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush 