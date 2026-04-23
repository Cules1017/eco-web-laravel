@extends('layouts.eshopper')

@section('title', 'Thanh toán chuyển khoản - Đơn ' . $order->order_number)

@section('content')
<div class="container vs-page-wrapper">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4 justify-content-center">
        <div class="col-lg-10">
            <div class="card bank-card">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 align-items-center">
                        <!-- QR VietQR -->
                        <div class="col-md-5 text-center">
                            <div class="bank-brand mb-3">
                                <div class="bank-brand-logo">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="fw-bold fs-5 mt-2" style="color:#0f5132;">Chuyển khoản ngân hàng</div>
                                <div class="small text-muted">Quét mã QR bằng <strong>app ngân hàng bất kỳ</strong> (NAPAS 247)</div>
                            </div>

                            <div class="bank-qr-wrap position-relative">
                                @if($qrUrl)
                                    <img src="{{ $qrUrl }}" id="bank-qr-img" alt="VietQR" class="img-fluid rounded">
                                @else
                                    <div class="text-muted p-4">
                                        <i class="fas fa-triangle-exclamation fa-2x text-warning mb-2"></i>
                                        <div>Chưa cấu hình tài khoản nhận chuyển khoản.</div>
                                        <div class="small">Vui lòng liên hệ cửa hàng hoặc chọn phương thức khác.</div>
                                    </div>
                                @endif
                                <div id="bank-qr-success" class="bank-success-overlay" style="display:none;">
                                    <div class="text-center">
                                        <i class="fas fa-circle-check" style="font-size:64px; color:#16a34a;"></i>
                                        <div class="fw-bold mt-2" style="color:#16a34a;">Đã thanh toán!</div>
                                    </div>
                                </div>
                            </div>

                            @if($qrUrl)
                                <div class="mt-3 d-flex gap-2 justify-content-center flex-wrap">
                                    <a href="{{ $qrUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-up-right-from-square me-1"></i> Mở QR cỡ lớn
                                    </a>
                                    <a href="{{ $qrUrl }}" download="VietQR-{{ $order->order_number }}.png" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-download me-1"></i> Tải QR
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Thông tin chuyển khoản -->
                        <div class="col-md-7">
                            <h4 class="mb-3">Thông tin chuyển khoản</h4>

                            <ul class="list-unstyled bank-info">
                                <li>
                                    <span class="lbl">Ngân hàng</span>
                                    <span class="val">
                                        {{ $bank['name'] ?: '—' }}
                                        @if($bank['bin']) <small class="text-muted">(BIN {{ $bank['bin'] }})</small> @endif
                                    </span>
                                </li>
                                <li>
                                    <span class="lbl">Chủ tài khoản</span>
                                    <span class="val">{{ $bank['account_name'] ?: '—' }}</span>
                                </li>
                                <li>
                                    <span class="lbl">Số tài khoản</span>
                                    <span class="val d-flex align-items-center gap-2 flex-wrap">
                                        <strong id="bank-acc-no">{{ $bank['account_no'] ?: '—' }}</strong>
                                        @if($bank['account_no'])
                                            <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="bank-acc-no">
                                                <i class="fas fa-copy me-1"></i> Sao chép
                                            </button>
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    <span class="lbl">Số tiền</span>
                                    <span class="val d-flex align-items-center gap-2 flex-wrap">
                                        <strong class="vs-price-vnd fs-4" id="bank-amount">{{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                        <span class="vs-price-vnd fs-5">₫</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy-value="{{ (int) $order->total_amount }}">
                                            <i class="fas fa-copy me-1"></i> Sao chép
                                        </button>
                                    </span>
                                </li>
                                <li class="bank-content-row">
                                    <span class="lbl">
                                        Nội dung chuyển khoản
                                        <div class="small text-danger">Bắt buộc ghi đúng</div>
                                    </span>
                                    <span class="val d-flex align-items-center gap-2 flex-wrap">
                                        <strong class="bank-content-code" id="bank-memo">{{ $transferContent }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-primary copy-btn" data-copy="bank-memo">
                                            <i class="fas fa-copy me-1"></i> Sao chép
                                        </button>
                                    </span>
                                </li>
                                <li>
                                    <span class="lbl">Mã đơn hàng</span>
                                    <span class="val">#{{ $order->order_number }}</span>
                                </li>
                                <li>
                                    <span class="lbl">Trạng thái</span>
                                    <span class="val">
                                        @if($order->payment_status === 'paid')
                                            <span class="badge bg-success" id="bank-status-badge">Đã thanh toán</span>
                                        @elseif($notifiedAt)
                                            <span class="badge bg-info" id="bank-status-badge">Chờ xác nhận</span>
                                        @else
                                            <span class="badge bg-warning" id="bank-status-badge">Chờ thanh toán</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>

                            <div class="alert alert-warning small mt-3 mb-0">
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Mẹo:</strong> Dùng app ngân hàng → chọn <strong>Chuyển khoản bằng mã QR</strong> → quét mã ở bên cạnh.
                                Toàn bộ thông tin (số TK, số tiền, nội dung) sẽ được điền tự động, bạn chỉ cần xác nhận.
                            </div>

                            <div class="d-flex gap-2 mt-4 flex-wrap">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Về đơn hàng
                                </a>
                                <button type="button" class="btn btn-success" id="btn-notified">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    {{ $notifiedAt ? 'Đã báo chuyển khoản' : 'Tôi đã chuyển khoản' }}
                                </button>
                            </div>

                            @if(!app()->environment('production'))
                                <div class="mock-pay-box mt-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="fw-bold text-warning-emphasis">
                                                <i class="fas fa-flask me-1"></i> Chế độ DEV
                                            </div>
                                            <div class="small text-muted">
                                                Bấm để giả lập ngân hàng báo đã nhận tiền, đơn sẽ chuyển sang "Đã thanh toán".
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="btn-mock-pay-bank">
                                            <i class="fas fa-bolt me-1"></i> Giả lập đã nhận tiền
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bank-card {
    border: none;
    box-shadow: var(--vs-shadow-lg);
    border-radius: 20px;
    overflow: hidden;
    background: #fff;
}
.bank-brand-logo {
    width: 68px; height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, #16a34a, #0f5132);
    color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 28px;
    box-shadow: 0 10px 24px rgba(15, 81, 50, 0.3);
}
.bank-qr-wrap {
    background: #fff;
    border: 2px dashed #bbf7d0;
    border-radius: 16px;
    padding: 14px;
    display: inline-block;
    max-width: 360px;
}
.bank-qr-wrap img { border-radius: 8px; }
.bank-success-overlay {
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.96);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    animation: vsFadeIn 0.3s ease;
}
.bank-info { margin: 0; padding: 0; }
.bank-info li {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px dashed #e5e7eb;
}
.bank-info li:last-child { border-bottom: none; }
.bank-info .lbl { color: var(--vs-text-muted); flex: 0 0 auto; }
.bank-info .val { font-weight: 600; color: var(--vs-text); text-align: right; }
.bank-info .bank-content-code {
    background: #fef3c7;
    border: 1px dashed #f59e0b;
    padding: 4px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    color: #92400e;
    letter-spacing: 1px;
}
.mock-pay-box {
    background: repeating-linear-gradient(45deg, #fffbeb, #fffbeb 8px, #fef3c7 8px, #fef3c7 16px);
    border: 1px dashed #f59e0b;
    border-radius: 12px;
    padding: 12px 16px;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    const notifyUrl = @json(route('payment.bank.notify', $order));
    const mockUrl = @json(route('payment.bank.mock', $order));
    const orderUrl = @json(route('orders.show', $order));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const badge          = document.getElementById('bank-status-badge');
    const successOverlay = document.getElementById('bank-qr-success');
    const notifyBtn      = document.getElementById('btn-notified');
    const mockBtn        = document.getElementById('btn-mock-pay-bank');

    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.dataset.copyValue
                || document.getElementById(btn.dataset.copy)?.textContent?.trim()
                || '';
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const old = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Đã sao chép';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-primary');
                setTimeout(() => {
                    btn.innerHTML = old;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                }, 1500);
            });
        });
    });

    async function post(url) {
        const r = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf || ''
            }
        });
        const data = await r.json().catch(() => ({}));
        return { ok: r.ok, data };
    }

    if (notifyBtn) {
        notifyBtn.addEventListener('click', async () => {
            notifyBtn.disabled = true;
            const old = notifyBtn.innerHTML;
            notifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang gửi...';
            const { ok, data } = await post(notifyUrl);
            if (ok && data.success) {
                if (badge) { badge.className = 'badge bg-info'; badge.textContent = 'Chờ xác nhận'; }
                notifyBtn.innerHTML = '<i class="fas fa-check me-1"></i> Đã báo chuyển khoản';
                if (typeof window.showCartToast === 'function') {
                    window.showCartToast(data.message || 'Đã ghi nhận thông báo.', true);
                }
                if (data.redirect) setTimeout(() => window.location.href = data.redirect, 1200);
            } else {
                notifyBtn.disabled = false;
                notifyBtn.innerHTML = old;
                alert(data.message || 'Không gửi được thông báo.');
            }
        });
    }

    if (mockBtn) {
        mockBtn.addEventListener('click', async () => {
            const ok = window.aiConfirm
                ? await window.aiConfirm('Giả lập ngân hàng báo đã nhận tiền cho đơn này?')
                : confirm('Giả lập ngân hàng báo đã nhận tiền cho đơn này?');
            if (!ok) return;
            mockBtn.disabled = true;
            const old = mockBtn.innerHTML;
            mockBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
            const res = await post(mockUrl);
            if (res.ok && res.data.success) {
                if (successOverlay) successOverlay.style.display = 'flex';
                if (badge) { badge.className = 'badge bg-success'; badge.textContent = 'Đã thanh toán'; }
                if (typeof window.showCartToast === 'function') {
                    window.showCartToast('Giả lập thanh toán thành công!', true);
                }
                setTimeout(() => { window.location.href = res.data.redirect || orderUrl; }, 1000);
            } else {
                mockBtn.disabled = false;
                mockBtn.innerHTML = old;
                alert(res.data.message || 'Giả lập thất bại.');
            }
        });
    }
})();
</script>
@endpush
@endsection
