@extends('layouts.admin')

@section('title', 'Cấu hình website')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">Cấu hình website</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="site_name">Tên website</label>
                            <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name']->value ?? '') }}" required>
                            @error('site_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="site_description">Mô tả website</label>
                            <textarea name="site_description" id="site_description" class="form-control @error('site_description') is-invalid @enderror" rows="3">{{ old('site_description', $settings['site_description']->value ?? '') }}</textarea>
                            @error('site_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="logo">Logo website</label><br>
                            @if(!empty($settings['site_logo']->value))
                                <img src="{{ asset('storage/' . $settings['site_logo']->value) }}" alt="Logo" style="max-height: 80px;" class="mb-2">
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control-file @error('logo') is-invalid @enderror">
                            @error('logo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 