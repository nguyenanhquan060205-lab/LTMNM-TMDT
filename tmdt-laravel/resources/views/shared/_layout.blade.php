@php
    $user = Session::get('user');
    $anh = $user && (!empty($user->AnhDaiDien) && strtolower($user->AnhDaiDien) !== 'default.jpg')
        ? (str_starts_with($user->AnhDaiDien, 'http') ? $user->AnhDaiDien : asset('Content/Avatars/' . $user->AnhDaiDien))
        : url('Content/Avatars/Default.jpg');

    $cartCount = 0;
    if ($user) {
        $gio = \App\Models\GioHang::where('MaKH', $user->MaKH)->first();
        if ($gio) {
            $cartCount = \App\Models\CtGioHang::where('MaGH', $gio->MaGH)->sum('SoLuong');
        }
    }
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="google-site-verification" content="3frdGwnRsijNKrEHCc9ytTIxxiz6QchrFlFlkyyseDw" />
    <title>@yield('title', 'TechSecond')</title>
    <link rel="stylesheet" href="{{ url('Content/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ url('Content/Site.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ url('Scripts/bootstrap.bundle.min.js') }}"></script>
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
                        <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/sanpham') }}">Sản phẩm</a>
                    </li>
                    @if ($user && $user->VaiTro == "Admin")
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/index') }}">Quản trị</a>
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
                        <a href="{{ url('/giohang') }}" class="nav-icon-btn position-relative" style="margin-right: 15px;">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <!-- THÔNG BÁO -->
                    @if ($user)
                    <div class="dropdown flex-shrink-0" style="margin-right: 10px;">
                        <a href="#" class="nav-icon-btn position-relative dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="color: #4a5568;">
                            <i class="fa-solid fa-bell fs-5"></i>
                            <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" style="font-size: 0.65rem;">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 320px; max-height: 400px; overflow-y: auto; right: -20px;" id="notification-list">
                            <li><h6 class="dropdown-header">Thông báo của bạn</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><div class="text-center p-3 text-muted">Đang tải...</div></li>
                        </ul>
                    </div>
                    @endif

                    <!-- NGƯỜI DÙNG -->
                    @if ($user)
                        <div class="dropdown flex-shrink-0">
                            <a class="nav-link dropdown-toggle d-flex align-items-center"
                               href="#"
                               data-bs-toggle="dropdown"
                               style="padding: 0.3rem 0.8rem;">
                                <img src="{{ $anh }}"
                                     onerror="this.src='{{ url('Content/Avatars/Default.jpg') }}';"
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

    <!-- ===================== NOTIFICATION WIDGET ===================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(Session::get('user'))
            function fetchNotifications() {
                fetch('{{ route("thongbao.danhsach") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.error) return;
                    let badge = document.getElementById('notification-badge');
                    let list = document.getElementById('notification-list');
                    
                    if (data.chuadoc > 0) {
                        badge.innerText = data.chuadoc > 99 ? '99+' : data.chuadoc;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }

                    if (data.thongbaos.length === 0) {
                        list.innerHTML = '<li><h6 class="dropdown-header">Thông báo của bạn</h6></li><li><hr class="dropdown-divider"></li><li><div class="text-center p-3 text-muted">Chưa có thông báo nào</div></li>';
                    } else {
                        let html = '<li><h6 class="dropdown-header">Thông báo của bạn</h6></li><li><hr class="dropdown-divider"></li>';
                        data.thongbaos.forEach(tb => {
                            let isRead = tb.DaXem ? 'opacity-50' : 'fw-bold bg-light';
                            let time = new Date(tb.ThoiGian).toLocaleString('vi-VN');
                            let baseUrl = '{{ url("") }}';
                            // Clean up url logic if DuongDan already starts with /
                            let finalUrl = tb.DuongDan.startsWith('/') ? baseUrl + tb.DuongDan : baseUrl + '/' + tb.DuongDan;
                            
                            html += `<li>
                                <a class="dropdown-item ${isRead} text-wrap" href="#" onclick="readNotification(${tb.MaTB}, '${finalUrl}'); return false;" style="border-bottom: 1px solid #eee; padding: 10px;">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1" style="font-size: 0.9rem; color: #0d6efd;">${tb.TieuDe}</h6>
                                    </div>
                                    <p class="mb-1" style="font-size: 0.8rem; line-height: 1.4;">${tb.NoiDung}</p>
                                    <small class="text-muted" style="font-size: 0.7rem;">${time}</small>
                                </a>
                            </li>`;
                        });
                        list.innerHTML = html;
                    }
                });
            }

            window.readNotification = function(id, url) {
                fetch('{{ url("/thongbao/api/doc") }}/' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    window.location.href = url;
                });
            }

            fetchNotifications();
            setInterval(fetchNotifications, 15000); // Poll every 15s
            @endif
        });
    </script>
    <!-- =========================================================== -->

    <!-- ===================== AI CHAT WIDGET ===================== -->
    @include('components.ai_chat')
    <!-- =========================================================== -->

</body>
</html>
