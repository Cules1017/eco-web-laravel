@extends('layouts.eshopper')

@section('title', __('messages.addresses'))

@section('content')
<div class="container vs-page-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="vs-section-title mb-0">{{ __('messages.addresses') }}</h1>
        <a href="{{ route('addresses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> {{ __('messages.add_new_address') }}
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="vs-empty-state">
            <div class="vs-empty-icon"><i class="fas fa-location-dot"></i></div>
            <h4 class="mb-2">{{ __('messages.no_addresses') }}</h4>
            <p class="text-muted">Thêm địa chỉ giao hàng để thanh toán nhanh hơn.</p>
            <a href="{{ route('addresses.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-1"></i> {{ __('messages.add_new_address') }}
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($addresses as $address)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 address-card {{ $address->is_default ? 'address-card-default' : '' }}">
                        <div class="card-body d-flex flex-column">
                            @if($address->is_default)
                                <span class="badge bg-primary align-self-start mb-2">
                                    <i class="fas fa-star me-1"></i>{{ __('messages.default_address') }}
                                </span>
                            @endif

                            <h5 class="mb-1">{{ $address->full_name }}</h5>
                            <div class="text-muted small mb-2">
                                <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                            </div>
                            <p class="mb-3 flex-grow-1">
                                <i class="fas fa-location-dot text-primary me-1"></i>{{ $address->full_address }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-pen me-1"></i>{{ __('messages.edit') }}
                                    </a>
                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('{{ __('messages.delete_address_confirm') }}')">
                                            <i class="fas fa-trash me-1"></i>{{ __('messages.delete') }}
                                        </button>
                                    </form>
                                </div>

                                @if(!$address->is_default)
                                    <form action="{{ route('addresses.default', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none">
                                            {{ __('messages.set_as_default') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('styles')
<style>
.address-card { border-radius: 14px; transition: box-shadow .2s, transform .2s; }
.address-card:hover { box-shadow: 0 10px 24px rgba(99, 102, 241, 0.12); transform: translateY(-2px); }
.address-card-default {
    border: 2px solid var(--vs-primary, #6366f1);
    background: linear-gradient(180deg, #eef2ff 0%, #fff 40%);
}
</style>
@endpush
@endsection
