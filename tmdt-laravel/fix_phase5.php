<?php

$layoutAdminView = <<<'EOD'
@php
    $user = session('user');
    $anh = $user && !empty($user->AnhDaiDien) ? asset('Content/Avatars/' . $user->AnhDaiDien) : asset('Content/Avatars/default.jpg');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $Title ?? 'Quản Trị Hệ Thống' }} - TechSecond Admin</title>
    <link rel="stylesheet" href="{{ asset('Content/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('Scripts/bootstrap.bundle.min.js') }}"></script>
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { min-height: 100vh; background: #343a40; color: #fff; }
        .sidebar .nav-link { color: rgba(255,255,255,.75); padding: 12px 20px; font-weight: 500; transition: 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); border-left: 4px solid #ffc107; }
        .content { width: 100%; }
        .navbar-admin { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.04); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar d-flex flex-column flex-shrink-0" style="width: 250px;">
            <a href="{{ route('admin.index') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none p-3 border-bottom border-secondary">
                <i class="fa-solid fa-shield-halved fa-2x text-warning me-2"></i>
                <span class="fs-5 fw-bold">Admin Panel</span>
            </a>
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="fa fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quanlysanpham') }}" class="nav-link {{ request()->routeIs('admin.quanlysanpham') ? 'active' : '' }}">
                        <i class="fa fa-box me-2"></i> Duyệt sản phẩm
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quanlynguoidung') }}" class="nav-link {{ request()->routeIs('admin.quanlynguoidung') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i> Quản lý người dùng
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quanlydonhang') }}" class="nav-link {{ request()->routeIs('admin.quanlydonhang') ? 'active' : '' }}">
                        <i class="fa fa-shopping-cart me-2"></i> Đơn hàng hệ thống
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.quanlykhieunai') }}" class="nav-link {{ request()->routeIs('admin.quanlykhieunai') ? 'active' : '' }}">
                        <i class="fa fa-exclamation-triangle me-2"></i> Xử lý khiếu nại
                    </a>
                </li>
                <li>
                    <a href="{{ route('loaisanpham.index') }}" class="nav-link {{ request()->routeIs('loaisanpham.*') ? 'active' : '' }}">
                        <i class="fa fa-tags me-2"></i> Danh mục sản phẩm
                    </a>
                </li>
                <li>
                    <a href="{{ route('home.index') }}" class="nav-link text-info mt-4 border-top border-secondary pt-3">
                        <i class="fa fa-globe me-2"></i> Trở về trang chính
                    </a>
                </li>
            </ul>
        </div>

        <!-- CONTENT -->
        <div class="content d-flex flex-column">
            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-expand-lg navbar-light navbar-admin px-4 py-3">
                <div class="container-fluid">
                    <h4 class="mb-0 fw-bold text-dark">{{ $Title ?? 'Quản Trị' }}</h4>
                    <div class="d-flex align-items-center ms-auto">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <img src="{{ $anh }}" width="32" height="32" class="rounded-circle me-2 border shadow-sm">
                                <strong>{{ $user->TaiKhoan ?? 'Admin' }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ route('taikhoan.thongtin') }}">Thông tin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('taikhoan.dangxuat') }}">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- MAIN BODY -->
            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    @yield('scripts')
</body>
</html>
EOD;
file_put_contents(__DIR__ . '/resources/views/shared/_layoutadmin.blade.php', $layoutAdminView);

$adminIndexView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Sản Phẩm Đang Chờ</h6>
                    <h2 class="fw-bold mb-0">{{ $sanPhamChoDuyet }}</h2>
                </div>
                <i class="fa fa-box fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Tổng Người Dùng</h6>
                    <h2 class="fw-bold mb-0">{{ $tongNguoiDung }}</h2>
                </div>
                <i class="fa fa-users fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Đơn Hàng Hôm Nay</h6>
                    <h2 class="fw-bold mb-0">{{ $donHangMoi }}</h2>
                </div>
                <i class="fa fa-shopping-cart fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Khiếu Nại Mới</h6>
                    <h2 class="fw-bold mb-0">{{ $khieuNaiMoi }}</h2>
                </div>
                <i class="fa fa-exclamation-triangle fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Hoạt động gần đây</h5>
                <p class="text-muted">Hệ thống đang hoạt động ổn định.</p>
                <a href="{{ route('admin.quanlysanpham') }}" class="btn btn-primary rounded-pill px-4">Duyệt sản phẩm ngay</a>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/admin/index.blade.php', $adminIndexView);

