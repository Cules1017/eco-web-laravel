@extends('layouts.eshopper')

@section('title', __('messages.checkout'))

@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('messages.checkout') }}</h1>
    @if(empty($cart))
        <div class="alert alert-info">{{ __('messages.empty_cart') }}</div>
        <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('messages.continue_shopping') }}</a>
    @else
        <div class="row">
            <!-- Order Summary -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.order_summary') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.product') }}</th>
                                        <th class="text-center">{{ __('messages.quantity') }}</th>
                                        <th class="text-end">{{ __('messages.price') }}</th>
                                        <th class="text-end">{{ __('messages.subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($cart as $item)
                                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3 border rounded bg-white d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; overflow: hidden;">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $loop->index }}">
                                                            <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                                 alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                    <div class="text-truncate" style="max-width: 200px;">
                                                        @if(isset($item['slug']))
                                                            <a href="{{ route('products.show', $item['slug']) }}" class="text-decoration-none" target="_blank">
                                                                {{ $item['name'] }}
                                                            </a>
                                                        @else
                                                            {{ $item['name'] }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="imageModal{{ $loop->index }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $loop->index }}" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content bg-transparent border-0">
                                                      <div class="modal-body text-center p-0">
                                                        <img src="{{ Str::startsWith($item['image'], ['http://', 'https://']) ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                             alt="{{ $item['name'] }}" style="max-width: 100%; max-height: 80vh; border-radius: 8px;">
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-end">{{ number_format($item['price']) }}đ</td>
                                            <td class="text-end">{{ number_format($subtotal) }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($total) }}đ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping & Payment -->
            <div class="col-md-4">
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __('messages.shipping_address') }}</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus"></i> {{ __('messages.add_new_address') }}
                            </a>
                        </div>
                        <div class="card-body">
                            @php $hasDefault = $addresses->contains('is_default', true); @endphp
                            @foreach($addresses as $i => $address)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="shipping_address_id" 
                                        id="address_{{ $address->id }}" value="{{ $address->id }}"
                                        @if($address->is_default || (!$hasDefault && $i === 0)) checked @endif required>
                                    <label class="form-check-label" for="address_{{ $address->id }}">
                                        <strong>{{ $address->full_name }}</strong> - {{ $address->phone }}<br>
                                        {{ $address->address }}, {{ $address->ward_name }}, 
                                        {{ $address->district_name }}, {{ $address->province_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('messages.payment_method') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                        id="payment_cod" value="cod" checked required>
                                    <label class="form-check-label" for="payment_cod">
                                        {{ __('messages.payment_cod') }}
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                        id="payment_bank" value="bank">
                                    <label class="form-check-label" for="payment_bank">
                                        {{ __('messages.payment_bank') }}
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                        id="payment_momo" value="momo">
                                    <label class="form-check-label" for="payment_momo">
                                        {{ __('messages.payment_momo') }}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                        id="payment_vnpay" value="vnpay">
                                    <label class="form-check-label" for="payment_vnpay">
                                        {{ __('messages.payment_vnpay') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="btn btn-success btn-lg w-100" 
                        {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                        {{ __('messages.place_order') }}
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

<!-- Modal Thêm Địa Chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('addresses.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addAddressModalLabel">{{ __('messages.add_new_address') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">{{ __('messages.full_name') }}</label>
          <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('messages.phone_number') }}</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('messages.address_line') }}</label>
          <input type="text" name="address" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('messages.province') }}</label>
          <select name="province" id="modal_province" class="form-select" required>
            <option value="">{{ __('messages.select_province') }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('messages.district') }}</label>
          <select name="district" id="modal_district" class="form-select" required>
            <option value="">{{ __('messages.select_district') }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('messages.ward') }}</label>
          <select name="ward" id="modal_ward" class="form-select" required>
            <option value="">{{ __('messages.select_ward') }}</option>
          </select>
        </div>
        <input type="hidden" name="province_name" id="province_name">
        <input type="hidden" name="district_name" id="district_name">
        <input type="hidden" name="ward_name" id="ward_name">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('messages.save_address') }}</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate shipping address
    const shippingAddress = document.querySelector('input[name="shipping_address_id"]:checked');
    if (!shippingAddress) {
        alert('{{ __("messages.payment_method_required") }}');
        return;
    }

    // Validate payment method
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        alert('{{ __("messages.payment_method_required") }}');
        return;
    }

    // If all validations pass, submit the form
    this.submit();
});

$(document).ready(function() {
    // Load provinces for modal
    $('#addAddressModal').on('show.bs.modal', function () {
        $.get('https://provinces.open-api.vn/api/p/', function(data) {
            $('#modal_province').empty().append('<option value="">{{ __('messages.select_province') }}</option>');
            data.forEach(function(item) {
                $('#modal_province').append('<option value="'+item.code+'">'+item.name+'</option>');
            });
            $('#modal_district').empty().append('<option value="">{{ __('messages.select_district') }}</option>');
            $('#modal_ward').empty().append('<option value="">{{ __('messages.select_ward') }}</option>');
        });
    });
    // On province change, load districts
    $('#modal_province').on('change', function() {
        var provinceCode = $(this).val();
        $('#modal_district').empty().append('<option value="">{{ __('messages.select_district') }}</option>');
        $('#modal_ward').empty().append('<option value="">{{ __('messages.select_ward') }}</option>');
        if(provinceCode) {
            $.get('https://provinces.open-api.vn/api/p/' + provinceCode + '?depth=2', function(data) {
                data.districts.forEach(function(item) {
                    $('#modal_district').append('<option value="'+item.code+'">'+item.name+'</option>');
                });
            });
        }
    });
    // On district change, load wards
    $('#modal_district').on('change', function() {
        var districtCode = $(this).val();
        $('#modal_ward').empty().append('<option value="">{{ __('messages.select_ward') }}</option>');
        if(districtCode) {
            $.get('https://provinces.open-api.vn/api/d/' + districtCode + '?depth=2', function(data) {
                data.wards.forEach(function(item) {
                    $('#modal_ward').append('<option value="'+item.code+'">'+item.name+'</option>');
                });
            });
        }
    });
    // AJAX submit form thêm địa chỉ (dùng route web, không phải API, dùng CSRF và cookie session)
    $('#addAddressModal form').on('submit', function(e) {
        e.preventDefault();
        // Gán tên tỉnh/huyện/xã vào input hidden trước khi serialize
        $('#province_name').val($('#modal_province option:selected').text());
        $('#district_name').val($('#modal_district option:selected').text());
        $('#ward_name').val($('#modal_ward option:selected').text());
        var $form = $(this);
        var formData = $form.serialize();
        $.ajax({
            url: '/addresses',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                $('#addAddressModal').modal('hide');
                alert('Đã thêm địa chỉ thành công!');
                location.reload();
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra: ' + (xhr.responseJSON?.message || ''));
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
#addAddressModal .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    font-size: 1rem;
    background-color: #f8f9fa;
    box-shadow: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' fill='gray' viewBox='0 0 16 16'%3E%3Cpath d='M4.646 6.646a.5.5 0 0 1 .708 0L8 9.293l2.646-2.647a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.2em;
}
#addAddressModal .form-select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.15rem rgba(0,123,255,.15);
    background-color: #fff;
}
#addAddressModal label.form-label {
    font-weight: 500;
    color: #333;
}
#addAddressModal .form-control {
    border-radius: 8px;
    background: #f8f9fa;
}
</style>
@endpush
@endsection 