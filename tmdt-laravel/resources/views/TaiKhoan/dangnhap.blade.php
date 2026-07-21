@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="text-center mb-3">Đăng nhập</h4>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="post" action="{{ route('taikhoan.dangnhap') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Tài khoản</label>
                        <input name="taikhoan" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input name="matkhau" type="password" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>

                <p class="text-center mt-3">
                    Chưa có tài khoản? <a href="{{ route('taikhoan.dangky') }}">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
