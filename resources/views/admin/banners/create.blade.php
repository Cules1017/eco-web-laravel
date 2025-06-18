@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Thêm Banner</h1>
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Hình ảnh <span class="text-danger">*</span></label>
            <div>
                <input type="radio" id="upload_type_file" name="upload_type" value="file" checked>
                <label for="upload_type_file">Tải file</label>
                <input type="radio" id="upload_type_url" name="upload_type" value="url">
                <label for="upload_type_url">Nhập link/chọn ảnh</label>
            </div>
            <div id="upload_file_block">
                <input type="file" name="image_file" class="form-control mt-2">
            </div>
            <div id="upload_url_block" style="display:none;">
                <input type="text" name="image_url" class="form-control mt-2" placeholder="Nhập link ảnh hoặc chọn ảnh từ file manager">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Link</label>
            <input type="text" name="link" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Thứ tự</label>
            <input type="number" name="order" class="form-control" value="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileRadio = document.getElementById('upload_type_file');
        const urlRadio = document.getElementById('upload_type_url');
        const fileBlock = document.getElementById('upload_file_block');
        const urlBlock = document.getElementById('upload_url_block');
        function toggleUploadType() {
            if (fileRadio.checked) {
                fileBlock.style.display = '';
                urlBlock.style.display = 'none';
            } else {
                fileBlock.style.display = 'none';
                urlBlock.style.display = '';
            }
        }
        fileRadio.addEventListener('change', toggleUploadType);
        urlRadio.addEventListener('change', toggleUploadType);
    });
</script>
@endpush 