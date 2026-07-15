@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-users me-2 text-primary"></i>Quản Lý Người Dùng</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tài khoản</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số ĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nguoiDungs as $nd)
                        <tr>
                            <td class="fw-bold">{{ $nd->TaiKhoan }}</td>
                            <td>{{ $nd->HoTen }}</td>
                            <td>{{ $nd->Email }}</td>
                            <td>{{ $nd->SDT }}</td>
                            <td>
                                @if ($nd->VaiTro == 'Admin')
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if ($nd->Khoa)
                                    <span class="badge bg-danger">Khóa</span>
                                @else
                                    <span class="badge bg-success">Hoạt động</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($nd->MaKH != session('user')->MaKH)
                                    <form action="{{ route('admin.doitrangthainguoidung', ['id' => $nd->MaKH]) }}" method="POST">
                                        @csrf
                                        @if ($nd->Khoa)
                                            <button class="btn btn-sm btn-success rounded-pill px-3">Mở khóa</button>
                                        @else
                                            <button class="btn btn-sm btn-danger rounded-pill px-3">Khóa</button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $nguoiDungs->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection