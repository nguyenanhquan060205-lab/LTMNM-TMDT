@php
    $user = Session::get('user');
    $anh = $user && !empty($user->AnhDaiDien)
        ? url('Content/Avatars/' . $user->AnhDaiDien)
        : url('content/avatars/default.jpg');
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
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar siêu mượt */
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #1e1e2f 0%, #2a2a40 100%);
            color: #fff; 
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
        }
        .sidebar .nav-link { 
            color: rgba(255,255,255,.7); 
            padding: 12px 20px; 
            font-weight: 500; 
            transition: all 0.3s ease; 
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover { 
            color: #fff; 
            background: rgba(255,255,255,.1); 
            transform: translateX(5px);
        }
        .sidebar .nav-link.active { 
            color: #fff; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .sidebar .text-warning { color: #f6ad55 !important; }
        
        .content { width: 100%; }
        .navbar-admin { 
            background: rgba(255,255,255,0.9); 
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,.03); 
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar d-flex flex-column flex-shrink-0" style="width: 250px;">
            <a href="{{ url('/admin/index') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none p-3 border-bottom border-secondary">
                <i class="fa-solid fa-shield-halved fa-2x text-warning me-2"></i>
                <span class="fs-5 fw-bold">Admin Panel</span>
            </a>
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="{{ url('/admin/index') }}" class="nav-link {{ request()->is('Admin/Index') || request()->is('Admin') ? 'active' : '' }}">
                        <i class="fa fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/quanlysanpham') }}" class="nav-link {{ request()->is('admin/quanlysanpham*') || request()->is('Admin/DoiTrangThai*') || request()->is('Admin/QuanLySanPham*') ? 'active' : '' }}">
                        <i class="fa fa-boxes-stacked me-2"></i> Quản lý sản phẩm
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/quanlynguoidung') }}" class="nav-link {{ request()->is('Admin/QuanLyNguoiDung') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i> Quản lý người dùng
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/quanlydonhang') }}" class="nav-link {{ request()->is('Admin/QuanLyDonHang') ? 'active' : '' }}">
                        <i class="fa fa-shopping-cart me-2"></i> Đơn hàng hệ thống
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/quanlykhieunai') }}" class="nav-link {{ request()->is('Admin/QuanLyKhieuNai') ? 'active' : '' }}">
                        <i class="fa fa-exclamation-triangle me-2"></i> Xử lý khiếu nại
                    </a>
                </li>
                <li>
                    <a href="{{ url('/home') }}" class="nav-link text-info mt-4 border-top border-secondary pt-3">
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
                    <h4 class="mb-0 fw-bold text-dark">@yield('title', 'Quản Trị')</h4>
                    <div class="d-flex align-items-center ms-auto">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <img src="{{ $anh }}" width="32" height="32" class="rounded-circle me-2 border shadow-sm">
                                <strong>{{ $user->TaiKhoan ?? 'Admin' }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ url('/taikhoan/thongtinadmin') }}">Thông tin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ url('/taikhoan/dangxuat') }}">Đăng xuất</a></li>
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
