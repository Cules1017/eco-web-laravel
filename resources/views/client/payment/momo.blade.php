@extends('layouts.eshopper')

@section('title', 'Thanh toán MoMo - Đơn ' . $order->order_number)

@section('content')
@php
    $qrImage = $qrCodeUrl
        ? 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&margin=8&data=' . urlencode($qrCodeUrl)
        : null;
@endphp

<div class="container vs-page-wrapper">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4 justify-content-center">
        <div class="col-lg-10">
            <div class="card momo-card">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 align-items-center">
                        <!-- QR -->
                        <div class="col-md-5 text-center">
                            <div class="momo-brand mb-3">
                                <div class="momo-brand-logo">
                                    <span>M</span>
                                </div>
                                <div class="fw-bold fs-5 mt-2" style="color:#a50064;">Thanh toán MoMo</div>
                            </div>

                            <div class="momo-qr-wrap position-relative">
                                @if($qrImage)
                                    <img src="{{ $qrImage }}" id="momo-qr-img" alt="MoMo QR" class="img-fluid rounded">
                                @else
                                    <div class="text-muted p-4">Không tạo được mã QR.</div>
                                @endif
                                <div id="momo-qr-success" class="momo-success-overlay" style="display:none;">
                                    <div class="text-center">
                                        <i class="fas fa-circle-check" style="font-size:64px; color:#16a34a;"></i>
                                        <div class="fw-bold mt-2" style="color:#16a34a;">Đã thanh toán!</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 small text-muted">
                                <i class="fas fa-mobile-screen me-1"></i>
                                Dùng ứng dụng MoMo quét mã QR để thanh toán
                            </div>

                            @if($payUrl)
                                <a href="{{ $payUrl }}" target="_blank" rel="noopener"
                                   class="btn btn-outline-primary w-100 mt-3">
                                    <i class="fas fa-up-right-from-square me-1"></i> Mở cổng MoMo
                                </a>
                            @endif

                            @if($deeplink)
                                <a href="{{ $deeplink }}" class="btn btn-link btn-sm mt-1 d-md-none">
                                    <i class="fas fa-mobile me-1"></i> Mở app MoMo trên điện thoại
                                </a>
                            @endif
                        </div>

                        <!-- Order summary -->
                        <div class="col-md-7">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="mb-0">Thông tin đơn hàng</h4>
                                <span class="momo-timer" id="momo-countdown">
                                    <i class="far fa-clock me-1"></i><span id="momo-countdown-val">15:00</span>
                                </span>
                            </div>

                            <ul class="list-unstyled momo-info">
                                <li>
                                    <span class="lbl">Mã đơn hàng</span>
                                    <span class="val">#{{ $order->order_number }}</span>
                                </li>
                                <li>
                                    <span class="lbl">Số lượng sản phẩm</span>
                                    <span class="val">{{ $order->items->count() }}</span>
                                </li>
                                <li>
                                    <span class="lbl">Ngày tạo</span>
                                    <span class="val">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                                <li>
                                    <span class="lbl">Số tiền cần thanh toán</span>
                                    <span class="val vs-price-vnd fs-4">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                                </li>
                                <li>
                                    <span class="lbl">Trạng thái</span>
                                    <span class="val">
                                        <span id="momo-status-badge" class="badge bg-warning">Chờ thanh toán</span>
                                    </span>
                                </li>
                            </ul>

                            <div class="momo-steps mt-3">
                                <div class="step">
                                    <div class="step-num">1</div>
                                    <div>Mở app <strong>MoMo</strong> trên điện thoại.</div>
                                </div>
                                <div class="step">
                                    <div class="step-num">2</div>
                                    <div>Chọn mục <strong>Quét mã QR</strong> và quét mã bên cạnh.</div>
                                </div>
                                <div class="step">
                                    <div class="step-num">3</div>
                                    <div>Xác nhận thanh toán. Trang này sẽ tự chuyển sau khi thanh toán thành công.</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4 flex-wrap">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Về đơn hàng
                                </a>
                                <button type="button" class="btn btn-outline-primary" id="btn-manual-check">
                                    <i class="fas fa-rotate me-1"></i> Kiểm tra thanh toán
                                </button>
                            </div>

                            @if(!app()->environment('production'))
                                <div class="mock-pay-box mt-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="fw-bold text-warning-emphasis">
                                                <i class="fas fa-flask me-1"> TEST</i> 
                                            </div>
                                            <div class="small text-muted">
                                                Bạn có thể bấm bên phải để giả lập MoMo báo thanh toán thành công mà không cần quét QR.
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="btn-mock-pay">
                                            <i class="fas fa-bolt me-1"></i> Giả lập thanh toán thành công
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- <div class="alert alert-info mt-3 mb-0 small">
                                <i class="fas fa-circle-info me-1"></i>
                                <strong>Tài khoản test MoMo sandbox:</strong> Bạn có thể dùng app test MoMo hoặc nhập mã QR trên
                                <a href="https://test-payment.momo.vn/" target="_blank" rel="noopener">test-payment.momo.vn</a>
                                để mô phỏng thanh toán thành công.
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.momo-card {
    border: none;
    box-shadow: var(--vs-shadow-lg);
    border-radius: 20px;
    overflow: hidden;
    background: #fff;
}
.momo-brand-logo {
    width: 68px; height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ae2070, #a50064);
    color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800;
    box-shadow: 0 10px 24px rgba(165, 0, 100, 0.3);
    letter-spacing: -1px;
}
.momo-qr-wrap {
    background: #fff;
    border: 2px dashed #e5e7eb;
    border-radius: 16px;
    padding: 14px;
    display: inline-block;
}
.momo-qr-wrap img { border-radius: 8px; }
.momo-success-overlay {
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.96);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    animation: vsFadeIn 0.3s ease;
}
@keyframes vsFadeIn { from { opacity: 0; } to { opacity: 1; } }

