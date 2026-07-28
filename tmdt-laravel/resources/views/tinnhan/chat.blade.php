@php
    $currentUser = Session::get('user');
    $viewMode = request()->query('mode');
    $isAdminAccount = ($currentUser && $currentUser->VaiTro == 'Admin');
    $showAdminLayout = $isAdminAccount && ($viewMode != 'user');
    $layout = $showAdminLayout ? 'shared._layoutadmin' : 'shared._layout';
    $title = $showAdminLayout ? 'Dashboard quản trị' : 'Tin nhắn';
@endphp

@extends($layout)
@section('title', $title)

@section('content')
<input type="hidden" id="maSP" value="{{ $MaSP ?? '' }}" />
<input type="hidden" id="anhSP" value="{{ $AnhSP ?? '' }}" />
<input type="hidden" id="tenSP" value="{{ $TenSP ?? '' }}" />

<input type="hidden" id="RequestVerificationToken" value="{{ csrf_token() }}" />

<style>
    .chat-container {
        display: flex;
        width: 85%;
        margin: 40px auto;
        height: 72vh;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
        overflow: visible;
        position: relative;
    }

        .chat-container.admin-view {
            width: 100% !important;
            margin: 0 !important;
            height: 80vh !important;
            box-shadow: none !important;
            border: 1px solid #e0e0e0;
        }

    /* USER LIST */
    .user-list {
        width: 280px;
        background: #1d2033;
        color: #fff;
        border-right: 1px solid #2c2e43;
        overflow-y: auto;
    }

    .user-item {
        padding: 15px;
        border-bottom: 1px solid #2c2e43;
        cursor: pointer;
        transition: .2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

        .user-item:hover {
            background: #313553;
        }

        .user-item.active {
            background: #3d4163;
            border-left: 4px solid #0078ff;
        }

    /* CHAT BOX */
    .chat-box {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-header {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        font-weight: bold;
        color: #333;
    }

    .admin-view .chat-header {
        background-color: #f8f9fa;
    }

    .chat-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        overflow-x: visible;
        background: #f1f3f7;
        display: flex;
        flex-direction: column;
    }

    .msg {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-end;
        position: relative;
    }

        .msg img.avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

    .bubble {
        padding: 10px 14px;
        max-width: 65%;         
        width: fit-content;      
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,.1);
        text-align: left;
    }

    .msg.me {
        justify-content: flex-end;
        text-align: right;
    }

        .msg.me .bubble {
            background: #0078ff;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

    .msg.them {
        justify-content: flex-start;
    }

        .msg.them .bubble {
            border-bottom-left-radius: 4px;
        }

    .bubble-time {
        font-size: 10px;
        opacity: 0.7;
        margin-top: 4px;
        text-align: right;
    }

    /* INPUT */
    .chat-input {
        padding: 15px;
        background: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        gap: 10px;
    }

        .chat-input input {
            flex: 1;
            padding: 10px 15px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

    .msg-text {
        margin-bottom: 6px;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .chat-image {
        max-width: 220px;
        border-radius: 10px;
        display: block;
        margin-bottom: 6px;
    }

    .chat-text {
        white-space: pre-wrap;
        word-break: break-word;
        line-height: 1.4;
        text-align: left !important;
    }

    .chat-product-card {
        display: flex;
        gap: 12px;
        padding: 12px;
        background: #fff;
        color: #333 !important;
        align-items: center;
    }

    .chat-product-card img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .product-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
    }

    .product-name {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 4px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-link {
        font-size: 12px;
        color: #0078ff;
        text-decoration: none;
        font-weight: 500;
    }

    .product-link:hover {
        text-decoration: underline;
    }

    .bubble.has-product {
        padding: 0 !important;
        overflow: hidden;
        width: 280px;
        max-width: 100%;
    }

    .bubble.has-product .chat-product-card {
        margin: 0;
        border-radius: 0;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        width: 100%;
    }

    .bubble.has-product .chat-text {
        padding: 12px 14px 4px 14px;
        text-align: left;
    }

    .bubble.has-product .bubble-time,
    .bubble.has-product .receipt-label {
        padding: 0 14px 10px 14px;
    }

    .msg-options {
        position: relative;
        display: inline-block;
        margin-right: 5px;
    }

    .btn-options {
        background: transparent;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: #666;
    }

    .options-menu {
        position: absolute;
        top: 20px;
        left: 0;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        z-index: 500;
        min-width: 100px;
    }

        .options-menu button {
            width: 100%;
            border: none;
            background: transparent;
            padding: 5px 10px;
            text-align: left;
            cursor: pointer;
        }

            .options-menu button:hover {
                background: #f5f5f5;
            }
    .receipt-label {
        font-size: 10px;
        font-style: italic;
        margin-top: 2px;
        display: block;
        text-align: right;
    }
    .receipt-label.sent {
        color: rgba(255,255,255,0.65);
    }
    .receipt-label.seen {
        color: #90caf9;
        font-weight: 500;
    }
    /* Cho bubble bên mình (màu xanh) */
    .msg.me .receipt-label.sent { color: rgba(255,255,255,0.65); }
    .msg.me .receipt-label.seen { color: #cce5ff; }

    /* Admin ghim đầu danh sách */
    .admin-item {
        border-top: 2px solid #f0b429 !important;
        background: linear-gradient(90deg, #1d2033, #2a2730) !important;
    }
    .admin-item:hover {
        background: linear-gradient(90deg, #2a2d3d, #352e40) !important;
    }
</style>

@if (!$showAdminLayout)
    <div style="width:85%; margin:0 auto;">
        <h2 class="fw-bold text-primary mb-3 mt-4">
            <i class="bi bi-receipt-cutoff"></i> Tin Nhắn
        </h2>
    </div>
@endif

<input type="hidden" id="NguoiGuiID" value="{{ $NguoiGuiID ?? '' }}" />
<input type="hidden" id="NguoiNhanID" value="{{ $NguoiNhanID ?? '0' }}" />

<div class="chat-container {{ $showAdminLayout ? 'admin-view' : '' }}">

    <!-- ==================== USER LIST + SEARCH ======================= -->
    <div class="user-list">

        <div style="padding:15px; border-bottom:1px solid #3d4163;">
            <h5 class="m-0 text-white mb-2">Danh sách</h5>

            <!-- 🔎 TÌM KIẾM USER -->
            <input type="text" id="searchUser" placeholder="Tìm người..."
                   style="width:100%; padding:8px 12px; border-radius:6px;
                          border:none; outline:none; background:#2a2d44;
                          color:white; font-size:14px;">
        </div>

        @php
            $userChuaDoc = $UserChuaDoc ?? [];
            $nguoiNhanID = $NguoiNhanID ?? 0;
            $mode = request()->query('mode');
        @endphp

        @if(isset($dsNguoiDung) && is_iterable($dsNguoiDung))
            @foreach ($dsNguoiDung as $u)
                @php
                    $chuaDoc = in_array($u->MaKH, $userChuaDoc);
                @endphp

                <div class="user-item {{ $u->MaKH == $nguoiNhanID ? 'active' : '' }} {{ $u->VaiTro == 'Admin' ? 'admin-item' : '' }}"
                     data-unread="{{ $chuaDoc ? 1 : 0 }}"
                     onclick="location.href='{{ url('/tinnhan/chat') }}?idNguoiNhan={{ $u->MaKH }}&mode={{ $mode }}'">

                    <div style="position:relative; flex-shrink:0;">
                        <img src="{{ Str::startsWith($u->AnhDaiDien, 'http') ? $u->AnhDaiDien : url('Content/Avatars/' . (empty($u->AnhDaiDien) ? 'Default.jpg' : $u->AnhDaiDien)) }}"
                             style="width:36px;height:36px;border-radius:50%;object-fit:cover;" />
                        @if($u->VaiTro == 'Admin')
                            <span style="position:absolute;bottom:-2px;right:-4px;font-size:12px;" title="Chăm sóc khách hàng">⭐</span>
                        @endif
                    </div>

                    <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1;">
                        <div style="display:flex; align-items:center; gap:4px;">
                            <b>{{ $u->HoTen }}</b>
                            @if($u->VaiTro == 'Admin')
                                <span style="font-size:10px; background:#f0b429; color:#000; border-radius:3px; padding:0 4px; font-weight:700;">CSKH</span>
                            @endif
                            @if ($chuaDoc)
                                <span class="ms-1 text-warning">●</span>
                            @endif
                        </div>
                        @if($u->VaiTro == 'Admin')
                            <small style="color:#8bc4ff; font-size:10px;">📌 Hỗ trợ khách hàng</small>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- ======================= CHAT BOX ======================= -->
    <div class="chat-box">
        <div class="chat-header d-flex justify-content-between align-items-center">
            <div>
                @if (isset($NguoiNhanID) && $NguoiNhanID != 0)
                    <i class="fa-solid fa-circle-user me-2 text-primary"></i> {{ $NguoiNhanTen ?? '' }}
                @else
                    <span>Chưa chọn người để trò chuyện</span>
                @endif
            </div>

            <div style="font-size:14px;">
                <input type="checkbox" id="chkUnread" />
                <label for="chkUnread">Chỉ tin chưa xem</label>
            </div>
        </div>

        <div id="messages" class="chat-body">
            @if (!isset($NguoiNhanID) || $NguoiNhanID == 0)
                <div class="text-center text-muted mt-5">
                    <i class="fa-regular fa-comments fa-3x mb-3"></i><br />
                    Vui lòng chọn một người dùng bên trái để bắt đầu
                </div>
            @endif
        </div>

        @if (isset($NguoiNhanID) && $NguoiNhanID != 0)
            <div id="imagePreview"
                 style="display:none; padding:10px 15px; border-top:1px solid #ddd; background:#fff;">
                <div style="position:relative; display:inline-block;">
                    <img id="previewImg"
                         style="max-width:120px; border-radius:10px; border:1px solid #ccc;" />
                    <button id="removeImage"
                            type="button"
                            style="position:absolute; top:-8px; right:-8px;
                       border:none; background:#ff4d4f; color:white;
                       border-radius:50%; width:22px; height:22px;
                       cursor:pointer;">
                        ×
                    </button>
                </div>
            </div>

            <div class="chat-input">
                <label for="imgUpload" class="btn btn-outline-secondary mb-0">
                    <i class="fa-regular fa-image"></i>
                </label>

                <input id="imgUpload" type="file" accept="image/*" hidden>

                <input id="txtMsg" type="text" placeholder="Nhập tin nhắn...">

                <button id="btnSend" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Image Zoom Modal -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="zoomedImage" src="" class="img-fluid rounded shadow" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    function openImageModal(src) {
        document.getElementById('zoomedImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
        myModal.show();
    }
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

<script>
    // ======================= TÌM KIẾM USER REALTIME =======================
    $("#searchUser").on("keyup", function () {
        let keyword = $(this).val().toLowerCase();

        $(".user-item").each(function () {
            let name = $(this).text().trim().toLowerCase();

            $(this).toggle(name.includes(keyword));
        });
    });

    // ======================= LOAD TIN NHẮN =======================
    let autoScroll = true;

    function loadTinNhan() {
        const idGui = $('#NguoiGuiID').val();
        const idNhan = $('#NguoiNhanID').val();
        if (idNhan == 0) return;

        let token = $('#RequestVerificationToken').val();

        $.getJSON('/tinnhan/loadtinnhan/' + idGui + '/' + idNhan, function (data) {

            let html = "";
            let lastMyIndex = -1;

            // Lấy avatar người nhận (để hiển thị khi tin đã được xem)
            const idNhan = $('#NguoiNhanID').val();

            for (let i = data.length - 1; i >= 0; i--) {
                if (data[i].NguoiGui == idGui) {
                    lastMyIndex = i;
                    break;
                }
            }

            $.each(data, function (i, m) {
                let isMe = m.NguoiGui == idGui;
                let av = m.AvatarGui ? `${m.AvatarGui.startsWith('http') ? m.AvatarGui : '/Content/Avatars/' + m.AvatarGui}` : '/Content/Avatars/Default.jpg';

                // --- Receipt (chỉ hiện ở tin cuối cùng của mình) ---
                let receiptHtml = '';
                if (isMe) {
                    if (m.DaDoc) {
                        receiptHtml = `<span class="receipt-label seen">Đã xem</span>`;
                    } else {
                        receiptHtml = `<span class="receipt-label sent">Đã nhận</span>`;
                    }
                }

                let messageHtml = "";
                if (m.MaSP) {
                    messageHtml += `
                        <div class="chat-product-card">
                            <img src="${m.AnhSP.startsWith('http') ? m.AnhSP : '/Content/Images/' + m.AnhSP}">
                            <div class="product-info">
                                <div class="product-name">${m.TenSP}</div>
                                <a href="/sanpham/chitiet/${m.MaSP}" class="product-link">Xem sản phẩm</a>
                            </div>
                        </div>
                    `;
                }

                if (m.NoiDung) {
                    messageHtml += `<div class="chat-text">${m.NoiDung}</div>`;
                }

                html += `
                <div class="msg ${isMe ? 'me' : 'them'}" data-dadoc="${m.DaDoc}">
                    ${!isMe ? `<img class="avatar" src="${av}">` : ""}

                    ${isMe ? `
                    <div class="msg-options">
                        <button class="btn-options">&#x2026;</button>
                        <div class="options-menu" style="display:none;">
                            <button type="button" class="text-danger" onclick="xoaTinNhan(${m.MaTN})">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                    ` : ``}

                    <div class="bubble ${m.MaSP ? 'has-product' : ''}">
                        ${m.Anh
                        ? `<img src="${m.Anh.startsWith('http') ? m.Anh : '/Content/chat_images/' + m.Anh}"
                                   class="chat-image" style="cursor: pointer;" onclick="openImageModal(this.src)">`
                        : ""
                        }

                        ${messageHtml}

                        <div class="bubble-time">${m.Gio}</div>
                        ${receiptHtml}
                    </div>
                    ${isMe ? `<img class="avatar" src="${av}">` : ""}
                </div>`;
            });

            $("#messages").html(html);

            if (autoScroll) {
                let box = $("#messages")[0];
                box.scrollTop = box.scrollHeight;
            }
        });
    }

    $("#messages").on("scroll", function () {
        const el = $(this)[0];
        autoScroll = !(el.scrollTop + el.clientHeight < el.scrollHeight - 50);
    });

    $("#btnSend").click(function () {
        const idGui = $('#NguoiGuiID').val();
        const idNhan = $('#NguoiNhanID').val();
        const msg = $("#txtMsg").val().trim();
        const maSP = $("#maSP").val();
        const file = $("#imgUpload")[0] ? $("#imgUpload")[0].files[0] : null;
        let token = $('#RequestVerificationToken').val();

        if (!msg && !file && !maSP) return;

        let formData = new FormData();
        formData.append("_token", token);
        formData.append("nguoiGui", idGui);
        formData.append("nguoiNhan", idNhan);
        formData.append("noiDung", msg);
        if (maSP) formData.append("maSP", maSP);
        if (file) formData.append("anh", file);

        $.ajax({
            url: "/tinnhan/guitinnhan",
            type: "POST",
            data: formData,
            processData: false, // ⛔ bắt buộc
            contentType: false, // ⛔ bắt buộc
            success: function () {
                $("#txtMsg").val('');
                $("#imgUpload").val('');
                $("#maSP").val('');
                $("#anhSP").val('');
                $("#tenSP").val('');
                $("#imagePreview").hide();
                autoScroll = true;
                loadTinNhan();
            }
        });
    });

    $('#txtMsg').keypress(function (e) {
        if (e.which === 13) { $("#btnSend").click(); return false; }
    });

    loadTinNhan();

    // ======================= PUSHER REAL-TIME =======================
    // Khởi tạo Pusher
    var pusher = new Pusher('ef7c3d0d3073e7a57338', {
      cluster: 'ap1'
    });

    // Lắng nghe kênh của User hiện tại (ID của tài khoản đang đăng nhập)
    var currentUserId = $('#NguoiGuiID').val();
    var channel = pusher.subscribe('chat.' + currentUserId);

    // Khi có tin nhắn mới gửi đến
    channel.bind('App\\Events\\TinNhanMoi', function(data) {
        let m = data.message;
        if (m.NguoiGui == $('#NguoiNhanID').val()) {
            autoScroll = true;
            loadTinNhan();
            sendDanhDauDaDoc(); // Tự động đánh dấu đã xem khi đang mở khung chat
        } else {
            let userItem = $('.user-item').filter(function() {
                return $(this).attr('onclick') && $(this).attr('onclick').includes('idNguoiNhan=' + m.NguoiGui);
            });
            if (userItem.length > 0) {
                userItem.attr('data-unread', 1);
                if (userItem.find('.text-warning').length === 0) {
                    userItem.find('b').parent().append('<span class="ms-1 text-warning">●</span>');
                }
            } else {
                location.reload(); 
            }
        }
    });

    // Khi người kia đã đọc tin nhắn của mình
    channel.bind('App\\Events\\TinNhanDaDoc', function(data) {
        if (data.message.NguoiDoc == $('#NguoiNhanID').val()) {
            loadTinNhan(); // Reload lại để cập nhật chữ "Đã xem"
        }
    });
    // ================================================================
    function sendDanhDauDaDoc() {
        const idGui = $('#NguoiGuiID').val();
        const idNhan = $('#NguoiNhanID').val();
        if (idNhan == 0) return;
        let token = $('#RequestVerificationToken').val();

        $.post('/tinnhan/danhdaudadoc/' + idGui + '/' + idNhan, {
            _token: token
        }).done(function () {
            // ...
        });
    }
    
    sendDanhDauDaDoc();

    function xoaTinNhan(idTin) {
        if (!confirm('Bạn có chắc muốn xóa tin nhắn này?')) return;
        let token = $('#RequestVerificationToken').val();
        $.ajax({
            url: '/tinnhan/xoatinnhan/' + idTin,
            type: 'POST',
            data: { _token: token },
            success: function() { loadTinNhan(); },
            error: function(xhr) {
                alert('Không thể xóa tin nhắn: ' + (xhr.responseJSON?.error || ''));
            }
        });
    }

    // ======================= PREVIEW ẢNH =======================
    $("#imgUpload").on("change", function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            $("#previewImg").attr("src", e.target.result);
            $("#imagePreview").show();
        };
        reader.readAsDataURL(file);
    });

    // BỎ ẢNH ĐÃ CHỌN
    $("#removeImage").click(function () {
        $("#imgUpload").val("");
        $("#imagePreview").hide();
    });

    // ======================= PASTE IMAGE (CTRL + V) =======================
    $("#txtMsg").on("paste", function (e) {
        const clipboard = e.originalEvent.clipboardData;
        if (!clipboard) return;

        for (let item of clipboard.items) {
            if (item.type.indexOf("image") !== -1) {
                const blob = item.getAsFile();

                // tạo file giả giống upload
                const file = new File([blob], "paste-image.png", { type: blob.type });

                // gán vào input file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById("imgUpload").files = dataTransfer.files;

                // preview ảnh
                const reader = new FileReader();
                reader.onload = function (e) {
                    $("#previewImg").attr("src", e.target.result);
                    $("#imagePreview").show();
                };
                reader.readAsDataURL(file);

                e.preventDefault(); // ⛔ không paste text
                break;
            }
        }
    });

    $(document).ready(function () {
        const anhSP = $("#anhSP").val();
        const maSP = $("#maSP").val();
        const tenSP = $("#tenSP").val();

        if (maSP) {
            $("#txtMsg").val("Mình muốn hỏi về sản phẩm " + tenSP + " này");
        }
    });

    // Hiển thị menu khi click nút ...
    $("#messages").on("click", ".btn-options", function (e) {
        e.stopPropagation(); 
        $(this).siblings(".options-menu").toggle();
    });

    // Click ra ngoài đóng menu
    $(document).click(function () {
        $(".options-menu").hide();
    });

    $("#chkUnread").change(function () {
        if (this.checked) {
            $(".user-item").each(function () {
                const unread = Number($(this).data("unread"));
                if (unread !== 1) {
                    $(this).hide();
                }
            });
        } else {
            $(".user-item").show();
        }
    });
</script>
<style>
    .seen-text {
        font-size: 11px;
        color: rgba(255,255,255,0.8);
        text-align: right;
        margin-top: 4px;
    }
    .user-item[data-unread="1"] {
        background: #2a2d44;
    }
</style>
@endsection
