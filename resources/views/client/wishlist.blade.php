@extends('layouts.eshopper')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <h1 class="mb-0 fw-normal text-uppercase fs-3">Sản phẩm yêu thích</h1>
    </div>

    <!-- Container cho Wishlist JS render -->
    <div id="wishlist-container" style="min-height: 400px;">
        <div class="text-center py-5 text-muted">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p>Đang tải danh sách yêu thích...</p>
        </div>
    </div>
</div>
@endsection
