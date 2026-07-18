@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chỉnh sửa sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Số lượng</label>
                        <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-control shadow-sm rounded bg-white @error('category_id') is-invalid @enderror" required style="height:38px;">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Ảnh sản phẩm chính</label>
                    <div class="mb-2">
                        @if($product->image)
                            <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height: 80px;">
                        @endif
                    </div>
                    <input type="file" class="form-control mt-2 @error('image') is-invalid @enderror" name="image">
                    @error('image')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group mb-4">
                    <label class="fw-bold mb-3 d-block">Ảnh phụ (Gallery)</label>
                    
                    @if($product->images && $product->images->count() > 0)
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @foreach($product->images as $img)
                            <div class="position-relative border rounded shadow-sm overflow-hidden bg-white" style="width: 110px; height: 110px;">
                                <img src="{{ Str::startsWith($img->image_path, ['http://', 'https://']) ? $img->image_path : asset('storage/' . $img->image_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:4px; right:4px; border-radius:50%; width: 24px; height: 24px; padding:0; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn xóa ảnh này?')) document.getElementById('delete-image-{{ $img->id }}').submit();" title="Xóa ảnh này"><i class="fas fa-times" style="font-size: 12px;"></i></button>
                            </div>
                        @endforeach
                    </div>
                    @endif

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
                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Hiện sản phẩm</label>
                        @error('is_active')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_featured') is-invalid @enderror" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        @error('is_featured')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu thay đổi</button>
            </form>
            @foreach($product->images as $img)
                <form id="delete-image-{{ $img->id }}" action="{{ route('admin.products.deleteImage', $img->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 250,
        menubar: false,
        plugins: 'lists link image code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
        branding: false,
        content_style: 'body { font-family:Roboto,sans-serif; font-size:14px }'
    });

    // Handle Image Gallery Upload Preview & AJAX
    const imagesInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('new-images-preview');
    const dropZone = document.getElementById('image-upload-zone');
    const galleryContainer = document.querySelector('.d-flex.flex-wrap.gap-3.mb-4') || previewContainer;
    const csrfToken = document.querySelector('input[name="_token"]').value;

    if (imagesInput && galleryContainer) {
        imagesInput.addEventListener('change', async function() {
            if (this.files && this.files.length > 0) {
                const filesArray = Array.from(this.files);
                
                for (let i = 0; i < filesArray.length; i++) {
                    let file = filesArray[i];
                    if (file.type.startsWith('image/')) {
                        // Create a placeholder with loading spinner
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'position-relative border rounded shadow-sm overflow-hidden bg-white d-flex align-items-center justify-content-center';
                        imgDiv.style.width = '110px';
                        imgDiv.style.height = '110px';
                        imgDiv.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                        
                        galleryContainer.appendChild(imgDiv);
                        
                        // Upload via AJAX
                        const formData = new FormData();
                        formData.append('_token', csrfToken);
                        formData.append('image', file);
                        
                        try {
                            const response = await fetch("{{ route('admin.products.uploadGallery', $product->id) }}", {
                                method: 'POST',
                                body: formData
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                imgDiv.className = 'position-relative border rounded shadow-sm overflow-hidden bg-white';
                                imgDiv.innerHTML = `
                                    <img src="${data.url}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    <span class="badge bg-success position-absolute" style="top:4px; left:4px;">Mới</span>
                                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:4px; right:4px; border-radius:50%; width: 24px; height: 24px; padding:0; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);" onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn xóa ảnh này?')) document.getElementById('delete-image-${data.id}').submit();" title="Xóa ảnh này"><i class="fas fa-times" style="font-size: 12px;"></i></button>
                                `;
                                
                                // Create the hidden delete form for this new image
                                const deleteForm = document.createElement('form');
                                deleteForm.id = `delete-image-${data.id}`;
                                deleteForm.action = `/admin/products/image/${data.id}`;
                                deleteForm.method = 'POST';
                                deleteForm.style.display = 'none';
                                deleteForm.innerHTML = `
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                `;
                                document.body.appendChild(deleteForm);
                            } else {
                                imgDiv.remove();
                                alert('Có lỗi xảy ra khi tải ảnh lên!');
                            }
                        } catch (error) {
                            imgDiv.remove();
                            alert('Lỗi kết nối khi tải ảnh!');
                        }
                    }
                }
                
                // Clear the input so the same files can be selected again
                this.value = '';
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
            imagesInput.files = files; 
            const event = new Event('change');
            imagesInput.dispatchEvent(event);
        }, false);
    }
</script>
@endpush 