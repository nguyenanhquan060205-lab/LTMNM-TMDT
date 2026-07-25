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
