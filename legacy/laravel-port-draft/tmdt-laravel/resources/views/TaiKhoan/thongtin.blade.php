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
                <p class="text-muted small mb-3">{{ $user->Email ?? 'Chưa cập nhật Email' }}</p>
                <div class="list-group list-group-flush text-start">
                    <a href="{{ route('taikhoan.thongtin') }}" class="list-group-item list-group-item-action active fw-bold border-0 rounded"><i class="fa fa-user me-2"></i>Thông tin cá nhân</a>
                    <a href="{{ route('taikhoan.doimatkhau') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-lock me-2"></i>Đổi mật khẩu</a>
                    <a href="{{ route('taikhoan.lichsu') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-history me-2"></i>Lịch sử giao dịch</a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Hồ Sơ Của Tôi</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif
                    
                    <form action="{{ route('taikhoan.capnhatthongtin') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và tên</label>
                                <input type="text" name="HoTen" class="form-control" value="{{ $user->HoTen }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="SDT" class="form-control" value="{{ $user->SDT }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="Email" class="form-control" value="{{ $user->Email }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                <input type="text" name="DiaChi" class="form-control" value="{{ $user->DiaChi }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ảnh đại diện</label>
                            <input type="file" name="AnhDaiDien" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning px-4 py-2 fw-bold shadow-sm">Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
