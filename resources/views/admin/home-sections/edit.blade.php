@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Section</h3>
                </div>
                <form action="{{ route('admin.home-sections.update', $homeSection) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên section</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $homeSection->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Tiêu đề hiển thị</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $homeSection->title) }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $homeSection->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Loại section</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Chọn loại section</option>
                                <option value="1" {{ old('type', $homeSection->type) == 1 ? 'selected' : '' }}>Sản phẩm nổi bật</option>
                                <option value="2" {{ old('type', $homeSection->type) == 2 ? 'selected' : '' }}>Sản phẩm mới</option>
                                <option value="3" {{ old('type', $homeSection->type) == 3 ? 'selected' : '' }}>Sản phẩm theo danh mục</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="categories-group" style="display: none;">
                            <label for="list_categories">Chọn danh mục</label>
                            <select class="form-control select2 @error('list_categories') is-invalid @enderror" id="list_categories" name="list_categories" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('list_categories', $homeSection->category_ids)) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('list_categories')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="num">Số lượng sản phẩm hiển thị</label>
                            <input type="number" class="form-control @error('num') is-invalid @enderror" id="num" name="num" value="{{ old('num', $homeSection->num) }}" min="1" max="20" required>
                            @error('num')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $homeSection->order) }}" min="0" required>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $homeSection->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Hiển thị section</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.home-sections.index') }}" class="btn btn-default">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Khởi tạo Select2
    $('.select2').select2();

    // Xử lý hiển thị/ẩn danh mục khi chọn loại section
    $('#type').change(function() {
        if ($(this).val() == '3') {
            $('#categories-group').show();
        } else {
            $('#categories-group').hide();
        }
    });

    // Trigger change event khi load trang
    $('#type').trigger('change');
});
</script>
@endpush 