@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách người dùng</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Vai trò</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>
                                            <span class="masked-text">{{ preg_replace('/(?<=.{2}).(?=[^@]*?@)/', '*', $user->email) }}</span>
                                            <span class="full-text d-none">{{ $user->email }}</span>
                                            <i class="fas fa-eye text-muted ms-2 toggle-eye" style="cursor: pointer;" title="Hiện/Ẩn"></i>
                                        </td>
                                        <td>
                                            @if($user->phone)
                                                <span class="masked-text">{{ strlen($user->phone) >= 6 ? substr($user->phone, 0, 3) . str_repeat('*', strlen($user->phone) - 6) . substr($user->phone, -3) : '***' }}</span>
                                                <span class="full-text d-none">{{ $user->phone }}</span>
                                                <i class="fas fa-eye text-muted ms-2 toggle-eye" style="cursor: pointer;" title="Hiện/Ẩn"></i>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->is_admin ? 'danger' : 'info' }}">
                                                {{ $user->is_admin ? 'Admin' : 'User' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm"
                                                        onclick="return confirm('Bạn có chắc chắn muốn đổi vai trò người dùng này?');">
                                                        {{ $user->is_admin ? 'Hạ quyền Admin' : 'Nâng lên Admin' }}
                                                    </button>
                                                </form>
                                                @if(!$user->is_admin || ($user->is_admin && $adminCount > 1))
                                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có người dùng nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-eye').forEach(icon => {
        icon.addEventListener('click', function() {
            const cell = this.closest('td');
            const masked = cell.querySelector('.masked-text');
            const full = cell.querySelector('.full-text');
            
            if (full.classList.contains('d-none')) {
                full.classList.remove('d-none');
                masked.classList.add('d-none');
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                full.classList.add('d-none');
                masked.classList.remove('d-none');
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
});
</script>
@endpush 