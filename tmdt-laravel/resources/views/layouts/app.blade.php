@php
    $user = Session::get('user');
    $anh = $user && !empty($user->AnhDaiDien)
        ? str_starts_with($user->AnhDaiDien, 'http') ? $user->AnhDaiDien : asset('Content/Avatars/' . $user->AnhDaiDien)
        : asset('Content/Avatars/Default.jpg');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - TechSecond</title>
    <link rel="stylesheet" href="{{ asset('Content/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('Content/Site.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('Scripts/bootstrap.bundle.min.js') }}"></script>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa-solid fa-cart-shopping me-2"></i>TechSecond
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <!-- Menu bên trái -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') || request()->is('Home*') ? 'active' : '' }}" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('sanpham*') ? 'active' : '' }}" href="{{ url('/sanpham') }}">Sản phẩm</a>
                    </li>
                    @if ($user && $user->VaiTro == "Admin")
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ url('/admin/index') }}">Quản trị</a>
                        </li>
                    @endif
                </ul>

                <!-- Phần bên phải -->
                <div class="d-flex align-items-center gap-3">
                    <!-- TÌM KIẾM -->
                    <form class="d-flex align-items-center flex-shrink-0" method="get" action="{{ url('/sanpham') }}">
                        <div class="position-relative">
                            <input class="form-control form-control-sm border-0 bg-light"
                                   type="text"
                                   name="q"
                                   placeholder="Tìm kiếm sản phẩm..."
                                   style="border-radius:20px; padding: 0.4rem 1rem 0.4rem 2.5rem; width: {{ ($user && $user->VaiTro == 'Admin') ? '320px' : '460px' }}; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);" />
                            <i class="fa fa-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                        </div>
                    </form>

                    <!-- GIỎ HÀNG -->
                    <div class="flex-shrink-0">
                        @php $cartCount = Session::get('CartCount', 0); @endphp
                        <a href="{{ url('/giohang') }}" class="nav-icon-btn position-relative">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <!-- NGƯỜI DÙNG -->
                    @if ($user)
                        <div class="dropdown flex-shrink-0">
                            <a class="nav-link dropdown-toggle d-flex align-items-center"
                               href="#"
                               data-bs-toggle="dropdown"
                               style="padding: 0.3rem 0.8rem;">
                                <img src="{{ $anh }}"
                                     onerror="this.src='{{ url('content/avatars/default.jpg') }}';"
                                     class="rounded-circle me-2 shadow-sm"
                                     width="30"
                                     height="30" />
                                <span style="white-space: nowrap;">{{ $user->TaiKhoan }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/taikhoan/thongtinkhachhang') }}">
                                        <i class="fa fa-user me-2"></i> Thông tin
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/sanpham/taomoi') }}">
                                        <i class="fa fa-plus-circle me-2"></i> Đăng bán
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/sanpham/cuatoi') }}">
                                        <i class="fa fa-box-open me-2"></i> Bài đăng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/sanpham/sanphamdaban') }}">
                                        <i class="fa fa-clipboard-list me-2"></i> Đơn hàng bán
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/taikhoan/lichsu') }}">
                                        <i class="fa fa-history me-2"></i> Lịch sử
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/tinnhan/chat') }}">
                                        <i class="fa-regular fa-comments me-2"></i> Tin nhắn
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('/taikhoan/khieunai') }}">
                                        <i class="fa-solid fa-flag me-2"></i> Khiếu nại
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ url('/taikhoan/dangxuat') }}">
                                        <i class="fa fa-sign-out me-2"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="btn btn-nav-login btn-sm px-4 rounded-pill fw-bold"
                           href="{{ url('/taikhoan/dangnhap') }}"
                           style="white-space: nowrap;">
                            Đăng nhập
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- BODY -->
    <main class="container-fluid p-0">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-top: 1px solid rgba(0,0,0,0.05); color: #4a5568;">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="fw-bold mb-0" style="color: #667eea;">
                        <i class="fa-solid fa-cart-shopping me-2"></i>TechSecond
                    </h5>
                    <small class="text-muted mt-2 d-block">Trao đổi đồ công nghệ dễ dàng & an toàn.</small>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <a href="#" class="text-decoration-none text-muted me-3 hover-primary"><i class="fa-brands fa-facebook fs-5"></i></a>
                    <a href="#" class="text-decoration-none text-muted me-3 hover-primary"><i class="fa-brands fa-tiktok fs-5"></i></a>
                    <a href="#" class="text-decoration-none text-muted hover-primary"><i class="fa-brands fa-youtube fs-5"></i></a>
                </div>
                <div class="col-md-4 text-center text-md-end text-muted small">
                    © 2026 <strong>TechSecond</strong>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <style>
        .hover-primary:hover { color: #0d6efd !important; transform: scale(1.1); transition: all 0.2s; }
        .btn-nav-login { color: #2d3748; background: transparent; border: 2px solid #e2e8f0; transition: all 0.3s; }
        .btn-nav-login:hover { background: #0d6efd; color: white; border-color: #0d6efd; }
    </style>

    @yield('scripts')

    <!-- ===================== AI CHAT WIDGET ===================== -->
    @include('components.ai_chat')
    <!-- =========================================================== -->
</body>
</html>
