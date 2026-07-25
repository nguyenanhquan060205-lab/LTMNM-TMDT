@extends('Shared._Layout')
@section('title', 'Đăng ký')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="text-center mb-3">Đăng ký</h4>

                @if (session('Error'))
                    <div class="alert alert-danger">{{ session('Error') }}</div>
                @endif
                @if (session('Success'))
                    <div class="alert alert-success">{{ session('Success') }}</div>
                @endif

                <form method="post" action="{{ url('/taikhoan/dangky') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Họ tên</label>
                        <input class="form-control" name="HoTen" required />
                    </div>
                    <div class="mb-3">
                        <label>Tài khoản</label>
                        <input class="form-control" name="TaiKhoan" required />
                    </div>
                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input class="form-control" name="MatKhau" type="password" required />
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input class="form-control" name="Email" required />
                    </div>
                    <div class="mb-3">
                        <label>SĐT</label>
                        <input class="form-control" name="SDT" />
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ</label>
                        <textarea class="form-control" name="DiaChi"></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 text-white fw-bold">Đăng ký</button>
                </form>

                <p class="text-center mt-3">
                    Đã có tài khoản? <a href="{{ url('/taikhoan/dangnhap') }}" class="text-warning">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
