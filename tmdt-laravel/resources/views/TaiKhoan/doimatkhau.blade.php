@extends('shared._layout')
@section('content')
@php
    $anh = $user && !empty($user->AnhDaiDien) ? asset('Content/Avatars/' . $user->AnhDaiDien) : asset('Content/Avatars/default.jpg');
@endphp
<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <img src="{{ $anh }}" class="rounded-circle mx-auto mb-3 shadow" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';" />
                <h5 class="fw-bold">{{ $user->HoTen ?? 'Ẩn danh' }}</h5>
                <div class="list-group list-group-flush text-start mt-3">
                    <a href="{{ route('taikhoan.thongtin') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-user me-2"></i>Thông tin cá nhân</a>
                    <a href="{{ route('taikhoan.doimatkhau') }}" class="list-group-item list-group-item-action active fw-bold border-0 rounded"><i class="fa fa-lock me-2"></i>Đổi mật khẩu</a>
                    <a href="{{ route('taikhoan.lichsu') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-history me-2"></i>Lịch sử giao dịch</a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Đổi Mật Khẩu</h3>
                    
                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif
                    
                    <form action="{{ route('taikhoan.doimatkhau') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu hiện tại</label>
                            <input type="password" name="MatKhauCu" class="form-control w-50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới</label>
                            <input type="password" name="MatKhauMoi" class="form-control w-50" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="XacNhanMatKhau" class="form-control w-50" required>
                        </div>
                        <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm">Đổi Mật Khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection