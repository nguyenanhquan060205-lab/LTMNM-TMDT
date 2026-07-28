@extends('shared._layout')
@section('title', 'Quên Mật Khẩu')
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="font-weight: 600; color: #333;">Quên Mật Khẩu</h2>
                    <p class="text-center text-muted mb-4">Vui lòng nhập địa chỉ email của bạn. Chúng tôi sẽ gửi một liên kết để đặt lại mật khẩu.</p>
                    
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="/taikhoan/quenmatkhau" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 500;">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="Email" class="form-control" placeholder="Nhập email của bạn" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3 rounded-3" style="font-weight: 600;">
                            Gửi liên kết đặt lại mật khẩu
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('taikhoan.dangnhap') }}" class="text-decoration-none">
                            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại Đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
