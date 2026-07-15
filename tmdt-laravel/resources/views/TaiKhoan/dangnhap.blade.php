@extends('shared._layout')
@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-4 text-dark">Đăng Nhập</h2>
                    
                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('taikhoan.dangnhap') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tài khoản</label>
                            <input type="text" name="TaiKhoan" class="form-control form-control-lg bg-light" required autofocus placeholder="Nhập tên tài khoản">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="MatKhau" class="form-control form-control-lg bg-light" required placeholder="Nhập mật khẩu">
                        </div>
                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm mb-3">Đăng nhập</button>
                    </form>
                    <div class="text-center mt-3">
                        <span class="text-muted">Chưa có tài khoản?</span> <a href="{{ route('taikhoan.dangky') }}" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection