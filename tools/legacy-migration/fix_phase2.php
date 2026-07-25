<?php

$dangNhapView = <<<'EOD'
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
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/dangnhap.blade.php', $dangNhapView);

$dangKyView = <<<'EOD'
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
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/dangky.blade.php', $dangKyView);

$thongTinView = <<<'EOD'
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
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/thongtin.blade.php', $thongTinView);

$doiMatKhauView = <<<'EOD'
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
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/doimatkhau.blade.php', $doiMatKhauView);

$gioHangView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <h2 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-cart-shopping text-warning me-2"></i>Giỏ Hàng Của Bạn</h2>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif

    @if (empty($gioHang) || count($gioHang) == 0)
        <div class="text-center p-5 bg-white shadow-sm rounded-4 border">
            <img src="{{ asset('Content/Images/empty-cart.png') }}" alt="Giỏ hàng trống" style="width: 150px; opacity: 0.5; margin-bottom: 20px;" onerror="this.style.display='none';">
            <h4 class="text-muted mb-3">Giỏ hàng của bạn đang trống!</h4>
            <a href="{{ route('home.index') }}" class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow-sm">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th class="text-center pe-4">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gioHang as $item)
                                        @php
                                            $anhUrl = $item['AnhBia'] ? asset('Content/Images/' . $item['AnhBia']) : asset('Content/Images/noimage.jpg');
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('sanpham.chitiet', ['id' => $item['MaSP']]) }}">
                                                        <img src="{{ $anhUrl }}" class="rounded shadow-sm me-3" style="width: 70px; height: 70px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                                    </a>
                                                    <div>
                                                        <a href="{{ route('sanpham.chitiet', ['id' => $item['MaSP']]) }}" class="text-decoration-none text-dark fw-bold d-block text-truncate" style="max-width: 200px;">
                                                            {{ $item['TenSP'] }}
                                                        </a>
                                                        <small class="text-muted">Người bán: {{ $item['NguoiBan'] ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-danger fw-bold">{{ number_format($item['DonGia'], 0, ',', '.') }}₫</td>
                                            <td>
                                                <div class="input-group input-group-sm mx-auto" style="width: 100px;">
                                                    <a href="{{ route('giohang.giam', ['id' => $item['MaSP']]) }}" class="btn btn-outline-secondary px-2"><i class="fa fa-minus"></i></a>
                                                    <input type="text" class="form-control text-center fw-bold bg-white" value="{{ $item['SoLuong'] }}" readonly>
                                                    <a href="{{ route('giohang.tang', ['id' => $item['MaSP']]) }}" class="btn btn-outline-secondary px-2"><i class="fa fa-plus"></i></a>
                                                </div>
                                            </td>
                                            <td class="text-danger fw-bold">{{ number_format($item['ThanhTien'], 0, ',', '.') }}₫</td>
                                            <td class="text-center pe-4">
                                                <a href="{{ route('giohang.xoa', ['id' => $item['MaSP']]) }}" class="text-secondary hover-danger transition-02" title="Xóa">
                                                    <i class="fa-solid fa-trash-can fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng Tiền & Thanh Toán -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky top-sticky">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-3">Tóm Tắt Đơn Hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính ({{ $tongSoLuong }} sản phẩm):</span>
                            <span class="fw-bold">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span class="text-muted">Phí giao hàng:</span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="text-danger fw-bold fs-4">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                        </div>

                        <a href="{{ route('hoadon.dathang') }}" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill shadow-sm py-3 mb-2">
                            Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{ route('home.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-danger:hover { color: #dc3545 !important; }
    .transition-02 { transition: 0.2s ease; }
    .top-sticky { top: 90px; }
</style>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/giohang/index.blade.php', $gioHangView);

echo "Phase 2: Tài khoản & Giỏ hàng views updated!\n";
