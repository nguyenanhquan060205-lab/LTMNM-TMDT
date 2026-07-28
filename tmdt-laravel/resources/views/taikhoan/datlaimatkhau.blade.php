@extends('shared._layout')
@section('title', 'Đặt lại Mật Khẩu')
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4" style="font-weight: 600; color: #333;">Đặt Lại Mật Khẩu</h2>
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="/taikhoan/datlaimatkhau" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 500;">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="MatKhauMoi" class="form-control" placeholder="Nhập mật khẩu mới" required minlength="6">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 500;">Xác nhận Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="XacNhanMatKhauMoi" class="form-control" placeholder="Nhập lại mật khẩu mới" required minlength="6">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3 rounded-3" style="font-weight: 600;">
                            Cập nhật Mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
