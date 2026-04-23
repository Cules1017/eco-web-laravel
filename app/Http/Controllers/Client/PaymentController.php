<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private MomoService $momo) {}

    /**
     * Hiển thị trang QR thanh toán MoMo.
     * - Nếu đơn chưa có payment url → gọi MoMo để tạo mới.
     * - Nếu đơn đã có payment url còn hiệu lực → dùng lại.
     */
    public function momoShow(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== 'momo') {
            return redirect()->route('orders.show', $order);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('success', __('messages.order_paid_success'));
        }

        $payload = $order->payment_payload ?? [];
        $payUrl    = $payload['payUrl']    ?? null;
        $qrCodeUrl = $payload['qrCodeUrl'] ?? null;
        $deeplink  = $payload['deeplink']  ?? null;

        if (!$payUrl || !$qrCodeUrl) {
            $orderRef    = $order->order_number . '-' . substr((string) time(), -4);
            $redirectUrl = config('services.momo.redirect_url') ?: route('payment.momo.return');
            $ipnUrl      = config('services.momo.ipn_url')      ?: route('payment.momo.ipn');

            $result = $this->momo->createPayment(
                amount: (int) $order->total_amount,
                orderId: $orderRef,
                orderInfo: 'Thanh toan don hang ' . $order->order_number,
                redirectUrl: $redirectUrl,
                ipnUrl: $ipnUrl,
                extra: ['order_id' => $order->id, 'user_id' => $order->user_id]
            );

            if (!$result['success']) {
                return redirect()->route('orders.show', $order)
                    ->with('error', 'Khởi tạo thanh toán MoMo thất bại: ' . ($result['message'] ?? ''));
            }

            $payUrl    = $result['payUrl'];
            $qrCodeUrl = $result['qrCodeUrl'];
            $deeplink  = $result['deeplink'] ?? null;

            $order->update([
                'payment_request_id' => $result['requestId'] ?? null,
                'payment_payload'    => [
                    'orderRef'  => $orderRef,
                    'payUrl'    => $payUrl,
                    'qrCodeUrl' => $qrCodeUrl,
                    'deeplink'  => $deeplink,
                    'created_at' => now()->toIso8601String(),
                ],
            ]);
        }

        return view('client.payment.momo', [
            'order'     => $order,
            'payUrl'    => $payUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'deeplink'  => $deeplink,
        ]);
    }

    /**
     * [DEV ONLY] Giả lập MoMo trả về thanh toán thành công.
     * Chỉ hoạt động khi APP_ENV != production để tránh lạm dụng.
     */
    public function momoMockSuccess(Order $order)
    {
        $this->authorizeOrder($order);

        if (app()->environment('production')) {
            abort(403, 'Mock payment disabled in production.');
        }

        if ($order->payment_method !== 'momo') {
            return response()->json(['success' => false, 'message' => 'Đơn này không thanh toán bằng MoMo.'], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'  => true,
                'message'  => 'Đơn đã thanh toán.',
                'redirect' => route('orders.show', $order),
            ]);
        }

        $this->markOrderPaid($order, [
            'transId' => 'MOCK-' . time() . '-' . $order->id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Đã giả lập thanh toán thành công.',
            'redirect' => route('orders.show', $order),
        ]);
    }

    /**
     * AJAX: client polling check trạng thái thanh toán.
     */
    public function momoStatus(Order $order)
    {
        $this->authorizeOrder($order);
        return response()->json([
            'payment_status' => $order->payment_status,
            'status'         => $order->status,
            'paid'           => $order->payment_status === 'paid',
            'redirect'       => route('orders.show', $order),
        ]);
    }

    /**
     * Redirect URL: MoMo điều hướng trình duyệt về sau khi quét QR / bấm thanh toán.
     * Đây KHÔNG PHẢI nguồn đáng tin cậy để xác nhận thanh toán — chỉ dùng để UX redirect.
     * Nguồn xác nhận chính là IPN (ipnUrl, server-to-server).
     */
    public function momoReturn(Request $request)
    {
        $params = $request->all();

        $order = $this->findOrderFromMomoParams($params);
        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        // Nếu resultCode = 0 → MoMo đã ghi nhận thanh toán; có thể đã IPN trước hoặc chưa.
        // Nếu IPN chưa tới (local dev không public), ta cũng markPaid tại đây với verify signature.
        if ((int) ($params['resultCode'] ?? -1) === 0
            && $this->momo->verifySignature($params)) {
            $this->markOrderPaid($order, $params);
            return redirect()->route('orders.show', $order)
                ->with('success', __('messages.order_paid_success'));
        }

        return redirect()->route('payment.momo.show', $order)
            ->with('error', $params['message'] ?? 'Thanh toán chưa hoàn tất.');
    }

    /**
     * IPN: MoMo gọi server-to-server để xác nhận thanh toán.
     * Phải trả HTTP 204 hoặc 200 + JSON; KHÔNG redirect.
     */
    public function momoIpn(Request $request)
    {
        $params = $request->all();
        Log::info('MoMo IPN received', $params);

        if (!$this->momo->verifySignature($params)) {
            return response()->json(['resultCode' => 99, 'message' => 'Invalid signature'], 400);
        }

        $order = $this->findOrderFromMomoParams($params);
        if (!$order) {
            return response()->json(['resultCode' => 99, 'message' => 'Order not found'], 404);
        }

        if ((int) ($params['resultCode'] ?? -1) === 0) {
            $this->markOrderPaid($order, $params);
        } else {
            $order->update([
                'payment_status'         => 'failed',
                'payment_transaction_id' => $params['transId'] ?? null,
            ]);
        }

        return response()->json(['resultCode' => 0, 'message' => 'Confirm Success']);
    }

    /* ========================================================================
     * BANK TRANSFER (VietQR / NAPAS247)
     * ======================================================================*/

    /**
     * Trang thanh toán bằng chuyển khoản ngân hàng.
     * Hiển thị thông tin TK + mã QR VietQR (auto sinh theo số tiền & nội dung CK).
     */
    public function bankShow(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== 'bank') {
            return redirect()->route('orders.show', $order);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('success', __('messages.order_paid_success'));
        }

        $bank = [
            'bin'          => Setting::getValue('bank_bin'),
            'name'         => Setting::getValue('bank_name'),
            'account_no'   => Setting::getValue('bank_account_no'),
            'account_name' => Setting::getValue('bank_account_name'),
        ];

        // Nội dung CK = order_number (để admin đối soát sao kê / webhook Casso)
        $transferContent = $order->order_number;

        // VietQR URL: https://img.vietqr.io/image/{BIN}-{ACCOUNT_NO}-compact2.png?amount=X&addInfo=...&accountName=...
        $qrUrl = null;
        if ($bank['bin'] && $bank['account_no']) {
            $qrUrl = 'https://img.vietqr.io/image/'
                . rawurlencode($bank['bin']) . '-' . rawurlencode($bank['account_no']) . '-compact2.png'
                . '?amount=' . (int) $order->total_amount
                . '&addInfo=' . rawurlencode($transferContent)
                . ($bank['account_name'] ? '&accountName=' . rawurlencode($bank['account_name']) : '');
        }

        $payload = $order->payment_payload ?? [];
        $notifiedAt = $payload['bank_notified_at'] ?? null;

        return view('client.payment.bank', [
            'order'           => $order,
            'bank'            => $bank,
            'qrUrl'           => $qrUrl,
            'transferContent' => $transferContent,
            'notifiedAt'      => $notifiedAt,
        ]);
    }

    /**
     * User bấm "Tôi đã chuyển khoản" — ghi nhận thời điểm báo để admin theo dõi.
     * KHÔNG mark paid — quyết định do admin hoặc webhook ngân hàng.
     */
    public function bankNotify(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== 'bank') {
            return response()->json(['success' => false, 'message' => 'Phương thức không hợp lệ.'], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'  => true,
                'message'  => 'Đơn đã được xác nhận thanh toán.',
                'redirect' => route('orders.show', $order),
            ]);
        }

        $payload = $order->payment_payload ?? [];
        $payload['bank_notified_at'] = now()->toIso8601String();
        $order->update(['payment_payload' => $payload]);

        return response()->json([
            'success' => true,
            'message' => 'Đã ghi nhận thông báo. Chúng tôi sẽ đối soát và xác nhận trong ít phút.',
        ]);
    }

    /**
     * [DEV ONLY] Giả lập ngân hàng báo đã nhận tiền → mark paid.
     */
    public function bankMockSuccess(Order $order)
    {
        $this->authorizeOrder($order);

        if (app()->environment('production')) {
            abort(403, 'Mock payment disabled in production.');
        }

        if ($order->payment_method !== 'bank') {
            return response()->json(['success' => false, 'message' => 'Đơn này không thanh toán bằng chuyển khoản.'], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => true, 'message' => 'Đơn đã thanh toán.',
                'redirect' => route('orders.show', $order),
            ]);
        }

        $this->markOrderPaid($order, [
            'transId' => 'BANK-MOCK-' . time() . '-' . $order->id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Đã giả lập xác nhận thanh toán chuyển khoản.',
            'redirect' => route('orders.show', $order),
        ]);
    }

    /* ---------- helpers ---------- */

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function findOrderFromMomoParams(array $params): ?Order
    {
        // orderId MoMo chúng ta gửi đi là "order_number-xxxx"; extraData có order_id thật.
        if (!empty($params['extraData'])) {
            $decoded = json_decode((string) base64_decode($params['extraData']), true);
            if (!empty($decoded['order_id'])) {
                return Order::find($decoded['order_id']);
            }
        }

        $orderRef = $params['orderId'] ?? null;
        if ($orderRef) {
            [$orderNumber] = explode('-', $orderRef, 2);
            return Order::where('order_number', $orderNumber)->first();
        }

        return null;
    }

    private function markOrderPaid(Order $order, array $params): void
    {
        if ($order->payment_status === 'paid') return;

        $order->update([
            'payment_status'         => 'paid',
            'payment_transaction_id' => $params['transId'] ?? null,
            'paid_at'                => now(),
            'status'                 => $order->status === 'pending' ? 'processing' : $order->status,
        ]);
    }
}
