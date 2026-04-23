@extends('layouts.eshopper')

@section('title', __('messages.add_new_address'))

@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">{{ __('messages.add_new_address') }}</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label fw-semibold">{{ __('messages.first_name') }}</label>
                                <input type="text" name="first_name" id="first_name"
                                       value="{{ old('first_name') }}"
                                       class="form-control @error('first_name') is-invalid @enderror" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label fw-semibold">{{ __('messages.last_name') }}</label>
                                <input type="text" name="last_name" id="last_name"
                                       value="{{ old('last_name') }}"
                                       class="form-control @error('last_name') is-invalid @enderror" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">{{ __('messages.phone') }}</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">{{ __('messages.address') }}</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                   class="form-control @error('address') is-invalid @enderror"
                                   placeholder="Số nhà, tên đường..." required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label fw-semibold">{{ __('messages.province') }}</label>
                                <select name="province_id" id="province"
                                        class="form-select @error('province_id') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.select_province') }}</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name">
                                @error('province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label fw-semibold">{{ __('messages.district') }}</label>
                                <select name="district_id" id="district"
                                        class="form-select @error('district_id') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('messages.select_district') }}</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name">
                                @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label fw-semibold">{{ __('messages.ward') }}</label>
                                <select name="ward_code" id="ward"
                                        class="form-select @error('ward_code') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('messages.select_ward') }}</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name">
                                @error('ward_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                   id="is_default" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                {{ __('messages.set_as_default') }}
                            </label>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i>{{ __('messages.save_address') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const provinceNameInput = document.getElementById('province_name');
        const districtNameInput = document.getElementById('district_name');
        const wardNameInput = document.getElementById('ward_name');

        fetch('/api/provinces')
            .then(response => response.json())
            .then(data => {
                data.forEach(province => {
                    const option = new Option(province.name, province.id);
                    provinceSelect.add(option);
                });
            });

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            provinceNameInput.value = selectedOption.text;

            districtSelect.innerHTML = '<option value="">{{ __('messages.select_district') }}</option>';
            wardSelect.innerHTML = '<option value="">{{ __('messages.select_ward') }}</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (provinceId) {
                fetch(`/api/districts/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(district => {
                            const option = new Option(district.name, district.id);
                            districtSelect.add(option);
                        });
                        districtSelect.disabled = false;
                    });
            }
        });

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            districtNameInput.value = selectedOption.text;

            wardSelect.innerHTML = '<option value="">{{ __('messages.select_ward') }}</option>';
            wardSelect.disabled = true;

            if (districtId) {
                fetch(`/api/wards/${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(ward => {
                            const option = new Option(ward.name, ward.code);
                            wardSelect.add(option);
                        });
                        wardSelect.disabled = false;
                    });
            }
        });

        wardSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            wardNameInput.value = selectedOption.text;
        });
    });
</script>
@endpush
@endsection
