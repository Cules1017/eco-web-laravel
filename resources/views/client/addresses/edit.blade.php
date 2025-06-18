@extends('layouts.app')

@section('title', __('messages.edit_address'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.edit_address') }}</h1>

        <form action="{{ route('addresses.update', $address) }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.first_name') }}
                    </label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $address->first_name) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.last_name') }}
                    </label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $address->last_name) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('messages.phone') }}
                </label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $address->phone) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('messages.address') }}
                </label>
                <input type="text" name="address" id="address" value="{{ old('address', $address->address) }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.province') }}
                    </label>
                    <select name="province_id" id="province"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                        <option value="">{{ __('messages.select_province') }}</option>
                    </select>
                    <input type="hidden" name="province_name" id="province_name" value="{{ $address->province_name }}">
                    @error('province_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.district') }}
                    </label>
                    <select name="district_id" id="district"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                        <option value="">{{ __('messages.select_district') }}</option>
                    </select>
                    <input type="hidden" name="district_name" id="district_name" value="{{ $address->district_name }}">
                    @error('district_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ward" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.ward') }}
                    </label>
                    <select name="ward_code" id="ward"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                        <option value="">{{ __('messages.select_ward') }}</option>
                    </select>
                    <input type="hidden" name="ward_name" id="ward_name" value="{{ $address->ward_name }}">
                    @error('ward_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-600">{{ __('messages.set_as_default') }}</span>
                </label>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('addresses.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    {{ __('messages.update_address') }}
                </button>
            </div>
        </form>
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

        // Load provinces
        fetch('/api/provinces')
            .then(response => response.json())
            .then(data => {
                data.forEach(province => {
                    const option = new Option(province.name, province.id);
                    if (province.id == {{ $address->province_id }}) {
                        option.selected = true;
                    }
                    provinceSelect.add(option);
                });

                // Load districts for selected province
                if (provinceSelect.value) {
                    loadDistricts(provinceSelect.value);
                }
            });

        // Handle province change
        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            provinceNameInput.value = selectedOption.text;

            // Reset and disable district and ward selects
            districtSelect.innerHTML = '<option value="">{{ __('messages.select_district') }}</option>';
            wardSelect.innerHTML = '<option value="">{{ __('messages.select_ward') }}</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;

            if (provinceId) {
                loadDistricts(provinceId);
            }
        });

        // Handle district change
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            const selectedOption = this.options[this.selectedIndex];
            districtNameInput.value = selectedOption.text;

            // Reset and disable ward select
            wardSelect.innerHTML = '<option value="">{{ __('messages.select_ward') }}</option>';
            wardSelect.disabled = true;

            if (districtId) {
                loadWards(districtId);
            }
        });

        // Handle ward change
        wardSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            wardNameInput.value = selectedOption.text;
        });

        function loadDistricts(provinceId) {
            fetch(`/api/districts/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = new Option(district.name, district.id);
                        if (district.id == {{ $address->district_id }}) {
                            option.selected = true;
                        }
                        districtSelect.add(option);
                    });
                    districtSelect.disabled = false;

                    // Load wards for selected district
                    if (districtSelect.value) {
                        loadWards(districtSelect.value);
                    }
                });
        }

        function loadWards(districtId) {
            fetch(`/api/wards/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(ward => {
                        const option = new Option(ward.name, ward.code);
                        if (ward.code == '{{ $address->ward_code }}') {
                            option.selected = true;
                        }
                        wardSelect.add(option);
                    });
                    wardSelect.disabled = false;
                });
        }
    });
</script>
@endpush
@endsection 