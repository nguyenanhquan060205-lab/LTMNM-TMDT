@php
    $user = Session::get('user');
    $anh = $user && !empty($user->AnhDaiDien)
        ? url('Content/Avatars/' . $user->AnhDaiDien)
        : url('content/avatars/default.jpg');

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
    <title>@yield('title', 'TechSecond')</title>
    <link rel="stylesheet" href="{{ url('content/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ url('content/site.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ url('scripts/bootstrap.bundle.min.js') }}"></script>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
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
                        <a href="{{ url('/giohang') }}" class="btn btn-warning btn-sm position-relative">
                            <i class="fa-solid fa-cart-shopping"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
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
                        <a class="btn btn-warning btn-sm px-3 flex-shrink-0"
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
    <footer>
        <div class="container py-5">
            <div class="text-center small">
                © 2025 <b class="text-warning">TechSecond</b> – All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')

<!-- ===================== AI CHAT WIDGET ===================== -->
<style>
    #ai-widget-btn {
        position: fixed;
        bottom: 28px;
        right: 28px;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(79,70,229,0.5);
        z-index: 9999;
        transition: transform .2s, box-shadow .2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #ai-widget-btn:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(79,70,229,0.7); }

    #ai-widget-box {
        position: fixed;
        bottom: 100px;
        right: 28px;
        width: 340px;
        height: 480px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        z-index: 9999;
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    #ai-widget-box.open { display: flex; animation: slideUp .25s ease; }
    @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

    .ai-header {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .ai-header .ai-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }
    .ai-header .ai-info { flex: 1; }
    .ai-header .ai-info b { display: block; font-size: 14px; }
    .ai-header .ai-info small { font-size: 11px; opacity: 0.8; }
    .ai-header .ai-close {
        background: none; border: none; color: white;
        font-size: 20px; cursor: pointer; opacity: 0.8; line-height: 1;
    }
    .ai-header .ai-close:hover { opacity: 1; }

    .ai-messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        background: #f9fafb;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .ai-msg { display: flex; gap: 8px; align-items: flex-end; }
    .ai-msg.user { flex-direction: row-reverse; }
    .ai-bubble {
        max-width: 78%;
        padding: 9px 13px;
        border-radius: 16px;
        font-size: 13px;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .ai-msg.bot .ai-bubble { background: #fff; border: 1px solid #e5e7eb; color: #1f2937; border-bottom-left-radius: 4px; }
    .ai-msg.user .ai-bubble { background: #4f46e5; color: white; border-bottom-right-radius: 4px; }
    .ai-bot-icon { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg,#4f46e5,#7c3aed); color:white; display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0; }

    .ai-typing { display: flex; gap: 4px; align-items: center; padding: 10px 14px; }
    .ai-typing span { width:7px;height:7px;border-radius:50%;background:#9ca3af;display:inline-block;animation:bounce 1.2s infinite; }
    .ai-typing span:nth-child(2){animation-delay:.2s}
    .ai-typing span:nth-child(3){animation-delay:.4s}
    @keyframes bounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-6px)}}

    .ai-input-area {
        padding: 12px;
        background: #fff;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    .ai-input-area input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 20px;
        padding: 8px 14px;
        font-size: 13px;
        outline: none;
    }
    .ai-input-area input:focus { border-color: #4f46e5; }
    .ai-send-btn {
        width: 36px; height: 36px; border-radius: 50%;
        background: #4f46e5; color: white; border: none;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        font-size: 15px; flex-shrink: 0;
        transition: background .2s;
    }
    .ai-send-btn:hover { background: #7c3aed; }
</style>

<button id="ai-widget-btn" title="Hỏi AI hỗ trợ">🤖</button>

<div id="ai-widget-box">
    <div class="ai-header">
        <div class="ai-avatar">🤖</div>
        <div class="ai-info">
            <b>TechBot AI</b>
            <small>Trợ lý thông minh TechSecond</small>
        </div>
        <button class="ai-close" onclick="toggleAiWidget()">×</button>
    </div>
    <div class="ai-messages" id="aiMessages">
        <div class="ai-msg bot">
            <div class="ai-bot-icon">🤖</div>
            @if(isset($user) && $user)
            <div class="ai-bubble">
                Xin chào <b>{{ $user->TaiKhoan }}</b>! Tôi là TechBot 👋<br>
                Tôi có thể giúp bạn về:<br>
                🛒 Mua bán sản phẩm<br>
                📦 Theo dõi đơn hàng<br>
                💬 Chính sách &amp; hỗ trợ<br><br>
                <i style="color:#6b7280;font-size:11px;">Gợi ý: "Làm sao để đăng bán hàng?", "Tôi muốn huỷ đơn hàng"</i>
            </div>
            @else
            <div class="ai-bubble">
                👋 Xin chào! Tôi là <b>TechBot AI</b> của TechSecond.<br><br>
                🔒 Vui lòng <b>đăng nhập</b> để trò chuyện với tôi nhé!<br><br>
                <div style="display:flex;gap:8px;margin-top:6px;flex-wrap:wrap;">
                    <a href="{{ url('/taikhoan/dangnhap') }}"
                       style="background:#4f46e5;color:white;padding:7px 16px;border-radius:20px;font-size:12px;text-decoration:none;font-weight:600;">
                        🔑 Đăng nhập
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="ai-input-area">
        <input type="text" id="aiInput"
               placeholder="{{ isset($user) && $user ? 'Nhập câu hỏi...' : 'Đăng nhập để sử dụng TechBot...' }}"
               autocomplete="off"
               {{ isset($user) && $user ? '' : 'disabled' }} />
        <button class="ai-send-btn" onclick="sendAiMessage()">➤</button>
    </div>
</div>

<script>
    function toggleAiWidget() {
        const box = document.getElementById('ai-widget-box');
        box.classList.toggle('open');
        if (box.classList.contains('open') && !document.getElementById('aiInput').disabled) {
            document.getElementById('aiInput').focus();
        }
    }

    document.getElementById('ai-widget-btn').addEventListener('click', toggleAiWidget);

    document.getElementById('aiInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') sendAiMessage();
    });

    function appendMsg(role, html) {
        const container = document.getElementById('aiMessages');
        const div = document.createElement('div');
        div.className = 'ai-msg ' + role;
        div.innerHTML = role === 'bot'
            ? `<div class="ai-bot-icon">🤖</div><div class="ai-bubble">${html}</div>`
            : `<div class="ai-bubble">${html}</div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function showTyping() {
        const container = document.getElementById('aiMessages');
        const div = document.createElement('div');
        div.className = 'ai-msg bot';
        div.id = 'aiTyping';
        div.innerHTML = `<div class="ai-bot-icon">🤖</div><div class="ai-bubble ai-typing"><span></span><span></span><span></span></div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function removeTyping() {
        const t = document.getElementById('aiTyping');
        if (t) t.remove();
    }

    async function sendAiMessage() {
        const input = document.getElementById('aiInput');
        const msg = input.value.trim();
        if (!msg || input.disabled) return;

        input.value = '';
        appendMsg('user', msg);
        showTyping();

        try {
            const res = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            removeTyping();

            if (data.requireLogin) {
                input.disabled = true;
                input.placeholder = 'Đăng nhập để sử dụng TechBot...';
                appendMsg('bot', `🔒 Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.<br><br>
                    <a href="{{ url('/taikhoan/dangnhap') }}" style="background:#4f46e5;color:white;padding:6px 14px;border-radius:20px;font-size:12px;text-decoration:none;font-weight:600;">🔑 Đăng nhập</a>`);
                return;
            }

            appendMsg('bot', (data.reply || 'Xin lỗi, có lỗi xảy ra.').replace(/\n/g, '<br>'));
        } catch {
            removeTyping();
            appendMsg('bot', '⚠️ Kết nối bị lỗi. Vui lòng thử lại!');
        }
    }
</script>
<!-- =========================================================== -->

</body>
</html>
