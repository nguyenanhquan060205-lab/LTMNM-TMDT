@extends('shared._layout')
@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-4 text-dark">Đăng Ký Tài Khoản</h2>
                    
                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('taikhoan.dangky') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ tên</label>
                                <input type="text" name="HoTen" class="form-control bg-light" required placeholder="Nhập họ và tên">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="SDT" class="form-control bg-light" required placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tài khoản</label>
                            <input type="text" name="TaiKhoan" class="form-control bg-light" required placeholder="Nhập tên đăng nhập">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mật khẩu</label>
                                <input type="password" name="MatKhau" class="form-control bg-light" required placeholder="Nhập mật khẩu">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                                <input type="password" name="XacNhanMatKhau" class="form-control bg-light" required placeholder="Xác nhận mật khẩu">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm mb-3">Đăng ký</button>
                    </form>
                    <div class="text-center mt-3">
                        <span class="text-muted">Đã có tài khoản?</span> <a href="{{ route('taikhoan.dangnhap') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection