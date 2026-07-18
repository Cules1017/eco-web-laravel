@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Thêm sản phẩm mới</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Số lượng</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" min="0" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-control shadow-sm rounded bg-white @error('category_id') is-invalid @enderror" required style="height:38px;">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Ảnh sản phẩm</label>
                    <div>
                        <input type="radio" id="upload_type_file" name="upload_type" value="file" checked>
                        <label for="upload_type_file">Tải file</label>
                        <input type="radio" id="upload_type_url" name="upload_type" value="url">
                        <label for="upload_type_url">Nhập link/chọn ảnh</label>
                    </div>
                    <div id="upload_file_block">
                        <input type="file" class="form-control mt-2 @error('image') is-invalid @enderror" name="image">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="upload_url_block" style="display:none;">
                        <div class="input-group mt-2">
                            <input id="image_url" class="form-control @error('image_url') is-invalid @enderror" type="text" name="image_url" value="{{ old('image_url') }}">
                            <span class="input-group-btn">
                                <button id="lfm" data-input="image_url" data-preview="holder" class="btn btn-secondary" type="button">
                                    <i class="fa fa-picture-o"></i> Chọn ảnh
                                </button>
                            </span>
                        </div>
                        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        @error('image_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="fw-bold mb-3 d-block">Ảnh phụ (Gallery)</label>
                    
                    <!-- Upload New Images -->
                    <div id="image-upload-zone" class="rounded text-center p-5 position-relative bg-light" style="border: 2px dashed #adb5bd; cursor: pointer; transition: all 0.2s ease;">
                        <input type="file" id="images-input" name="images[]" multiple class="position-absolute w-100 h-100" style="top:0; left:0; opacity:0; cursor:pointer;" accept="image/*">
                        <div class="py-3">
                            <i class="fas fa-cloud-upload-alt text-primary mb-3" style="font-size: 3.5rem;"></i>
                            <h5 class="text-dark fw-bold mb-2">Kéo thả ảnh vào đây hoặc click để tải lên</h5>
                            <p class="text-muted small mb-0">Hỗ trợ định dạng JPG, PNG, GIF (Tối đa 2MB/ảnh). Có thể chọn nhiều ảnh cùng lúc.</p>
                        </div>
                    </div>
                    @error('images.*')<span class="invalid-feedback d-block mt-2">{{ $message }}</span>@enderror

                    <!-- Preview newly selected images -->
                    <div id="new-images-preview" class="d-flex flex-wrap gap-3 mt-4"></div>
                </div>
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hiện sản phẩm</label>
                        @error('is_active')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_featured') is-invalid @enderror" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        @error('is_featured')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu sản phẩm</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    $('#lfm').filemanager('image');
    $('input[name=upload_type]').change(function() {
        if ($(this).val() === 'file') {
            $('#upload_file_block').show();
            $('#upload_url_block').hide();
        } else {
            $('#upload_file_block').hide();
            $('#upload_url_block').show();
        }
    });
    tinymce.init({
        selector: '#description',
        height: 250,
        menubar: false,
        plugins: 'lists link image code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
        branding: false,
        content_style: 'body { font-family:Roboto,sans-serif; font-size:14px }'
    });

    // Handle Image Gallery Upload Preview
    const imagesInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('new-images-preview');
    const dropZone = document.getElementById('image-upload-zone');

    if (imagesInput && previewContainer) {
        imagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = ''; // Clear old previews
            if (this.files && this.files.length > 0) {
                Array.from(this.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'position-relative border rounded shadow-sm overflow-hidden bg-white';
                            imgDiv.style.width = '110px';
                            imgDiv.style.height = '110px';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.width = '100%';
                            img.style.height = '100%';
                            img.style.objectFit = 'cover';
                            
                            const badge = document.createElement('span');
                            badge.className = 'badge bg-success position-absolute';
                            badge.style.top = '4px';
                            badge.style.left = '4px';
                            badge.innerText = 'Mới';
                            
                            imgDiv.appendChild(img);
                            imgDiv.appendChild(badge);
                            previewContainer.appendChild(imgDiv);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }

    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.style.borderColor = '#007bff';
                dropZone.style.backgroundColor = '#e9ecef';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.style.borderColor = '#adb5bd';
                dropZone.style.backgroundColor = '#f8f9fa';
            }, false);
        });
        
        dropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            imagesInput.files = files; // Assign files to input
            
            // Trigger change event manually
            const event = new Event('change');
            imagesInput.dispatchEvent(event);
        }, false);
    }
</script>
@endpush 