@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="text-center mb-3">Đăng ký</h4>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="post" action="{{ route('taikhoan.dangky') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Họ tên</label>
                        <input class="form-control" name="HoTen" value="{{ old('HoTen') }}" required />
                    </div>
                    <div class="mb-3">
                        <label>Tài khoản</label>
                        <input class="form-control" name="TaiKhoan" value="{{ old('TaiKhoan') }}" required />
                    </div>
                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input class="form-control" name="MatKhau" type="password" required />
                    </div>
                    <div class="mb-3">
                        <label>Xác nhận mật khẩu</label>
                        <input class="form-control" name="XacNhanMatKhau" type="password" required />
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input class="form-control" name="Email" type="email" value="{{ old('Email') }}" required />
                    </div>
                    <div class="mb-3">
                        <label>SĐT</label>
                        <input class="form-control" name="SDT" value="{{ old('SDT') }}" />
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ</label>
                        <textarea class="form-control" name="DiaChi">{{ old('DiaChi') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                </form>

                <p class="text-center mt-3">
                    Đã có tài khoản? <a href="{{ route('taikhoan.dangnhap') }}">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
