@extends('layouts.admin')

@section('title', __('messages.category_management'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{{ __('messages.category_management') }}</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('messages.add_new_category') }}
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif --}}

    <div class="card">
        <div class="card-body">
            <div class="card mb-4 p-3 shadow-sm border-0 bg-light">
                <form method="GET" class="row g-2 align-items-end mb-0">
                    <div class="col-auto d-flex flex-column">
                        <label for="parent_id" class="form-label mb-1 small">Danh mục cha</label>
                        <select name="parent_id" id="parent_id" class="form-select shadow-sm rounded bg-white" style="min-width:160px; max-width:200px; height:38px;">
                            <option value="">-- Tất cả --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto d-flex flex-column">
                        <label for="keyword" class="form-label mb-1 small">Từ khóa</label>
                        <input type="text" name="keyword" id="keyword" class="form-control shadow-sm rounded bg-white" placeholder="Nhập từ khóa..." value="{{ request('keyword') }}" style="min-width:180px; height:38px;">
                    </div>
                    <div class="col-auto pt-3">
                        <button type="submit" class="btn btn-outline-primary btn-sm px-4" style="height:38px;"><i class="fas fa-search me-1"></i> Lọc</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('messages.category_image') }}</th>
                            <th>{{ __('messages.category_name') }}</th>
                            <th>{{ __('messages.parent_category') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.display_order') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    @if($category->image)
                                        @php
                                            $isUrl = Str::startsWith($category->image, ['http://', 'https://']);
                                        @endphp
                                        <img src="{{ $isUrl ? $category->image : asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-height: 50px;">
                                    @else
                                        <span class="text-muted">{{ __('messages.no_image') }}</span>
                                    @endif
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->parent ? $category->parent->name : __('messages.no_parent') }}</td>
                                <td>
                                    <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                        {{ $category->is_active ? __('messages.active') : __('messages.inactive') }}
                                    </span>
                                </td>
                                <td>{{ $category->order }}</td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 