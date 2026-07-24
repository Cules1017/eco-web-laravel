<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 0; color: #333333; }
        .email-wrapper { width: 100%; background-color: #f4f5f7; padding: 40px 0; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background-color: #2563eb; padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 700; letter-spacing: 1px; }
        .content { padding: 40px 30px; }
        .content h2 { margin-top: 0; font-size: 22px; color: #1f2937; margin-bottom: 20px; }
        .content p { line-height: 1.6; color: #4b5563; margin-bottom: 25px; font-size: 15px; }
        .order-info { background-color: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0; }
        .order-info p { margin: 8px 0; font-size: 14px; color: #475569; }
        .order-info strong { color: #1e293b; display: inline-block; width: 160px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; text-align: left; padding: 12px 15px; font-size: 14px; border-bottom: 2px solid #e2e8f0; border-top: 1px solid #e2e8f0; }
        td { padding: 15px; border-bottom: 1px solid #e2e8f0; font-size: 14px; color: #475569; vertical-align: middle; }
        .item-name { font-weight: 600; color: #1e293b; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row td { font-weight: 700; color: #1e293b; font-size: 18px; border-bottom: none; border-top: 2px solid #e2e8f0; padding-top: 20px; }
        .total-price { color: #ef4444 !important; font-size: 20px; }
        .btn-container { text-align: center; margin: 40px 0 20px; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff !important; text-decoration: none; padding: 14px 35px; border-radius: 6px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }
        .footer { background-color: #f8fafc; padding: 25px; text-align: center; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 5px 0; }
        
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .status-paid { background-color: #d1fae5; color: #047857; }
        .status-unpaid { background-color: #fef3c7; color: #b45309; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <h1>{{ \App\Models\Setting::getValue('site_name', config('app.name')) }}</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <h2>Xin chào {{ $order->user->name ?? 'Quý khách' }},</h2>
                <p>Cảm ơn bạn đã tin tưởng và mua sắm tại <strong>{{ \App\Models\Setting::getValue('site_name', config('app.name')) }}</strong>. Đơn hàng của bạn đã được ghi nhận thành công và chúng tôi đang tiến hành chuẩn bị hàng.</p>

                <!-- Order Details Box -->
                <div class="order-info">
                    <p><strong>Mã đơn hàng:</strong> <span style="color: #2563eb; font-weight: 700; font-size: 16px;">#{{ $order->order_number }}</span></p>
                    <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Hình thức thanh toán:</strong> 
                        @if($order->payment_method === 'momo') Ví MoMo 
                        @elseif($order->payment_method === 'bank') Chuyển khoản ngân hàng 
                        @else Thanh toán khi nhận hàng (COD) @endif
                    </p>
                    <p><strong>Trạng thái thanh toán:</strong> 
                        @if($order->payment_status === 'paid') 
                            <span class="status-badge status-paid">Đã thanh toán</span>
                        @else 
                            <span class="status-badge status-unpaid">Chưa thanh toán</span> 
                        @endif
                    </p>
                </div>

                <!-- Order Items Table -->
                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}₫</strong></td>
                        </tr>
                        @endforeach
                        
                        <!-- Total Row -->
                        <tr class="total-row">
                            <td colspan="2" class="text-right">Tổng cộng:</td>
                            <td class="text-right total-price">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    </tbody>
                </table>

                <div class="btn-container">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn">Xem Chi Tiết Đơn Hàng</a>
                </div>
                
                <p style="margin-bottom: 0; text-align: center; color: #64748b; font-size: 14px;">Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi qua email này hoặc gọi hotline hỗ trợ khách hàng.</p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('site_name', config('app.name')) }}. All rights reserved.</p>
                <p>Cảm ơn bạn đã lựa chọn mua sắm cùng chúng tôi!</p>
            </div>
        </div>
    </div>
</body>
</html>
