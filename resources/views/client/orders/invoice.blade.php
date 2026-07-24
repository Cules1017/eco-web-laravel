<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa Đơn - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px; }
        .header { width: 100%; margin-bottom: 20px; }
        .header table { width: 100%; }
        .header td { padding: 5px; vertical-align: top; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .store-info { text-align: right; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; }
        .total-amount { font-size: 18px; color: #d9534f; }
        h3 { margin-bottom: 5px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="title">HÓA ĐƠN ĐIỆN TỬ</td>
                <td class="store-info">
                    <strong>{{ \App\Models\Setting::getValue('site_name', config('app.name')) }}</strong><br>
                    Ngày in: {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
        
        <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 20px;">

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <h3>Thông tin khách hàng:</h3>
                    <strong>Họ tên:</strong> {{ $order->address?->full_name ?? $order->user?->name }}<br>
                    <strong>Điện thoại:</strong> {{ $order->address?->phone ?? '' }}<br>
                    <strong>Địa chỉ:</strong> 
                    @if($order->address)
                        {{ implode(', ', array_filter([
                            $order->address->address_line,
                            $order->address->ward?->name,
                            $order->address->district?->name,
                            $order->address->province?->name
                        ])) }}
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    <h3>Thông tin đơn hàng:</h3>
                    <strong>Mã đơn:</strong> {{ $order->order_number }}<br>
                    <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Thanh toán:</strong> 
                    @if($order->payment_method === 'momo') Ví MoMo 
                    @elseif($order->payment_method === 'bank') Chuyển khoản 
                    @else Thu hộ (COD) @endif
                    <br>
                    <strong>Trạng thái:</strong> {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">STT</th>
                    <th style="width: 45%;">Tên sản phẩm</th>
                    <th class="text-center" style="width: 10%;">SL</th>
                    <th class="text-right" style="width: 20%;">Đơn giá</th>
                    <th class="text-right" style="width: 20%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->product?->name ?? 'Sản phẩm' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" class="text-right">TỔNG CỘNG:</td>
                    <td class="text-right total-amount">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: center; margin-top: 50px;">
            <p><strong>Cảm ơn quý khách đã mua hàng!</strong></p>
            <p style="font-size: 12px; color: #777;">(Hóa đơn này được tạo tự động từ hệ thống {{ \App\Models\Setting::getValue('site_name', config('app.name')) }})</p>
        </div>
    </div>
</body>
</html>