.momo-timer {
    color: #a50064;
    background: #fff0f7;
    border: 1px solid #ffd4ea;
    padding: 4px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.9rem;
}

.momo-info { margin: 0; padding: 0; }
.momo-info li {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed #e5e7eb;
}
.momo-info li:last-child { border-bottom: none; }
.momo-info .lbl { color: var(--vs-text-muted); }
.momo-info .val { font-weight: 600; color: var(--vs-text); }

.momo-steps .step {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 8px 0;
}
.momo-steps .step-num {
    flex: 0 0 28px;
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--vs-gradient-soft);
    color: var(--vs-primary);
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem;
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
    const statusUrl = @json(route('payment.momo.status', $order));
    const orderUrl  = @json(route('orders.show', $order));
    const badge = document.getElementById('momo-status-badge');
    const successOverlay = document.getElementById('momo-qr-success');
    const manualBtn = document.getElementById('btn-manual-check');

    let pollTimer = null;
    let stopped = false;

    function check() {
        if (stopped) return;
        fetch(statusUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                if (data && data.paid) {
                    stopped = true;
                    if (successOverlay) successOverlay.style.display = 'flex';
                    if (badge) {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Đã thanh toán';
                    }
                    if (pollTimer) clearInterval(pollTimer);
                    if (typeof window.showCartToast === 'function') {
                        window.showCartToast('Thanh toán thành công! Đang chuyển tới đơn hàng...', true);
                    }
                    setTimeout(() => { window.location.href = data.redirect || orderUrl; }, 1200);
                }
            })
            .catch(() => {});
    }

    // Poll every 3s (first check after 2s)
    setTimeout(check, 2000);
    pollTimer = setInterval(check, 3000);

    if (manualBtn) {
        manualBtn.addEventListener('click', function() {
            manualBtn.disabled = true;
            manualBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang kiểm tra...';
            check();
            setTimeout(() => {
                manualBtn.disabled = false;
                manualBtn.innerHTML = '<i class="fas fa-rotate me-1"></i> Kiểm tra thanh toán';
            }, 1500);
        });
    }

    // Giả lập thanh toán thành công (chỉ DEV)
    const mockBtn = document.getElementById('btn-mock-pay');
    if (mockBtn) {
        const mockUrl = @json(route('payment.momo.mock', $order));
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        mockBtn.addEventListener('click', async function() {
            const ok = window.aiConfirm
                ? await window.aiConfirm('Bạn có chắc muốn giả lập thanh toán thành công cho đơn này?')
                : confirm('Bạn có chắc muốn giả lập thanh toán thành công cho đơn này?');
            if (!ok) return;

            mockBtn.disabled = true;
            mockBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...';
            try {
                const r = await fetch(mockUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf || ''
                    }
                });
                const data = await r.json().catch(() => ({}));
                if (r.ok && data.success) {
                    stopped = true;
                    if (successOverlay) successOverlay.style.display = 'flex';
                    if (badge) { badge.className = 'badge bg-success'; badge.textContent = 'Đã thanh toán'; }
                    if (pollTimer) clearInterval(pollTimer);
                    if (typeof window.showCartToast === 'function') {
                        window.showCartToast('Giả lập thanh toán thành công! Đang chuyển tới đơn hàng...', true);
                    }
                    setTimeout(() => { window.location.href = data.redirect || orderUrl; }, 1000);
                } else {
                    mockBtn.disabled = false;
                    mockBtn.innerHTML = '<i class="fas fa-bolt me-1"></i> Giả lập thanh toán thành công';
                    alert(data.message || 'Giả lập thất bại.');
                }
            } catch (e) {
                mockBtn.disabled = false;
                mockBtn.innerHTML = '<i class="fas fa-bolt me-1"></i> Giả lập thanh toán thành công';
                alert('Lỗi kết nối: ' + e.message);
            }
        });
    }

    // Countdown 15 phút (chỉ UI)
    const cdEl = document.getElementById('momo-countdown-val');
    const cdWrap = document.getElementById('momo-countdown');
    let remain = 15 * 60;
    function renderCd() {
        if (!cdEl) return;
        const m = String(Math.floor(remain / 60)).padStart(2, '0');
        const s = String(remain % 60).padStart(2, '0');
        cdEl.textContent = m + ':' + s;
        if (remain <= 0) {
            if (cdWrap) { cdWrap.style.background = '#fef2f2'; cdWrap.style.color = '#dc2626'; cdWrap.style.borderColor = '#fecaca'; }
        }
    }
    renderCd();
    const cdTimer = setInterval(() => {
        if (stopped) { clearInterval(cdTimer); return; }
        remain = Math.max(0, remain - 1);
        renderCd();
        if (remain <= 0) clearInterval(cdTimer);
    }, 1000);
})();
</script>
@endpush
@endsection
