@extends('layouts.app')

@section('title', __('messages.checkout'))

@section('content')
<div class="container" style="padding-top: 100px;">
    <h1 class="mb-4">{{ __('messages.checkout') }}</h1>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.order_summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>{{ __('messages.subtotal') }}:</strong></td>
                                    <td>${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>{{ __('messages.tax') }}:</strong></td>
                                    <td>${{ number_format($tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>{{ __('messages.total') }}:</strong></td>
                                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-md-8">
            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.shipping_information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">{{ __('messages.first_name') }}</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required style="height:38px;">
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">{{ __('messages.last_name') }}</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required style="height:38px;">
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="height:38px;">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required style="height:38px;">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">{{ __('messages.province') }}</label>
                                <select class="form-select shadow-sm rounded bg-white @error('province_id') is-invalid @enderror" 
                                        id="province" name="province_id" required style="height:38px;">
                                    <option value="">{{ __('messages.select_province') }}</option>
                                </select>
                                @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">{{ __('messages.district') }}</label>
                                <select class="form-select shadow-sm rounded bg-white @error('district_id') is-invalid @enderror" 
                                        id="district" name="district_id" required disabled style="height:38px;">
                                    <option value="">{{ __('messages.select_district') }}</option>
                                </select>
                                @error('district_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">{{ __('messages.ward') }}</label>
                                <select class="form-select shadow-sm rounded bg-white @error('ward_id') is-invalid @enderror" 
                                        id="ward" name="ward_id" required disabled style="height:38px;">
                                    <option value="">{{ __('messages.select_ward') }}</option>
                                </select>
                                @error('ward_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('messages.address') }}</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required style="height:38px;">
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">{{ __('messages.city') }}</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', auth()->user()->city) }}" required style="height:38px;">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="state" class="form-label">{{ __('messages.state') }}</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', auth()->user()->state) }}" required style="height:38px;">
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip_code" class="form-label">{{ __('messages.zip_code') }}</label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror" 
                                       id="zip_code" name="zip_code" value="{{ old('zip_code', auth()->user()->zip_code) }}" required style="height:38px;">
                                @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.payment_information') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">{{ __('messages.select_payment_method') }}</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="credit_card" value="credit_card" 
                                       {{ old('payment_method') == 'credit_card' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="credit_card">
                                    {{ __('messages.credit_card') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cod" value="cod" 
                                       {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cod">
                                    {{ __('messages.cod') }}
                                </label>
                            </div>
                            @error('payment_method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="credit-card-fields">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">{{ __('messages.card_number') }}</label>
                                <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                       id="card_number" name="card_number" style="height:38px;">
                                @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiry_date" class="form-label">{{ __('messages.expiry_date') }}</label>
                                    <input type="text" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           id="expiry_date" name="expiry_date" placeholder="MM/YY" style="height:38px;">
                                    @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">{{ __('messages.cvv') }}</label>
                                    <input type="text" class="form-control @error('cvv') is-invalid @enderror" 
                                           id="cvv" name="cvv" style="height:38px;">
                                    @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_cart') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.place_order') }} <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide credit card fields based on payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const creditCardFields = document.getElementById('credit-card-fields');
            const cardInputs = creditCardFields.querySelectorAll('input');
            
            if (this.value === 'credit_card') {
                creditCardFields.style.display = 'block';
                cardInputs.forEach(input => input.required = true);
            } else {
                creditCardFields.style.display = 'none';
                cardInputs.forEach(input => input.required = false);
            }
        });
    });

    // Format card number
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        let formattedValue = '';
        for(let i = 0; i < value.length; i++) {
            if(i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });

    // Format expiry date
    document.getElementById('expiry_date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if(value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Format CVV
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
    });

    // Initialize credit card fields visibility
    document.addEventListener('DOMContentLoaded', function() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod) {
            selectedMethod.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection 