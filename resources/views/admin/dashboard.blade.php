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
    <form method="GET" class="row g-2 mb-4 align-items-end">
        <div class="col-md-3">
            <label for="date_from" class="form-label">Từ ngày</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $date_from }}">
        </div>
        <div class="col-md-3">
            <label for="date_to" class="form-label">Đến ngày</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $date_to }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn</h5>
                    <p class="card-text fs-3">{{ $order_count }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <p class="card-text fs-3">{{ number_format($revenue) }}đ</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Số khách hàng</h5>
                    <p class="card-text fs-3">{{ $user_count }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Top sản phẩm bán chạy</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($top_products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_sold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Biểu đồ doanh thu (mẫu)</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Tỷ lệ trạng thái đơn hàng</div>
                <div class="card-body">
                    <canvas id="orderStatusPie" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Số lượng đơn theo ngày (mẫu)</div>
                <div class="card-body">
                    <canvas id="orderLineChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Số lượng đơn theo trạng thái (mẫu)</div>
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
// Biểu đồ doanh thu (mẫu)
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
        datasets: [{
            label: 'Doanh thu',
            data: [12000000, 15000000, 10000000, 18000000, 20000000, 17000000, 22000000],
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
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
            backgroundColor: [
                'rgba(255, 205, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(201, 203, 207, 0.7)'
            ]
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
            borderColor: 'rgba(255, 99, 132, 1)',
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
            backgroundColor: [
                'rgba(255, 205, 86, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(201, 203, 207, 0.7)'
            ],
            borderColor: [
                'rgba(255, 205, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(201, 203, 207, 1)'
            ],
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