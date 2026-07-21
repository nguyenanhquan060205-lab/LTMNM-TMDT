@php
    // 1. Lấy thông tin người dùng
    $user = Session::get('user');

    // 2. Xác định Controller và Action hiện tại qua Route name
    $routeName = request()->route()->getName();
    
    // Phân tích route name (ví dụ: 'admin.index', 'tinnhan.chat')
    $routeParts = explode('.', $routeName);
    $currentController = $routeParts[0] ?? '';
    $currentAction = $routeParts[1] ?? '';

    // 3. Logic kiểm tra xem có phải đang ở bối cảnh Admin không
    $isAdminContext = false;

    if ($currentController == "admin") {
        $isAdminContext = true;
    } else if (($currentController == "tinnhan" || $currentController == "taikhoan") && $user && $user->VaiTro == "Admin") {
        $isAdminContext = true;
    }

    // 4. TỰ ĐỘNG ĐẶT TIÊU ĐỀ THEO MENU
    $pageTitle = "Dashboard quản trị"; // Mặc định

    if ($currentController == "admin") {
        switch ($currentAction) {
            case "index": $pageTitle = "Dashboard"; break;
            case "quanlysanpham": $pageTitle = "Quản lý sản phẩm"; break;
            case "quanlydonhang": $pageTitle = "Quản lý đơn hàng"; break;
            case "quanlynguoidung": $pageTitle = "Quản lý người dùng"; break;
            case "quanlyloaisp": $pageTitle = "Loại sản phẩm"; break;
            case "quanlykhieunai": $pageTitle = "Quản lý khiếu nại"; break;
        }
    } else if ($currentController == "tinnhan") {
        $pageTitle = "Tin nhắn / Hỗ trợ";
    } else if ($currentController == "taikhoan") {
        $pageTitle = "Thông tin quản trị viên";
    }

    // Hàm Active Menu
    $getActive = function($ctrl, $act) use ($currentController, $currentAction) {
        return ($currentController == $ctrl && $currentAction == $act) ? "active" : "";
    };
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle }} - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f6f8fb;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ==== SIDEBAR ==== */
        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            z-index: 1000;
            background: linear-gradient(180deg, #1e1e3b, #282852);
            color: #fff;
            padding: 15px 10px;
            top: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

            /* ADMIN TITLE */
            .sidebar .panel-title {
                text-decoration: none !important;
                color: #00d9ff !important;
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 18px;
                margin: 20px 0 0;
                border-left: 3px solid transparent;
                transition: all 0.25s ease;
                border-radius: 8px; /* Đã bo cong */
            }

                .sidebar .panel-title h2 {
                    margin: 0 !important;
                    font-size: 20px;
                    color: #00d9ff;
                    font-weight: 600;
                }

                .sidebar .panel-title:hover {
                    background: rgba(0, 217, 255, 0.15);
                    border-left: 3px solid #00d9ff;
                    padding-left: 28px;
                    cursor: pointer;
                }

            /* USER PANEL LINK */
            .sidebar .user-panel-link {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 20px;
                font-weight: 600;
                color: #ff9800 !important;
                background: transparent;
                border-left: 3px solid transparent;
                padding: 10px 18px;
                margin: 10px 0 35px;
                border-radius: 8px;
                text-decoration: none !important;
                transition: all 0.25s;
            }

                .sidebar .user-panel-link i {
                    width: 22px;
                    text-align: center;
                    color: #ff9800 !important;
                }

                .sidebar .user-panel-link:hover {
                    color: #ff9800 !important;
                    background: rgba(255, 152, 0, 0.2);
                    border-left: 3px solid #ff9800;
                    padding-left: 25px;
                }

            /* MENU LINKS */
            .sidebar a:not(.panel-title):not(.user-panel-link):not(.logout) {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #bbb;
                padding: 10px 18px;
                margin: 5px 0;
                text-decoration: none;
                font-size: 15px;
                border-radius: 8px;
                transition: all 0.25s;
                border-left: 3px solid transparent;
            }

            .sidebar a i {
                width: 22px;
                text-align: center;
            }

            .sidebar a:not(.panel-title):not(.user-panel-link):not(.logout):hover,
            .sidebar a:not(.panel-title):not(.user-panel-link):not(.logout).active {
                color: #fff;
                background: rgba(0, 217, 255, 0.2);
                border-left: 3px solid #00d9ff;
                padding-left: 25px;
            }

        /* LOGOUT */
        .logout {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ff5555 !important;
            font-weight: 500;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            transition: all 0.25s;
            border-left: 3px solid transparent;
        }

            .logout:hover {
                color: #fff !important;
                background: rgba(255, 85, 85, 0.2);
                border-left: 3px solid #ff5555;
                padding-left: 25px;
            }

        /* MAIN AREA */
        .main {
            margin-left: 250px;
            padding: 25px;
        }

        .header {
            background: #fff;
            border-radius: 12px;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

            .header h3 {
                font-weight: 600;
                color: #1a237e;
                margin: 0;
            }

            .header .user {
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 500;
                color: #333;
            }

                .header .user img {
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    border: 2px solid #00d9ff;
                }

        /* CONTENT */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: 0.3s ease;
        }

            .card:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }

        @media (max-width: 991px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }

            .logout {
                position: relative;
                bottom: auto;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="{{ route('admin.index') }}" class="panel-title">
            <h2 class="m-0"><i class="fa-solid fa-gear me-2"></i>Admin Panel</h2>
        </a>

        @if ($user && $user->VaiTro == "Admin" && $isAdminContext)
            <a href="{{ route('home.index') }}" class="user-panel-link">
                <i class="fa-solid fa-user-circle"></i>User Panel
            </a>
        @endif

        @if ($isAdminContext)
            <a href="{{ route('admin.index') }}" class="{{ $getActive('admin', 'index') }}"><i class="fa-solid fa-chart-column"></i>Dashboard</a>
            <a href="{{ route('admin.quanlysanpham') }}" class="{{ $getActive('admin', 'quanlysanpham') }}"><i class="fa-solid fa-box"></i>Quản lý sản phẩm</a>
            <a href="{{ route('admin.quanlydonhang') }}" class="{{ $getActive('admin', 'quanlydonhang') }}"><i class="fa-solid fa-file-invoice"></i>Quản lý đơn hàng</a>
            <a href="{{ route('admin.quanlynguoidung') }}" class="{{ $getActive('admin', 'quanlynguoidung') }}"><i class="fa-solid fa-users"></i>Quản lý người dùng</a>
            <a href="{{ route('admin.quanlykhieunai') }}" class="{{ $getActive('admin', 'quanlykhieunai') }}"><i class="fa-solid fa-triangle-exclamation"></i>Quản lý khiếu nại</a>
            <a href="{{ route('tinnhan.chat') }}" class="{{ $getActive('tinnhan', 'chat') }}"><i class="fa-solid fa-comments"></i>Tin nhắn / Hỗ trợ</a>
        @endif

        <a href="{{ route('taikhoan.dangxuat') }}" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
        </a>
    </div>

    <div class="main">
        <div class="header">
            <h3>{{ $pageTitle }}</h3>

            <div class="user">
                @php
                    $avatar = empty($user->AnhDaiDien) ? "Default.jpg" : $user->AnhDaiDien;
                @endphp

                @if ($user)
                    <a href="{{ route('taikhoan.thongtin') }}"
                       class="d-flex align-items-center gap-2 text-decoration-none text-dark"
                       title="Xem thông tin cá nhân">

                        <img src="{{ asset('Content/Avatars/' . $avatar) }}"
                             style="width:40px;height:40px;border-radius:50%;object-fit:cover; border: 2px solid #00d9ff;" />

                        <span class="fw-bold">{{ $user->HoTen }}</span>
                    </a>
                @endif

                @if ($user && $user->VaiTro == "Admin" && !$isAdminContext)
                    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-info ms-3">Admin Panel</a>
                @endif
            </div>
        </div>

        @yield('content')

        <footer>© 2025 - TechSecond Admin Dashboard</footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('scripts')
</body>
</html>

