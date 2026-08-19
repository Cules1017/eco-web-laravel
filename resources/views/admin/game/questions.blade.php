@extends('layouts.admin')

@section('title', 'Ngân hàng Câu hỏi Quiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Ngân hàng Câu hỏi Đúng/Sai</h3>
                    <div>
                        <a href="{{ route('admin.game.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Trở về cấu hình
                        </a>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Thêm câu hỏi
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Câu hỏi</th>
                                    <th>Đáp án đúng</th>
                                    <th>Giải thích (Khi sai)</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>{{ $question->question }}</td>
                                        <td>
                                            @if($question->is_correct_true)
                                                <span class="badge bg-success">Đúng</span>
                                            @else
                                                <span class="badge bg-danger">Sai</span>
                                            @endif
                                        </td>
                                        <td>{{ $question->explanation ?: '-' }}</td>
                                        <td>
                                            @if($question->is_active)
                                                <span class="badge bg-success">Đang bật</span>
                                            @else
                                                <span class="badge bg-secondary">Đã tắt</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $question->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.game.questions.destroy', $question) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.game.questions.update', $question) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Sửa câu hỏi</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nội dung câu hỏi</label>
                                                            <textarea name="question" class="form-control" rows="3" required>{{ $question->question }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Mệnh đề này là:</label>
                                                            <select name="is_correct_true" class="form-select form-control">
                                                                <option value="1" {{ $question->is_correct_true ? 'selected' : '' }}>Đúng</option>
                                                                <option value="0" {{ !$question->is_correct_true ? 'selected' : '' }}>Sai</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Giải thích (Tùy chọn)</label>
                                                            <textarea name="explanation" class="form-control" rows="2">{{ $question->explanation }}</textarea>
                                                        </div>
                                                        <div class="mb-3 form-check">
                                                            <input type="checkbox" class="form-check-input" name="is_active" value="1" id="active{{ $question->id }}" {{ $question->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="active{{ $question->id }}">Kích hoạt</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Chưa có câu hỏi nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.game.questions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm câu hỏi mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nội dung câu hỏi</label>
                        <textarea name="question" class="form-control" rows="3" required placeholder="VD: Túi nilon cần 500 năm để phân hủy hoàn toàn?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mệnh đề này là:</label>
                        <select name="is_correct_true" class="form-select form-control">
                            <option value="1">Đúng</option>
                            <option value="0">Sai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giải thích (Tùy chọn)</label>
                        <textarea name="explanation" class="form-control" rows="2" placeholder="Hiển thị khi khách hàng trả lời sai."></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="activeNew" checked>
                        <label class="form-check-label" for="activeNew">Kích hoạt</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
