<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo doanh thu</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
        .date-range { font-style: italic; color: #666; margin-bottom: 20px; }
        .summary-box { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-box td { width: 33.33%; padding: 15px; border: 1px solid #ddd; text-align: center; }
        .summary-value { font-size: 20px; font-weight: bold; color: #0d6efd; margin-top: 10px; }
        .summary-label { font-size: 14px; color: #555; text-transform: uppercase; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h3 { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-top: 30px; font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO TỔNG QUAN DOANH THU</div>
        <div class="date-range">
            @if($date_from || $date_to)
                Thời gian: {{ $date_from ? \Carbon\Carbon::parse($date_from)->format('d/m/Y') : 'Từ đầu' }} - {{ $date_to ? \Carbon\Carbon::parse($date_to)->format('d/m/Y') : 'Đến nay' }}
            @else
                Thời gian: Toàn thời gian
            @endif
        </div>
        <div>Ngày xuất báo cáo: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-label">Tổng doanh thu</div>
                <div class="summary-value" style="color: #198754;">{{ number_format($revenue, 0, ',', '.') }} đ</div>
            </td>
            <td>
                <div class="summary-label">Tổng số đơn hàng</div>
                <div class="summary-value">{{ $order_count }}</div>
            </td>
            <td>
                <div class="summary-label">Tổng khách hàng</div>
                <div class="summary-value" style="color: #dc3545;">{{ $user_count }}</div>
            </td>
        </tr>
    </table>

    <h3>Top Sản Phẩm Bán Chạy Nhất</h3>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">STT</th>
                <th style="width: 50%;">Tên sản phẩm</th>
                <th class="text-right" style="width: 20%;">Giá bán</th>
                <th class="text-center" style="width: 20%;">Đã bán (sp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_products as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td class="text-right">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                <td class="text-center"><strong>{{ $product->total_sold }}</strong></td>
            </tr>
            @endforeach
            
            @if($top_products->isEmpty())
            <tr>
                <td colspan="4" class="text-center">Không có dữ liệu trong khoảng thời gian này.</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <div style="margin-top: 50px; text-align: right; padding-right: 50px;">
        <p><strong>Người lập biểu</strong></p>
        <p style="font-style: italic; color: #777; margin-top: 5px;">(Ký và ghi rõ họ tên)</p>
        <p style="margin-top: 80px;">________________________</p>
    </div>
</body>
</html>
