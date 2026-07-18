@extends('layouts.admin')

@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">{{ __('messages.admin_dashboard') }}</h1>
            <p>{{ __('messages.welcome_admin_dashboard') }}</p>
        </div>
    </div>
    <form method="GET" class="mb-4">
        <div class="form-row align-items-end">
            <div class="col-md-3">
                <label for="date_from">Từ ngày</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $date_from }}">
            </div>
            <div class="col-md-3">
                <label for="date_to">Đến ngày</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $date_to }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Lọc</button>
            </div>
        </div>
    </form>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="info-box bg-white">
                <span class="info-box-icon text-muted"><i class="fas fa-shopping-bag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Tổng số đơn</span>
                    <span class="info-box-number mb-0 h4">{{ $order_count }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-white">
                <span class="info-box-icon text-muted"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Doanh thu</span>
                    <span class="info-box-number mb-0 h4">{{ number_format($revenue) }}đ</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-white">
                <span class="info-box-icon text-muted"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted">Số khách hàng</span>
                    <span class="info-box-number mb-0 h4">{{ $user_count }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header border-0 font-weight-bold">Top sản phẩm bán chạy</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-right">Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($top_products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td class="text-right">{{ $product->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header border-0 font-weight-bold">Biểu đồ doanh thu (mẫu)</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header border-0 font-weight-bold">Tỷ lệ trạng thái đơn hàng</div>
                <div class="card-body">
                    <canvas id="orderStatusPie" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header border-0 font-weight-bold">Số lượng đơn theo ngày (mẫu)</div>
                <div class="card-body">
                    <canvas id="orderLineChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header border-0 font-weight-bold">Số lượng đơn theo trạng thái (mẫu)</div>
                <div class="card-body">
                    <canvas id="orderStatusBar" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Cấu hình bảng màu monochrome/slate
const slatePalette = [
    'rgba(71, 85, 105, 0.8)',   // Slate 600
    'rgba(100, 116, 139, 0.8)', // Slate 500
    'rgba(148, 163, 184, 0.8)', // Slate 400
    'rgba(15, 23, 42, 0.8)',    // Slate 900
    'rgba(203, 213, 225, 0.8)'  // Slate 300
];
const slateBorders = [
    'rgba(71, 85, 105, 1)',
    'rgba(100, 116, 139, 1)',
    'rgba(148, 163, 184, 1)',
    'rgba(15, 23, 42, 1)',
    'rgba(203, 213, 225, 1)'
];

// Biểu đồ doanh thu (mẫu)
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
        datasets: [{
            label: 'Doanh thu',
            data: [12000000, 15000000, 10000000, 18000000, 20000000, 17000000, 22000000],
            backgroundColor: slatePalette[0],
            borderColor: slateBorders[0],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
// Biểu đồ tròn tỷ lệ trạng thái đơn hàng (mẫu)
const pieCtx = document.getElementById('orderStatusPie').getContext('2d');
const orderStatusPie = new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [10, 15, 8, 20, 5],
            backgroundColor: slatePalette,
            borderColor: slateBorders,
            borderWidth: 1
        }]
    }
});
// Biểu đồ đường số lượng đơn theo ngày (mẫu)
const lineCtx = document.getElementById('orderLineChart').getContext('2d');
const orderLineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['01/06', '02/06', '03/06', '04/06', '05/06', '06/06', '07/06'],
        datasets: [{
            label: 'Số lượng đơn',
            data: [5, 8, 6, 10, 7, 12, 9],
            fill: false,
            borderColor: slateBorders[1],
            backgroundColor: slatePalette[1],
            tension: 0.1
        }]
    }
});
// Biểu đồ cột số lượng đơn theo trạng thái (mẫu)
const barCtx = document.getElementById('orderStatusBar').getContext('2d');
const orderStatusBar = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Chờ xử lý', 'Đang xử lý', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            label: 'Số lượng đơn',
            data: [10, 15, 8, 20, 5],
            backgroundColor: slatePalette,
            borderColor: slateBorders,
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
@endsection 