$quanLySanPhamView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-box me-2 text-warning"></i>Quản Lý Sản Phẩm</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên SP</th>
                        <th>Người đăng</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sanPhams as $sp)
                        <tr>
                            <td class="fw-bold text-truncate" style="max-width: 200px;">{{ $sp->TenSP }}</td>
                            <td>{{ $sp->nguoiDung->TaiKhoan ?? 'N/A' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($sp->Gia, 0, ',', '.') }}₫</td>
                            <td>{{ $sp->SoLuong }}</td>
                            <td>{{ \Carbon\Carbon::parse($sp->NgayDang)->format('d/m/Y') }}</td>
                            <td>
                                @if ($sp->TrangThai == 'Đã duyệt')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif ($sp->TrangThai == 'Từ chối')
                                    <span class="badge bg-danger">Từ chối</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.doitrangthai', ['id' => $sp->MaSP]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Đã duyệt">
                                    <button class="btn btn-sm btn-success rounded-pill px-2 py-1" title="Duyệt" {{ $sp->TrangThai == 'Đã duyệt' ? 'disabled' : '' }}><i class="fa fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.doitrangthai', ['id' => $sp->MaSP]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Từ chối">
                                    <button class="btn btn-sm btn-danger rounded-pill px-2 py-1" title="Từ chối" {{ $sp->TrangThai == 'Từ chối' ? 'disabled' : '' }}><i class="fa fa-times"></i></button>
                                </form>
                                <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-info text-white rounded-pill px-2 py-1" title="Xem" target="_blank"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sanPhams->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/admin/quanlysanpham.blade.php', $quanLySanPhamView);

$quanLyNguoiDungView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-users me-2 text-primary"></i>Quản Lý Người Dùng</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tài khoản</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số ĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nguoiDungs as $nd)
                        <tr>
                            <td class="fw-bold">{{ $nd->TaiKhoan }}</td>
                            <td>{{ $nd->HoTen }}</td>
                            <td>{{ $nd->Email }}</td>
                            <td>{{ $nd->SDT }}</td>
                            <td>
                                @if ($nd->VaiTro == 'Admin')
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if ($nd->Khoa)
                                    <span class="badge bg-danger">Khóa</span>
                                @else
                                    <span class="badge bg-success">Hoạt động</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($nd->MaKH != session('user')->MaKH)
                                    <form action="{{ route('admin.doitrangthainguoidung', ['id' => $nd->MaKH]) }}" method="POST">
                                        @csrf
                                        @if ($nd->Khoa)
                                            <button class="btn btn-sm btn-success rounded-pill px-3">Mở khóa</button>
                                        @else
                                            <button class="btn btn-sm btn-danger rounded-pill px-3">Khóa</button>
                                        @endif
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $nguoiDungs->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/admin/quanlynguoidung.blade.php', $quanLyNguoiDungView);

$quanLyDonHangView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-shopping-cart me-2 text-success"></i>Quản Lý Đơn Hàng</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Khách đặt</th>
                        <th>Tổng tiền</th>
                        <th>Ngày lập</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDons as $hd)
                        <tr>
                            <td class="fw-bold">#{{ $hd->MaHD }}</td>
                            <td>{{ $hd->nguoiDung->HoTen ?? 'N/A' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($hd->TongTien, 0, ',', '.') }}₫</td>
                            <td>{{ \Carbon\Carbon::parse($hd->NgayLap)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($hd->TinhTrang == 'Đã hoàn thành')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @elseif ($hd->TinhTrang == 'Đã hủy')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $hd->TinhTrang }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('taikhoan.chitiethoadon', ['id' => $hd->MaHD]) }}" class="btn btn-sm btn-info text-white rounded-pill px-3">Xem</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $hoaDons->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/admin/quanlydonhang.blade.php', $quanLyDonHangView);

