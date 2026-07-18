@extends('layouts.eshopper')

@section('title', __('messages.checkout'))

@section('content')
<style>
    .checkout-input, .checkout-select {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        height: auto !important;
        padding: 1rem 1.25rem !important;
        font-size: 1.05rem !important;
        color: #333333 !important;
        transition: all 0.2s ease-in-out !important;
    }
    .checkout-input:focus, .checkout-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
        outline: none !important;
        background-color: #ffffff !important;
    }
    .checkout-input.is-invalid, .checkout-select.is-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
    }
    .checkout-label {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #4b5563;
        margin-bottom: 8px;
    }
    .vs-page-wrapper.py-5 {
        padding-top: 5rem !important;
        padding-bottom: 5rem !important;
    }
</style>
<div class="container vs-page-wrapper py-5">
    <h1 class="mb-5 text-uppercase" style="font-weight: 300; letter-spacing: 2px;">{{ __('messages.checkout') }}</h1>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-5 mb-5 order-md-2">
            <h4 class="text-uppercase mb-4 pb-2" style="letter-spacing: 1px; font-weight: 400; border-bottom: 2px solid #000;">{{ __('messages.order_summary') }}</h4>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr class="border-bottom text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">
                            <th class="px-0 py-3">{{ __('messages.product') }}</th>
                            <th class="px-0 py-3 text-center">{{ __('messages.quantity') }}</th>
                            <th class="px-0 py-3 text-end">{{ __('messages.price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr class="border-bottom">
                            <td class="px-0 py-3">{{ $item->product->name }}</td>
                            <td class="px-0 py-3 text-center">{{ $item->quantity }}</td>
                            <td class="px-0 py-3 text-end vs-price-vnd">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top-0">
                        <tr>
                            <td colspan="2" class="text-end px-0 pt-4"><strong>{{ __('messages.subtotal') }}:</strong></td>
                            <td class="text-end px-0 pt-4">{{ number_format($subtotal, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end px-0 pb-3"><strong>{{ __('messages.tax') }}:</strong></td>
                            <td class="text-end px-0 pb-3">{{ number_format($tax, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end px-0 py-3"><strong>{{ __('messages.total') }}:</strong></td>
                            <td class="text-end px-0 py-3"><strong class="vs-price-vnd" style="font-size: 1.25rem;">{{ number_format($total, 0, ',', '.') }}₫</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-md-7 pe-md-5 order-md-1">
            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-5">
                    <h4 class="text-uppercase mb-4 pb-2" style="letter-spacing: 1px; font-weight: 400; border-bottom: 2px solid #000;">{{ __('messages.shipping_information') }}</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="first_name" class="form-label checkout-label">{{ __('messages.first_name') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="last_name" class="form-label checkout-label">{{ __('messages.last_name') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label checkout-label">{{ __('messages.email') }}</label>
                        <input type="email" class="form-control p-3 checkout-input @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label checkout-label">{{ __('messages.phone') }}</label>
                        <input type="tel" class="form-control p-3 checkout-input @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="province" class="form-label checkout-label">{{ __('messages.province') }}</label>
                            <select class="form-select p-3 checkout-select bg-white @error('province_id') is-invalid @enderror" 
                                    id="province" name="province_id" required>
                                <option value="">{{ __('messages.select_province') }}</option>
                            </select>
                            @error('province_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="district" class="form-label checkout-label">{{ __('messages.district') }}</label>
                            <select class="form-select p-3 checkout-select bg-white @error('district_id') is-invalid @enderror" 
                                    id="district" name="district_id" required disabled>
                                <option value="">{{ __('messages.select_district') }}</option>
                            </select>
                            @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="ward" class="form-label checkout-label">{{ __('messages.ward') }}</label>
                            <select class="form-select p-3 checkout-select bg-white @error('ward_id') is-invalid @enderror" 
                                    id="ward" name="ward_id" required disabled>
                                <option value="">{{ __('messages.select_ward') }}</option>
                            </select>
                            @error('ward_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label checkout-label">{{ __('messages.address') }}</label>
                        <input type="text" class="form-control p-3 checkout-input @error('address') is-invalid @enderror" 
                               id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="city" class="form-label checkout-label">{{ __('messages.city') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('city') is-invalid @enderror" 
                                   id="city" name="city" value="{{ old('city', auth()->user()->city) }}" required>
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-4">
                            <label for="state" class="form-label checkout-label">{{ __('messages.state') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('state') is-invalid @enderror" 
                                   id="state" name="state" value="{{ old('state', auth()->user()->state) }}" required>
                            @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-4">
                            <label for="zip_code" class="form-label checkout-label">{{ __('messages.zip_code') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('zip_code') is-invalid @enderror" 
                                   id="zip_code" name="zip_code" value="{{ old('zip_code', auth()->user()->zip_code) }}" required>
                            @error('zip_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h4 class="text-uppercase mb-4 pb-2" style="letter-spacing: 1px; font-weight: 400; border-bottom: 2px solid #000;">{{ __('messages.payment_information') }}</h4>
                    <div class="mb-4">
                        <label class="form-label checkout-label">{{ __('messages.select_payment_method') }}</label>
                        <div class="form-check mb-3 mt-2">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="credit_card" value="credit_card" 
                                   {{ old('payment_method') == 'credit_card' ? 'checked' : '' }} required>
                            <label class="form-check-label ms-2" for="credit_card" style="font-weight: 500;">
                                {{ __('messages.credit_card') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="cod" value="cod" 
                                   {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="cod" style="font-weight: 500;">
                                {{ __('messages.cod') }}
                            </label>
                        </div>
                        @error('payment_method')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="credit-card-fields">
                        <div class="mb-4">
                            <label for="card_number" class="form-label checkout-label">{{ __('messages.card_number') }}</label>
                            <input type="text" class="form-control p-3 checkout-input @error('card_number') is-invalid @enderror" 
                                   id="card_number" name="card_number">
                            @error('card_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="expiry_date" class="form-label checkout-label">{{ __('messages.expiry_date') }}</label>
                                <input type="text" class="form-control p-3 checkout-input @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="cvv" class="form-label checkout-label">{{ __('messages.cvv') }}</label>
                                <input type="text" class="form-control p-3 checkout-input @error('cvv') is-invalid @enderror" 
                                       id="cvv" name="cvv">
                                @error('cvv')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-dark px-4 py-3 rounded-0 text-uppercase" style="letter-spacing: 1px;">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('messages.back_to_cart') }}
                    </a>
                    <button type="submit" class="btn btn-dark px-5 py-3 rounded-0 text-uppercase" style="letter-spacing: 1px;">
                        {{ __('messages.place_order') }} <i class="fas fa-arrow-right ms-2"></i>
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