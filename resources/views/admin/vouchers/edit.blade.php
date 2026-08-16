@extends('layouts.admin')
@section('title', 'Sửa Mã giảm giá')
@section('header', 'Sửa Mã giảm giá')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required style="text-transform: uppercase;">
                </div>
                <div class="col-md-3 form-group">
                    <label>Loại giảm giá <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-control" required>
                        <option value="fixed" {{ old('discount_type', $voucher->discount_type) == 'fixed' ? 'selected' : '' }}>Giảm tiền trực tiếp</option>
                        <option value="percent" {{ old('discount_type', $voucher->discount_type) == 'percent' ? 'selected' : '' }}>Giảm phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Mức giảm <span class="text-danger">*</span></label>
                    <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', (int)$voucher->discount_value) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Đơn tối thiểu áp dụng</label>
                    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', (int)$voucher->min_order_amount) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Số lượt sử dụng tối đa (Bỏ trống = Vô hạn)</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Ngày bắt đầu (Tùy chọn)</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $voucher->start_date ? $voucher->start_date->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Ngày kết thúc (Tùy chọn)</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $voucher->end_date ? $voucher->end_date->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="isActive">Kích hoạt mã</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