$quanLyKhieuNaiView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-exclamation-triangle me-2 text-danger"></i>Quản Lý Khiếu Nại</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã KN</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($khieuNais as $kn)
                        <tr>
                            <td class="fw-bold">#{{ $kn->MaKN }}</td>
                            <td>{{ $kn->nguoiDung->TaiKhoan ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('sanpham.chitiet', ['id' => $kn->MaSP]) }}" class="text-primary text-decoration-none">SP #{{ $kn->MaSP }}</a>
                            </td>
                            <td><div class="text-truncate" style="max-width: 250px;">{{ $kn->MoTa }}</div></td>
                            <td>
                                @if ($kn->TrangThai == 'Chưa xử lý')
                                    <span class="badge bg-warning text-dark">Chưa xử lý</span>
                                @else
                                    <span class="badge bg-success">Đã xử lý</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.capnhattrangthaikn', ['id' => $kn->MaKN]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success rounded-pill px-2 py-1" title="Đã giải quyết" {{ $kn->TrangThai == 'Đã xử lý' ? 'disabled' : '' }}><i class="fa fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.xoakhieunai', ['id' => $kn->MaKN]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger rounded-pill px-2 py-1" title="Xóa" onclick="return confirm('Xóa khiếu nại này?');"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $khieuNais->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/admin/quanlykhieunai.blade.php', $quanLyKhieuNaiView);

// Tin nhắn, khiếu nại view (Non-admin)
$taoKhieuNaiView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center text-dark"><i class="fa fa-flag text-danger me-2"></i>Gửi Báo Cáo / Khiếu Nại</h3>
                    
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <strong>Sản phẩm:</strong> {{ $sanPham->TenSP }}<br>
                        Người bán: {{ $sanPham->nguoiDung->HoTen ?? 'N/A' }}
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('khieunai.taokhieunai', ['id' => $sanPham->MaSP]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chi tiết vi phạm</label>
                            <textarea name="MoTa" class="form-control bg-light" rows="5" placeholder="Mô tả lý do bạn báo cáo sản phẩm này (hàng giả, lừa đảo, sai mô tả...)" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow-sm">Gửi Báo Cáo</button>
                        <a href="{{ route('sanpham.chitiet', ['id' => $sanPham->MaSP]) }}" class="btn btn-outline-secondary w-100 mt-2 rounded-pill">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
if(!is_dir(__DIR__ . '/resources/views/khieunai')) mkdir(__DIR__ . '/resources/views/khieunai');
file_put_contents(__DIR__ . '/resources/views/khieunai/taokhieunai.blade.php', $taoKhieuNaiView);

$tinNhanView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Danh sách user -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0"><i class="fa-regular fa-comments text-primary me-2"></i>Tin nhắn</h5>
                </div>
                <div class="card-body p-0" style="height: 500px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @foreach ($users as $u)
                            @php
                                $uAnh = $u->AnhDaiDien ? asset('Content/Avatars/' . $u->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                                $isActive = $activeUser && $activeUser->MaKH == $u->MaKH;
                            @endphp
                            <a href="{{ route('tinnhan.index', ['userId' => $u->MaKH]) }}" class="list-group-item list-group-item-action p-3 {{ $isActive ? 'bg-light border-start border-4 border-warning' : '' }}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $uAnh }}" class="rounded-circle me-3 border" width="45" height="45" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $u->TaiKhoan }}</h6>
                                        <small class="text-muted">{{ $u->HoTen }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Khung chat -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                @if ($activeUser)
                    @php
                        $activeAnh = $activeUser->AnhDaiDien ? asset('Content/Avatars/' . $activeUser->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                    @endphp
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center">
                        <img src="{{ $activeAnh }}" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                        <h5 class="fw-bold mb-0">{{ $activeUser->TaiKhoan }}</h5>
                    </div>
                    <div class="card-body" id="chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                        @foreach ($messages as $msg)
                            @if ($msg->NguoiGui == session('user')->MaKH)
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="bg-warning text-dark p-3 rounded-4 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 4px !important;">
                                        {{ $msg->NoiDung }}
                                        <div class="text-end mt-1"><small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($msg->NgayGui)->format('H:i d/m/Y') }}</small></div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-start mb-3">
                                    <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 75%; border-bottom-left-radius: 4px !important;">
                                        {{ $msg->NoiDung }}
                                        <div class="text-end mt-1"><small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($msg->NgayGui)->format('H:i d/m/Y') }}</small></div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer bg-white p-3 border-top">
                        <form id="chat-form" action="{{ route('tinnhan.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nguoiNhan" value="{{ $activeUser->MaKH }}">
                            <div class="input-group">
                                <input type="text" name="noiDung" class="form-control rounded-pill bg-light ps-4" placeholder="Nhập tin nhắn..." required id="msg-input">
                                <button type="submit" class="btn btn-warning rounded-pill ms-2 px-4 shadow-sm"><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                    <script>
                        // Scroll to bottom
                        var chatBox = document.getElementById("chat-box");
                        chatBox.scrollTop = chatBox.scrollHeight;

                        // AJAX form submit
                        $('#chat-form').on('submit', function(e) {
                            e.preventDefault();
                            var form = $(this);
                            var input = $('#msg-input');
                            if(input.val().trim() === '') return;
                            
                            $.ajax({
                                type: "POST",
                                url: form.attr('action'),
                                data: form.serialize(),
                                success: function(response) {
                                    if(response.success) {
                                        var newMsg = '<div class="d-flex justify-content-end mb-3"><div class="bg-warning text-dark p-3 rounded-4 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 4px !important;">' + input.val() + '</div></div>';
                                        $('#chat-box').append(newMsg);
                                        input.val('');
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    }
                                }
                            });
                        });
                    </script>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center text-muted" style="height: 500px;">
                        <div class="text-center">
                            <i class="fa-regular fa-comments fa-4x mb-3 opacity-50"></i>
                            <h5>Chọn một người để bắt đầu trò chuyện</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
