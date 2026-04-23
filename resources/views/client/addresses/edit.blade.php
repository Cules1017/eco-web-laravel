@extends('layouts.eshopper')

@section('title', __('messages.edit_address'))

@section('content')
<div class="container vs-page-wrapper">
    <h1 class="vs-section-title mb-4">{{ __('messages.edit_address') }}</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('addresses.update', $address) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="full_name" class="form-label fw-semibold">Họ và tên người nhận</label>
                                <input type="text" name="full_name" id="full_name"
                                       value="{{ old('full_name', $address->full_name) }}"
                                       class="form-control @error('full_name') is-invalid @enderror" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="phone" class="form-label fw-semibold">{{ __('messages.phone') }}</label>
                                <input type="tel" name="phone" id="phone"
                                       value="{{ old('phone', $address->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="province" class="form-label fw-semibold">{{ __('messages.province') }}</label>
                                <select id="province" class="form-select" required>
                                    <option value="">{{ __('messages.select_province') }}</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name"
                                       value="{{ old('province_name', $address->province_name) }}">
                                @error('province_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="district" class="form-label fw-semibold">{{ __('messages.district') }}</label>
                                <select id="district" class="form-select" required disabled>
                                    <option value="">{{ __('messages.select_district') }}</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name"
                                       value="{{ old('district_name', $address->district_name) }}">
                                @error('district_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ward" class="form-label fw-semibold">{{ __('messages.ward') }}</label>
                                <select id="ward" class="form-select" required disabled>
                                    <option value="">{{ __('messages.select_ward') }}</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name"
                                       value="{{ old('ward_name', $address->ward_name) }}">
                                @error('ward_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Số nhà, tên đường</label>
                            <input type="text" name="address" id="address"
                                   value="{{ old('address', $address->address) }}"
                                   class="form-control @error('address') is-invalid @enderror" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                   id="is_default" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">
                                {{ __('messages.set_as_default') }}
                            </label>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i>{{ __('messages.update_address') }}
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
(function() {
    const provinceSel = document.getElementById('province');
    const districtSel = document.getElementById('district');
    const wardSel = document.getElementById('ward');
    const provinceName = document.getElementById('province_name');
    const districtName = document.getElementById('district_name');
    const wardName = document.getElementById('ward_name');

    const currentProvince = provinceName.value;
    const currentDistrict = districtName.value;
    const currentWard = wardName.value;

    function fillSelect(sel, items, valueKey, labelKey, matchLabel) {
        sel.innerHTML = '<option value="">-- Chọn --</option>';
        items.forEach(it => {
            const opt = new Option(it[labelKey], it[valueKey]);
            opt.dataset.name = it[labelKey];
            if (matchLabel && String(it[labelKey]).trim() === String(matchLabel).trim()) {
                opt.selected = true;
            }
            sel.add(opt);
        });
    }

    fetch('{{ route('api.provinces') }}')
        .then(r => r.json())
        .then(data => {
            fillSelect(provinceSel, data || [], 'ProvinceID', 'ProvinceName', currentProvince);
            if (provinceSel.value) {
                loadDistricts(provinceSel.value);
            }
        });

    function loadDistricts(provinceId) {
        districtSel.disabled = true;
        wardSel.disabled = true;
        fetch('{{ route('api.districts') }}?province_id=' + encodeURIComponent(provinceId))
            .then(r => r.json())
            .then(data => {
                fillSelect(districtSel, data || [], 'DistrictID', 'DistrictName', currentDistrict);
                districtSel.disabled = false;
                if (districtSel.value) {
                    loadWards(districtSel.value);
                }
            });
    }

    function loadWards(districtId) {
        wardSel.disabled = true;
        fetch('{{ route('api.wards') }}?district_id=' + encodeURIComponent(districtId))
            .then(r => r.json())
            .then(data => {
                fillSelect(wardSel, data || [], 'WardCode', 'WardName', currentWard);
                wardSel.disabled = false;
            });
    }

    provinceSel.addEventListener('change', function() {
        provinceName.value = this.options[this.selectedIndex]?.dataset.name || '';
        districtName.value = '';
        wardName.value = '';
        districtSel.innerHTML = '<option value="">-- Chọn --</option>';
        wardSel.innerHTML = '<option value="">-- Chọn --</option>';
        if (this.value) loadDistricts(this.value);
    });

    districtSel.addEventListener('change', function() {
        districtName.value = this.options[this.selectedIndex]?.dataset.name || '';
        wardName.value = '';
        wardSel.innerHTML = '<option value="">-- Chọn --</option>';
        if (this.value) loadWards(this.value);
    });

    wardSel.addEventListener('change', function() {
        wardName.value = this.options[this.selectedIndex]?.dataset.name || '';
    });
})();
</script>
@endpush
@endsection
