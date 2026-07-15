@php
    $user = session('user');
    $anh = $user && !empty($user->AnhDaiDien)
        ? asset('Content/Avatars/' . $user->AnhDaiDien)
        : asset('Content/Avatars/default.jpg');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $Title ?? 'TechSecond' }}</title>
    <link rel="stylesheet" href="{{ asset('Content/') }}/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('Content/') }}/Site.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('Scripts/') }}/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- 🧭 NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">
                <i class="fa-solid fa-cart-shopping me-2"></i>TechSecond
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <!-- Menu bên trái -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ (isset($Title) && $Title=='Trang chủ') ? 'active' : '' }}" href="{{ route('home.index') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sanpham.index') }}">Sản phẩm</a>
                    </li>
                    @if ($user && $user->VaiTro == "Admin")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.index') }}">Quản trị</a>
                        </li>
                    @endif
                </ul>

                <!-- Phần bên phải -->
                <div class="d-flex align-items-center gap-3">
                    <!-- TÌM KIẾM -->
                    <form class="d-flex align-items-center flex-shrink-0" method="get" action="{{ route('sanpham.index') }}">
                        <input class="form-control form-control-sm"
                               type="text"
                               name="q"
                               placeholder="Tìm kiếm sản phẩm..."
                               style="border-radius:20px; width: {{ ($user && $user->VaiTro == 'Admin') ? '320px' : '460px' }};" />
                        <button class="btn btn-warning btn-sm px-3 ms-2" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>

                    <!-- GIỎ HÀNG -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('giohang.index') }}" class="btn btn-warning btn-sm position-relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @if(session('CartCount', 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ session('CartCount') }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <!-- NGƯỜI DÙNG -->
                    @if ($user)
                        <div class="dropdown flex-shrink-0">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center"
                               href="#"
                               data-bs-toggle="dropdown"
                               style="padding: 0.3rem 0.8rem;">
                                <img src="{{ $anh }}"
                                     onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';"
                                     class="rounded-circle me-2 shadow-sm"
                                     width="30"
                                     height="30" />
                                <span style="white-space: nowrap;">{{ $user->TaiKhoan }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('taikhoan.thongtin') }}">
                                        <i class="fa fa-user me-2"></i> Thông tin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('sanpham.taomoi') }}">
                                        <i class="fa fa-plus-circle me-2"></i> Đăng bán
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('sanpham.cuatoi') }}">
                                        <i class="fa fa-box-open me-2"></i> Bài đăng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('taikhoan.lichsu') }}">
                                        <i class="fa fa-history me-2"></i> Lịch sử
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('sanpham.daban') }}">
                                        <i class="fa fa-bell me-2"></i> Thông báo
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('tinnhan.index') }}">
                                        <i class="fa-regular fa-comments me-2"></i> Tin nhắn
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-solid fa-flag me-2"></i> Khiếu nại
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('taikhoan.dangxuat') }}">
                                        <i class="fa fa-sign-out me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-warning btn-sm px-3 flex-shrink-0"
                           href="{{ route('taikhoan.dangnhap') }}"
                           style="white-space: nowrap;">
                            Đăng nhập
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <!-- 📦 BODY -->
    <main class="container-fluid p-0">
        @yield('content')
    </main>

    <!-- 🦶 FOOTER -->
    <footer>
        <div class="container py-5">
            <div class="text-center small">
                © 2025 <b class="text-warning">TechSecond</b> – All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>