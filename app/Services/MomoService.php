<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * MoMo Payment Gateway Service
 *
 * - Test sandbox credentials được MoMo công bố public cho mục đích thử nghiệm:
 *     endpoint: https://test-payment.momo.vn/v2/gateway/api/create
 *     partner : MOMO
 *     access  : F8BBA842ECF85
 *     secret  : K951B6PE1waDMi640xX08PD3vg6EkVlz
 *
 * Docs: https://developers.momo.vn/v3/docs/payment/guide/payWithMoMo/
 */
class MomoService
{
    protected string $endpoint;
    protected string $partnerCode;
    protected string $accessKey;
    protected string $secretKey;
    protected string $storeId;
    protected string $requestType;

    public function __construct()
    {
        $c = config('services.momo');
        $this->endpoint    = $c['endpoint'];
        $this->partnerCode = $c['partner_code'];
        $this->accessKey   = $c['access_key'];
        $this->secretKey   = $c['secret_key'];
        $this->storeId     = $c['store_id'] ?? 'VenShop';
        $this->requestType = $c['request_type'] ?? 'captureWallet';
    }

    /**
     * Tạo yêu cầu thanh toán MoMo.
     *
     * @param int|float $amount     Số tiền (VND, số nguyên)
     * @param string    $orderId    Mã đơn (unique, <= 50 ký tự)
     * @param string    $orderInfo  Mô tả đơn
     * @param string    $redirectUrl URL trình duyệt trở về sau thanh toán
     * @param string    $ipnUrl     URL MoMo gửi IPN (server-to-server). Cần HTTPS public.
     * @param array     $extra      Dữ liệu thêm (sẽ base64-encode)
     *
     * @return array{success:bool, message:string, raw:array, payUrl?:string, qrCodeUrl?:string, deeplink?:string, requestId?:string}
     */
    public function createPayment(
        $amount,
        string $orderId,
        string $orderInfo,
        string $redirectUrl,
        string $ipnUrl,
        array $extra = []
    ): array {
        $amount    = (string) ((int) round((float) $amount));
        $requestId = $orderId . '-' . Str::random(6);
        $extraData = $extra ? base64_encode(json_encode($extra, JSON_UNESCAPED_UNICODE)) : '';

        $rawHash =
              'accessKey=' . $this->accessKey
            . '&amount='       . $amount
            . '&extraData='    . $extraData
            . '&ipnUrl='       . $ipnUrl
            . '&orderId='      . $orderId
            . '&orderInfo='    . $orderInfo
            . '&partnerCode='  . $this->partnerCode
            . '&redirectUrl='  . $redirectUrl
            . '&requestId='    . $requestId
            . '&requestType='  . $this->requestType;

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'VenShop',
            'storeId'     => $this->storeId,
            'requestId'   => $requestId,
            'amount'      => (int) $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $this->requestType,
            'signature'   => $signature,
        ];

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post($this->endpoint, $payload);

            $data = $response->json() ?? [];
            $resultCode = $data['resultCode'] ?? null;

            if ($resultCode === 0) {
                return [
                    'success'   => true,
                    'message'   => $data['message'] ?? 'OK',
                    'raw'       => $data,
                    'payUrl'    => $data['payUrl']    ?? null,
                    'qrCodeUrl' => $data['qrCodeUrl'] ?? null,
                    'deeplink'  => $data['deeplink']  ?? null,
                    'requestId' => $requestId,
                ];
            }

            Log::warning('MoMo createPayment non-zero resultCode', [
                'orderId' => $orderId,
                'payload' => $payload,
                'response' => $data,
            ]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'MoMo trả về lỗi không xác định.',
                'raw'     => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('MoMo createPayment exception', [
                'orderId' => $orderId,
                'error'   => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Không kết nối được cổng thanh toán MoMo. Vui lòng thử lại.',
                'raw'     => ['exception' => $e->getMessage()],
            ];
        }
    }

    /**
     * Xác thực chữ ký MoMo (dùng cho IPN + redirect).
     * Theo tài liệu, chữ ký IPN được hash theo thứ tự alphabet của các trường sau:
     *   accessKey, amount, extraData, message, orderId, orderInfo, orderType,
     *   partnerCode, payType, requestId, responseTime, resultCode, transId
     */
    public function verifySignature(array $params): bool
    {
        $signature = $params['signature'] ?? null;
        if (!$signature) return false;

        $rawHash =
              'accessKey='    . $this->accessKey
            . '&amount='      . ($params['amount']       ?? '')
            . '&extraData='   . ($params['extraData']    ?? '')
            . '&message='     . ($params['message']      ?? '')
            . '&orderId='     . ($params['orderId']      ?? '')
            . '&orderInfo='   . ($params['orderInfo']    ?? '')
            . '&orderType='   . ($params['orderType']    ?? '')
            . '&partnerCode=' . ($params['partnerCode']  ?? '')
            . '&payType='     . ($params['payType']      ?? '')
            . '&requestId='   . ($params['requestId']    ?? '')
            . '&responseTime='. ($params['responseTime'] ?? '')
            . '&resultCode='  . ($params['resultCode']   ?? '')
            . '&transId='     . ($params['transId']      ?? '');

        $expected = hash_hmac('sha256', $rawHash, $this->secretKey);
        return hash_equals($expected, $signature);
    }
}
