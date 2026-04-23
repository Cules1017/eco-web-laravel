@extends('layouts.admin')

@section('title', 'Cấu hình website')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">Cấu hình website</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="site_name">Tên website</label>
                            <input type="text" name="site_name" id="site_name" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $settings['site_name']->value ?? '') }}" required>
                            @error('site_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="site_description">Mô tả website</label>
                            <textarea name="site_description" id="site_description" class="form-control @error('site_description') is-invalid @enderror" rows="3">{{ old('site_description', $settings['site_description']->value ?? '') }}</textarea>
                            @error('site_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="logo">Logo website</label><br>
                            @if(!empty($settings['site_logo']->value))
                                <img src="{{ asset('storage/' . $settings['site_logo']->value) }}" alt="Logo" style="max-height: 80px;" class="mb-2">
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control-file @error('logo') is-invalid @enderror">
                            @error('logo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <hr class="my-4">
                        <h5 class="mb-3"><i class="fas fa-university me-2"></i>Tài khoản nhận chuyển khoản (VietQR)</h5>
                        <p class="text-muted small">
                            Chọn ngân hàng từ danh sách (đồng bộ từ
                            <a href="https://api.vietqr.io/v2/banks" target="_blank" rel="noopener">VietQR API</a>).
                            Nếu API không tải được, bạn có thể nhập trực tiếp Mã BIN (6 số, VD: <code>970436</code>).
                        </p>

                        @php
                            $currentBin  = old('bank_bin', $settings['bank_bin']->value ?? '');
                            $currentName = old('bank_name', $settings['bank_name']->value ?? '');
                        @endphp

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="bank_select">Ngân hàng</label>
                                <select id="bank_select" class="form-control">
                                    <option value="">-- Đang tải danh sách --</option>
                                </select>
                                <input type="hidden" name="bank_bin"  id="bank_bin"  value="{{ $currentBin }}">
                                <input type="hidden" name="bank_name" id="bank_name" value="{{ $currentName }}">
                                <small class="text-muted">
                                    BIN hiện tại: <code id="bank_bin_preview">{{ $currentBin ?: '—' }}</code>
                                </small>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="bank_bin_manual">Hoặc nhập Mã BIN thủ công</label>
                                <input type="text" id="bank_bin_manual" class="form-control"
                                       value="{{ $currentBin }}" placeholder="VD: 970436" maxlength="6">
                                <small class="text-muted">6 chữ số. Dùng khi dropdown không khả dụng.</small>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="bank_account_no">Số tài khoản</label>
                                <input type="text" name="bank_account_no" id="bank_account_no" class="form-control"
                                       value="{{ old('bank_account_no', $settings['bank_account_no']->value ?? '') }}">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="bank_account_name">Tên chủ tài khoản</label>
                                <input type="text" name="bank_account_name" id="bank_account_name" class="form-control"
                                       value="{{ old('bank_account_name', $settings['bank_account_name']->value ?? '') }}" placeholder="VD: NGUYEN VAN A">
                            </div>
                        </div>

                        <!-- Preview QR -->
                        <div id="bank_qr_preview_box" class="d-none alert alert-light border d-flex align-items-center gap-3" style="flex-wrap:wrap;">
                            <img id="bank_qr_preview_img" src="" alt="Preview QR" style="width:160px;height:auto;border:1px solid #e5e7eb;border-radius:8px;background:#fff;">
                            <div>
                                <div class="fw-bold mb-1"><i class="fas fa-eye me-1"></i> Xem trước QR VietQR</div>
                                <div class="small text-muted">
                                    Preview với số tiền demo <strong>100.000₫</strong>, nội dung <code>TEST</code>.
                                    Nếu QR ở trên hiển thị ảnh hợp lệ là bạn đã cấu hình đúng.
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                        </div>
                    </form>
                    @push('scripts')
                    <script>
                    (function(){
                        const select     = document.getElementById('bank_select');
                        const binInput   = document.getElementById('bank_bin');
                        const nameInput  = document.getElementById('bank_name');
                        const binManual  = document.getElementById('bank_bin_manual');
                        const binPreview = document.getElementById('bank_bin_preview');
                        const accInput   = document.getElementById('bank_account_no');
                        const qrBox      = document.getElementById('bank_qr_preview_box');
                        const qrImg      = document.getElementById('bank_qr_preview_img');

                        const currentBin = {!! json_encode($currentBin) !!};

                        function refreshPreview() {
                            const bin = binInput.value.trim();
                            const acc = accInput.value.trim();
                            if (bin && acc) {
                                qrImg.src = 'https://img.vietqr.io/image/' + encodeURIComponent(bin)
                                    + '-' + encodeURIComponent(acc)
                                    + '-compact2.png?amount=100000&addInfo=TEST';
                                qrBox.classList.remove('d-none');
                            } else {
                                qrBox.classList.add('d-none');
                            }
                            binPreview.textContent = bin || '—';
                        }

                        function applyBank(bin, name) {
                            binInput.value  = bin  || '';
                            nameInput.value = name || '';
                            binManual.value = bin  || '';
                            refreshPreview();
                        }

                        // Load bank list from VietQR
                        fetch('https://api.vietqr.io/v2/banks')
                            .then(r => r.json())
                            .then(json => {
                                const banks = (json && json.data) ? json.data : [];
                                select.innerHTML = '<option value="">-- Chọn ngân hàng --</option>';
                                banks.sort((a,b) => (a.shortName||'').localeCompare(b.shortName||''));
                                banks.forEach(b => {
                                    const opt = document.createElement('option');
                                    opt.value = b.bin;
                                    opt.dataset.name = b.shortName || b.name;
                                    opt.textContent = (b.shortName || b.name) + ' — ' + b.name + ' (BIN ' + b.bin + ')';
                                    if (currentBin && String(b.bin) === String(currentBin)) opt.selected = true;
                                    select.appendChild(opt);
                                });
                            })
                            .catch(() => {
                                select.innerHTML = '<option value="">-- API không khả dụng, nhập BIN thủ công --</option>';
                            });

                        select.addEventListener('change', function() {
                            const opt = this.options[this.selectedIndex];
                            applyBank(this.value, opt ? opt.dataset.name : '');
                        });

                        binManual.addEventListener('input', function() {
                            const v = this.value.replace(/\D/g, '').slice(0,6);
                            this.value = v;
                            binInput.value = v;
                            // Nếu khớp option trong dropdown → chọn lại + đồng bộ tên
                            const opt = Array.from(select.options).find(o => String(o.value) === v);
                            if (opt) {
                                select.value = v;
                                nameInput.value = opt.dataset.name || '';
                            }
                            refreshPreview();
                        });

                        accInput.addEventListener('input', refreshPreview);

                        refreshPreview();
                    })();
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 