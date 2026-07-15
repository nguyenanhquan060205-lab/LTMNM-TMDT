<?php

function fixBlade($file) {
    $content = file_get_contents($file);

    // Fix C# variables and logic block
    if (strpos($file, '_layout.blade.php') !== false) {
        $content = preg_replace('/@using.*?\r?\n/s', '', $content);
        $content = preg_replace('/@\{.*?var user = session\(\'user\'\).*?\}/s', 
            "@php\n" .
            "    \$user = session('user');\n" .
            "    \$anh = \$user && !empty(\$user->AnhDaiDien)\n" .
            "        ? asset('Content/Avatars/' . \$user->AnhDaiDien)\n" .
            "        : asset('Content/Avatars/default.jpg');\n" .
            "@endphp\n", $content);
    }

    // Fix @Url.Action("Action", "Controller") -> {{ route('controller.action') }}
    $content = preg_replace_callback('/@Url\.Action\("([^"]+)"\s*,\s*"([^"]+)"(?:,\s*new\s*\{[^}]+\})?\)/', function($matches) {
        $action = strtolower($matches[1]);
        $controller = strtolower($matches[2]);
        return "{{ route('{$controller}.{$action}') }}";
    }, $content);

    // Fix @Html.Action("CartIcon", "GioHang")
    $content = str_replace('@Html.Action("CartIcon", "GioHang")', '<div id="cart-icon-container">Cart: 0</div>', $content); // Placeholder

    // Fix $Title -> {{ $Title ?? '' }}
    $content = str_replace('$Title', '{{ $Title ?? \'\' }}', $content);

    // Fix @if
    $content = preg_replace('/@if\s*\(([^)]+)\)\s*\{/', '@if ($1)', $content);
    $content = preg_replace('/\}\s*else\s*\{/', '@else', $content);
    $content = preg_replace('/\}\s*$/m', '', $content); // this might be risky, better to manually replace in specific files.

    file_put_contents($file, $content);
}

// For home/index
function fixHomeIndex($file) {
    $content = file_get_contents($file);
    
    // Fix @foreach(var item in $items) -> @foreach($items as $item)
    $content = preg_replace('/@foreach\s*\(\s*var\s+([a-zA-Z0-9_]+)\s+in\s+([^)]+)\)\s*\{/', '@foreach($2 as $$1)', $content);
    
    $content = str_replace('@if (ViewBag.ThongBao != null)', '@if (session("ThongBao"))', $content);
    $content = str_replace('@ViewBag.ThongBao', '{{ session("ThongBao") }}', $content);
    $content = str_replace('item.', '$item->', $content);
    $content = str_replace('} <!-- end foreach -->', '@endforeach', $content); // Assuming we can guess ends
    
    // Convert all closing braces to @endif or @endforeach? Hard. Let's do it with a specific script.
    file_put_contents($file, $content);
}

// Just copy a clean version of home/index.blade.php and _layout.blade.php for now
$layout = <<<'EOD'
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
                    <div class="flex-shrink-0 text-white">
                        <i class="fa-solid fa-cart-shopping"></i> {{ session('CartCount', 0) }}
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
EOD;

file_put_contents(__DIR__ . '/resources/views/shared/_layout.blade.php', $layout);

$homeIndex = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="hero-section text-center position-relative mb-5" style="background: linear-gradient(135deg, #2d3436, #000000); color: white; padding: 100px 20px; overflow: hidden;">
    <!-- Nền Particles (trang trí) -->
    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-25" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="container position-relative" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 text-warning">TechSecond</h1>
        <p class="lead mb-4 fw-light text-light" style="max-width: 600px; margin: 0 auto;">Nền tảng thương mại điện tử C2C an toàn, kết nối người mua & người bán đồ công nghệ cũ nhanh chóng.</p>
        <a href="{{ route('sanpham.index') }}" class="btn btn-warning btn-lg px-5 py-3 shadow-sm" style="border-radius: 30px; font-weight: 600;">
            Khám phá ngay <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<div class="container my-5">
    @if(session('ThongBao'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('ThongBao') }}
        </div>
    @endif

    <!-- THỐNG KÊ -->
    <div class="row text-center mb-5 gx-4 gy-4">
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-users fs-1 text-primary mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $totalUsers ?? 0 }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Người dùng</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-box-open fs-1 text-success mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $totalProducts ?? 0 }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Sản phẩm</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-handshake fs-1 text-warning mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $totalOrders ?? 0 }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Giao dịch thành công</p>
            </div>
        </div>
    </div>

    <!-- SẢN PHẨM MỚI -->
    <h2 class="fw-bold mb-4 text-dark border-start border-5 border-warning ps-3">Sản Phẩm Mới Đăng</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
        @foreach($items as $item)
            @php
                $bia = $item->AnhBia ? asset('Content/Images/' . $item->AnhBia) : asset('Content/Images/noimage.jpg');
            @endphp
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <!-- Ảnh sản phẩm -->
                    <div class="position-relative overflow-hidden" style="border-radius: 10px 10px 0 0;">
                        <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                            <img src="{{ $bia }}" class="card-img-top object-fit-cover" alt="{{ $item->TenSP }}" style="height: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';">
                        </a>
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 px-3 py-2 rounded-pill shadow-sm">Mới</span>
                    </div>

                    <!-- Thông tin -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate fw-bold mb-2">
                            <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}" class="text-decoration-none text-dark">{{ $item->TenSP }}</a>
                        </h5>
                        <h4 class="text-danger fw-bold mb-3">{{ number_format($item->Gia, 0, ',', '.') }} đ</h4>
                        
                        <div class="mt-auto d-flex align-items-center justify-content-between text-muted small">
                            <span><i class="fa-solid fa-box me-1"></i>Còn: {{ $item->SoLuong }}</span>
                            <span><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($item->NgayDang)->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-3 pt-0">
                        <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}" class="btn btn-outline-dark w-100" style="border-radius: 8px;">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('sanpham.index') }}" class="btn btn-outline-warning btn-lg px-5 py-2 rounded-pill text-dark" style="font-weight: 500;">
            Xem tất cả sản phẩm <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>
@endsection
EOD;

file_put_contents(__DIR__ . '/resources/views/home/index.blade.php', $homeIndex);
echo "Fixed layout and home index\n";
