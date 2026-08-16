@extends('layouts.admin')

@section('title', 'Cấu hình Game Lắc Hũ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cấu hình Game Tặng Voucher</h3>
                    <a href="{{ route('admin.game.questions') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> Quản lý Câu hỏi
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.game.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Trạng thái Game</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="game_enabled" name="game_enabled" {{ $configs['game_enabled'] === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="game_enabled">Bật / Tắt Game</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="daily_questions" class="form-label">Số câu hỏi mỗi ngày (Mặc định)</label>
                                    <input type="number" class="form-control" id="daily_questions" name="daily_questions" value="{{ $configs['daily_questions'] }}" required min="1">
                                    <small class="text-muted">Khách hàng sẽ có lượt trả lời này vào mỗi ngày.</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="streak_required" class="form-label">Số câu đúng liên tiếp để Lắc Hũ</label>
                                    <input type="number" class="form-control" id="streak_required" name="streak_required" value="{{ $configs['streak_required'] }}" required min="1">
                                    <small class="text-muted">Ví dụ: Trả lời đúng 3 câu liên tiếp thì được 1 lần lắc hũ.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="win_probability" class="form-label">Tỷ lệ trúng Voucher (%)</label>
                                    <input type="number" class="form-control" id="win_probability" name="win_probability" value="{{ $configs['win_probability'] }}" required min="0" max="100">
                                    <small class="text-muted">Xác suất trúng khi lắc hũ (từ 0 đến 100).</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="game_voucher_id" class="form-label">Chọn Voucher thưởng</label>
                                    <select class="form-select form-control" id="game_voucher_id" name="game_voucher_id">
                                        <option value="">-- Không tặng voucher (Chỉ chơi cho vui) --</option>
                                        @foreach($vouchers as $voucher)
                                            <option value="{{ $voucher->id }}" {{ $configs['game_voucher_id'] == $voucher->id ? 'selected' : '' }}>
                                                {{ $voucher->code }} - Giảm 
                                                @if($voucher->discount_type == 'percent')
                                                    {{ $voucher->discount_value }}%
                                                @else
                                                    {{ number_format($voucher->discount_value) }} VNĐ
                                                @endif
                                                (Còn: {{ $voucher->usage_limit ? $voucher->usage_limit - $voucher->used_count : 'Không giới hạn' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Voucher này sẽ được tặng khi khách hàng lắc hũ trúng.</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Lưu cấu hình
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
