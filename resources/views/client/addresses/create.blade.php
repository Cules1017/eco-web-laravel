@extends('layouts.eshopper')

@section('title', __('messages.add_new_address'))

@section('content')
<style>
    .form-control, .form-select {
        padding: 12px !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 8px !important;
        background-color: #fff !important;
        transition: border-color 0.3s ease-in-out !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2b2b2b !important;
        box-shadow: none !important;
    }
</style>
<div class="container py-5">
    <h1 class="mb-5 fw-normal text-uppercase fs-3">{{ __('messages.add_new_address') }}</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-none border-0 bg-transparent">
                <div class="card-body p-0">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-0">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf

                        <div class="row g-5 mb-5">
                            <div class="col-md-7">
                                <label for="full_name" class="form-label text-uppercase text-muted small fw-bold mb-2">Họ và tên người nhận</label>
                                <input type="text" name="full_name" id="full_name"
                                       value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                       class="form-control form-control-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('full_name') is-invalid @enderror" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="phone" class="form-label text-uppercase text-muted small fw-bold mb-2">{{ __('messages.phone') }}</label>
                                <input type="tel" name="phone" id="phone"
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       class="form-control form-control-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('phone') is-invalid @enderror"
                                       placeholder="VD: 0987654321" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <label for="province" class="form-label text-uppercase text-muted small fw-bold mb-2">{{ __('messages.province') }}</label>
                                <select id="province" class="form-select form-select-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('province_name') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.select_province') }}</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
                                @error('province_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="district" class="form-label text-uppercase text-muted small fw-bold mb-2">{{ __('messages.district') }}</label>
                                <select id="district" class="form-select form-select-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('district_name') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('messages.select_district') }}</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">
                                @error('district_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ward" class="form-label text-uppercase text-muted small fw-bold mb-2">{{ __('messages.ward') }}</label>
                                <select id="ward" class="form-select form-select-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('ward_name') is-invalid @enderror" required disabled>
                                    <option value="">{{ __('messages.select_ward') }}</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name" value="{{ old('ward_name') }}">
                                @error('ward_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="address" class="form-label text-uppercase text-muted small fw-bold mb-2">Số nhà, tên đường</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                   class="form-control form-control-lg border-0 border-bottom rounded-0 bg-transparent px-0 @error('address') is-invalid @enderror"
                                   placeholder="VD: 123 Nguyễn Trãi" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-5">
                            <input class="form-check-input border-dark" type="checkbox" name="is_default" value="1"
                                   id="is_default" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 mt-1" for="is_default">
                                {{ __('messages.set_as_default') }}
                            </label>
                        </div>

                        <div class="d-flex gap-4 mt-5 pt-4 flex-wrap border-top">
                            <button type="submit" class="btn btn-dark text-uppercase rounded-0 px-5 py-3">
                                {{ __('messages.save_address') }}
                            </button>
                            <a href="{{ route('addresses.index') }}" class="btn btn-outline-dark text-uppercase rounded-0 px-5 py-3 border-0 border-bottom text-muted">
                                {{ __('messages.cancel') }}
                            </a>
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

    const oldProvince = provinceName.value;
    const oldDistrict = districtName.value;
    const oldWard = wardName.value;

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
            fillSelect(provinceSel, data || [], 'ProvinceID', 'ProvinceName', oldProvince);
            if (provinceSel.value) {
                provinceName.value = provinceSel.options[provinceSel.selectedIndex].dataset.name || '';
                loadDistricts(provinceSel.value);
            }
        });

    function loadDistricts(provinceId) {
        districtSel.disabled = true;
        wardSel.disabled = true;
        fetch('{{ route('api.districts') }}?province_id=' + encodeURIComponent(provinceId))
            .then(r => r.json())
            .then(data => {
                fillSelect(districtSel, data || [], 'DistrictID', 'DistrictName', oldDistrict);
                districtSel.disabled = false;
                if (districtSel.value) {
                    districtName.value = districtSel.options[districtSel.selectedIndex].dataset.name || '';
                    loadWards(districtSel.value);
                }
            });
    }

    function loadWards(districtId) {
        wardSel.disabled = true;
        fetch('{{ route('api.wards') }}?district_id=' + encodeURIComponent(districtId))
            .then(r => r.json())
            .then(data => {
                fillSelect(wardSel, data || [], 'WardCode', 'WardName', oldWard);
                wardSel.disabled = false;
                if (wardSel.value) {
                    wardName.value = wardSel.options[wardSel.selectedIndex].dataset.name || '';
                }
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
