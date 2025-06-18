@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm Section mới</h3>
                </div>
                <form action="{{ route('admin.home-sections.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên section</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Tiêu đề hiển thị</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Loại section</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Chọn loại section</option>
                                <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Sản phẩm nổi bật</option>
                                <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Sản phẩm mới</option>
                                <option value="3" {{ old('type') == 3 ? 'selected' : '' }}>Sản phẩm theo danh mục</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="categories-group" style="display: none;">
                            <label for="list_categories_select">Chọn danh mục</label>
                            <select class="form-control select2 @error('list_categories') is-invalid @enderror" id="list_categories_select" multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="list_categories" id="list_categories" value="{{ old('list_categories') }}">
                            @error('list_categories')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="num">Số lượng sản phẩm hiển thị</label>
                            <input type="number" class="form-control @error('num') is-invalid @enderror" id="num" name="num" value="{{ old('num', 8) }}" min="1" max="20" required>
                            @error('num')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="order">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Hiển thị section</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
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
document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && typeof $().select2 === 'function') {
        $('.select2').select2();
    }

    var typeSelect = document.getElementById('type');
    var categoriesGroup = document.getElementById('categories-group');
    var selectBox = document.getElementById('list_categories_select');
    var hiddenInput = document.getElementById('list_categories');
    var form = selectBox.closest('form');

    function toggleCategoriesGroup() {
        if (typeSelect.value === '3') {
            categoriesGroup.style.display = '';
        } else {
            categoriesGroup.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleCategoriesGroup);
    toggleCategoriesGroup();

    // Khi submit form, lấy các giá trị đã chọn và nối lại thành chuỗi
    form.addEventListener('submit', function(e) {
        var selected = Array.from(selectBox.selectedOptions).map(opt => opt.value);
        hiddenInput.value = selected.join(',');
    });

    // Nếu có old value, set lại selected cho select2
    @if(old('list_categories'))
        var oldVals = "{{ old('list_categories') }}".split(',');
        for (var i = 0; i < selectBox.options.length; i++) {
            if (oldVals.includes(selectBox.options[i].value)) {
                selectBox.options[i].selected = true;
            }
        }
        if (window.$ && typeof $().select2 === 'function') {
            $('#list_categories_select').trigger('change');
        }
    @endif
});
</script>
@endpush 