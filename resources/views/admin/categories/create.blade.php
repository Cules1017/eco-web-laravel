@extends('layouts.admin')

@section('title', __('messages.add_new_category'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_new_category') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="name">{{ __('messages.category_name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">{{ __('messages.category_description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="parent_id">{{ __('messages.parent_category') }}</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" name="parent_id">
                                <option value="">{{ __('messages.no_parent') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('messages.category_image') }}</label>
                            <div>
                                <input type="radio" id="upload_type_file" name="upload_type" value="file" checked>
                                <label for="upload_type_file">Tải file</label>
                                <input type="radio" id="upload_type_url" name="upload_type" value="url">
                                <label for="upload_type_url">Nhập link/chọn ảnh</label>
                            </div>
                            <div id="upload_file_block">
                                <input type="file" class="form-control mt-2 @error('image_file') is-invalid @enderror" name="image_file">
                                @error('image_file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div id="upload_url_block" style="display:none;">
                                <div class="input-group mt-2">
                                    <input id="image" class="form-control @error('image') is-invalid @enderror" type="text" name="image" value="{{ old('image') }}">
                                    <span class="input-group-btn">
                                        <button id="lfm" data-input="image" data-preview="holder" class="btn btn-secondary" type="button">
                                            <i class="fa fa-picture-o"></i> {{ __('messages.choose_image') }}
                                        </button>
                                    </span>
                                </div>
                                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="order">{{ __('messages.display_order') }}</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                       id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('messages.active') }}</label>
                                @error('is_active')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
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
</script>
@endpush 