if(!is_dir(__DIR__ . '/resources/views/tinnhan')) mkdir(__DIR__ . '/resources/views/tinnhan');
file_put_contents(__DIR__ . '/resources/views/tinnhan/index.blade.php', $tinNhanView);

// LoaiSanPham views
$loaiSpIndexView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0"><i class="fa fa-tags me-2 text-primary"></i>Danh Mục Sản Phẩm</h5>
            <a href="{{ route('loaisanpham.create') }}" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                <i class="fa fa-plus-circle me-2"></i>Thêm Danh Mục
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã loại</th>
                        <th>Tên loại</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dsLoai as $loai)
                        <tr>
                            <td class="fw-bold">#{{ $loai->MaLoai }}</td>
                            <td>{{ $loai->TenLoai }}</td>
                            <td class="text-center">
                                <a href="{{ route('loaisanpham.edit', ['id' => $loai->MaLoai]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Sửa</a>
                                <form action="{{ route('loaisanpham.delete', ['id' => $loai->MaLoai]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
EOD;
if(!is_dir(__DIR__ . '/resources/views/loaisanpham')) mkdir(__DIR__ . '/resources/views/loaisanpham');
file_put_contents(__DIR__ . '/resources/views/loaisanpham/index.blade.php', $loaiSpIndexView);

$loaiSpCreateView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-5">
        <h5 class="fw-bold mb-4 border-bottom pb-3">Thêm Danh Mục Mới</h5>
        <form action="{{ route('loaisanpham.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="TenLoai" class="form-control form-control-lg bg-light" required>
            </div>
            <button class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-sm mb-2">Thêm</button>
            <a href="{{ route('loaisanpham.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Quay lại</a>
        </form>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/loaisanpham/create.blade.php', $loaiSpCreateView);

$loaiSpEditView = <<<'EOD'
@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-5">
        <h5 class="fw-bold mb-4 border-bottom pb-3">Sửa Danh Mục #{{ $loai->MaLoai }}</h5>
        <form action="{{ route('loaisanpham.update', ['id' => $loai->MaLoai]) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="TenLoai" class="form-control form-control-lg bg-light" value="{{ $loai->TenLoai }}" required>
            </div>
            <button class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-sm mb-2">Lưu</button>
            <a href="{{ route('loaisanpham.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Quay lại</a>
        </form>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/loaisanpham/edit.blade.php', $loaiSpEditView);

echo "Phase 5 views updated!\n";
