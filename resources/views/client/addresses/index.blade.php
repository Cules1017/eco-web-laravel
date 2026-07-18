@extends('layouts.eshopper')

@section('title', __('messages.addresses'))

@section('content')
<style>
    .address-card { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 20px; transition: transform 0.2s; }
    .address-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
</style>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-2">
        <h1 class="mb-0 fw-normal text-uppercase fs-3">{{ __('messages.addresses') }}</h1>
        <a href="{{ route('addresses.create') }}" class="btn btn-dark text-uppercase rounded-0 px-4 py-2">
            {{ __('messages.add_new_address') }}
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="text-center py-5">
            <h4 class="mb-3 fw-normal">{{ __('messages.no_addresses') }}</h4>
            <p class="text-muted mb-4">Thêm địa chỉ giao hàng để thanh toán nhanh hơn.</p>
            <a href="{{ route('addresses.create') }}" class="btn btn-dark text-uppercase rounded-0 px-5 py-3">
                {{ __('messages.add_new_address') }}
            </a>
        </div>
    @else
        <div class="d-flex flex-column">
            @foreach($addresses as $address)
                <div class="address-card d-flex flex-column flex-md-row justify-content-between {{ $address->is_default ? 'bg-light' : '' }}">
                    <div class="mb-4 mb-md-0">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h5 class="mb-0 fs-4">{{ $address->full_name }}</h5>
                            @if($address->is_default)
                                <span class="badge bg-dark text-white rounded-0 fw-normal px-2 py-1 small">
                                    {{ __('messages.default_address') }}
                                </span>
                            @endif
                        </div>
                        <div class="text-muted mb-3 fs-5">
                            {{ $address->phone }}
                        </div>
                        <div class="fs-5 lh-base text-dark" style="max-width: 600px;">
                            {{ $address->full_address }}
                        </div>
                    </div>

                    <div class="d-flex flex-column justify-content-start align-items-md-end gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('addresses.edit', $address) }}" class="text-dark text-decoration-none text-uppercase fw-bold pb-1 border-bottom border-dark small" style="margin-right: 15px;">
                                {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none text-uppercase fw-bold small"
                                        style="transition: all 0.3s ease;"
                                        onmouseover="this.style.textDecoration='underline'"
                                        onmouseout="this.style.textDecoration='none'"
                                        onclick="return confirm('{{ __('messages.delete_address_confirm') }}')">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>

                        @if(!$address->is_default)
                            <form action="{{ route('addresses.default', $address) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark text-uppercase rounded-0 px-3 py-1 small">
                                    {{ __('messages.set_as_default') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
