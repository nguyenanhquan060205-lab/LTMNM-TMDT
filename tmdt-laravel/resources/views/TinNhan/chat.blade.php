{{-- @model IEnumerable<ThuongMaiDienTu_DoAn.Models.NGUOIDUNG> --}}

@extends('shared._layoutAdmin')
    else
    {
        Layout = "~/Views/Shared/_Layout.cshtml";
    }
}

<input type="hidden" id="maSP" value="$MaSP" />
<input type="hidden" id="anhSP" value="$AnhSP" />
<input type="hidden" id="tenSP" value="$TenSP" />

@{
    string token = Html.AntiForgeryToken().ToString();
}
<input type="hidden" id="RequestVerificationToken" value='@Html.AntiForgeryToken().ToString()' />

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
        max-width: 70%;
        margin: 0 8px;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,.1);
    }

    .msg.me {
        justify-content: flex-end;
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
    .unread-msg .bubble {
        border: 2px solid #ff9800;
        background: #fff8e1;
    }
</style>

@if (!showAdminLayout)
{
    <div style="width:85%; margin:0 auto;">
        <h2 class="fw-bold text-primary mb-3 mt-4">
            <i class="bi bi-receipt-cutoff"></i> Tin Nhắn
        </h2>
    </div>
}

<input type="hidden" id="NguoiGuiID" value="$NguoiGuiID" />
<input type="hidden" id="NguoiNhanID" value="$NguoiNhanID" />

<div class="chat-container @(showAdminLayout ? "admin-view" : "")">

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

        @*@foreach (var u in Model)
        {
            <div class="user-item @(u.MaKH == $NguoiNhanID ? "active" : "")"
                 onclick="location.href='@Url.Action("Chat", "TinNhan", new { idNguoiNhan = u.MaKH, mode = Request.QueryString["mode"] })'">

                <img src="{{ asset('Content/') }}/avatars/@(string.IsNullOrEmpty(u.AnhDaiDien) ? "Default.jpg" : u.AnhDaiDien)"
                     style="width:30px;height:30px;border-radius:50%;object-fit:cover;" />

                <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    <b>@u.HoTen</b>
                </div>
            </div>
        }*@
        @{
            var userChuaDoc = $UserChuaDoc as List<int> ?? new List<int>();
        }

        @foreach (var u in Model)
        {
            bool chuaDoc = userChuaDoc.Contains(u.MaKH);

            <div class="user-item @(u.MaKH == $NguoiNhanID ? "active" : "")"
                 data-unread="@(chuaDoc ? 1 : 0)"
                 onclick="location.href='@Url.Action("Chat", "TinNhan", new { idNguoiNhan = u.MaKH, mode = Request.QueryString["mode"] })'">

                <img src="{{ asset('Content/') }}/avatars/@(string.IsNullOrEmpty(u.AnhDaiDien) ? "Default.jpg" : u.AnhDaiDien)"
                     style="width:30px;height:30px;border-radius:50%;object-fit:cover;" />

                <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    <b>@u.HoTen</b>

                    @if (chuaDoc)
                    {
                        <span class="ms-1 text-warning">●</span>
                    }
                </div>
            </div>
        }
    </div>

    <!-- ======================= CHAT BOX ======================= -->
    <div class="chat-box">
        <div class="chat-header d-flex justify-content-between align-items-center">
            <div>
                @if ($NguoiNhanID != 0)
                {
                    <i class="fa-solid fa-circle-user me-2 text-primary"></i> $NguoiNhanTen
                }
                else
                { <span>Chưa chọn người để trò chuyện</span>}
            </div>

            <div style="font-size:14px;">
                <input type="checkbox" id="chkUnread" />
                <label for="chkUnread">Chỉ tin chưa xem</label>
            </div>
        </div>


        <div id="messages" class="chat-body">
            @if ($NguoiNhanID == 0)
            {
                <div class="text-center text-muted mt-5">
                    <i class="fa-regular fa-comments fa-3x mb-3"></i><br />
                    Vui lòng chọn một người dùng bên trái để bắt đầu
                </div>
            }
        </div>

        @if ($NguoiNhanID != 0)
        {
            @*<div class="chat-input">
                <input id="txtMsg" type="text" placeholder="Nhập tin nhắn...">
                <button id="btnSend" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i></button>
            </div>*@

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

        }
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

        $.getJSON('/TinNhan/LoadTinNhan', { idNguoiGui: idGui, idNguoiNhan: idNhan }, function (data) {

            let html = "";
            let lastMyIndex = -1;

            for (let i = data.length - 1; i >= 0; i--) {
                if (data[i].NguoiGui == idGui) {
                    lastMyIndex = i;
                    break;
                }
            }

            $.each(data, function (i, m) {
                let isMe = m.NguoiGui == idGui;
                let av = m.AvatarGui ? `/Content/avatars/${m.AvatarGui}` : '/Content/avatars/Default.jpg';
                let unreadClass = (!m.DaDoc && m.NguoiGui != idGui) ? "unread-msg" : "";

                html += `
                <div class="msg ${isMe ? 'me' : 'them'} ${unreadClass}" data-dadoc="${m.DaDoc}">
                    ${!isMe ? `<img class="avatar" src="${av}">` : ""}

                    ${isMe ? `
                    <div class="msg-options">
                        <button class="btn-options">&#x2026;</button>
                        <div class="options-menu" style="display:none;">
                            <form method="post" action="/TinNhan/XoaTinNhan"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa tin nhắn này?');">
                                <input type="hidden" name="idTin" value="${m.MaTN}" />
                                <button type="submit" class="text-danger">
                                    <i class="fa-solid fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                    ` : ``}

                    <div class="bubble">
                        ${m.Anh
                        ? `<img src="/Content/chat_images/${m.Anh}"
                                   class="chat-image">`
                        : ""
                        }

                        ${m.NoiDung
                        ? `<div class="chat-text">${m.NoiDung}</div>`
                        : ""
                        }

                        <div class="bubble-time">${m.Gio}</div>

                        ${isMe && i === lastMyIndex && m.DaDoc
                        ? `<div class="seen-text">Đã xem</div>`
                        : ``}
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

    //$("#btnSend").click(function () {
    //    const idGui = $('#NguoiGuiID').val();
    //    const idNhan = $('#NguoiNhanID').val();
    //    const msg = $("#txtMsg").val().trim();
    //    if (!msg) return;

    //    $.post('/TinNhan/GuiTinNhan', { nguoiGui: idGui, nguoiNhan: idNhan, noiDung: msg }, function () {
    //        $("#txtMsg").val('');
    //        autoScroll = true;
    //        loadTinNhan();
    //    });
    //});

    $("#btnSend").click(function () {
        const idGui = $('#NguoiGuiID').val();
        const idNhan = $('#NguoiNhanID').val();
        const msg = $("#txtMsg").val().trim();
        const file = $("#imgUpload")[0].files[0];

        if (!msg && !file) return;

        let formData = new FormData();
        formData.append("nguoiGui", idGui);
        formData.append("nguoiNhan", idNhan);
        formData.append("noiDung", msg);
        if (file) formData.append("anh", file);

        $.ajax({
            url: "/TinNhan/GuiTinNhan",
            type: "POST",
            data: formData,
            processData: false, // ⛔ bắt buộc
            contentType: false, // ⛔ bắt buộc
            success: function () {
                $("#txtMsg").val('');
                $("#imgUpload").val('');
                $("#imagePreview").hide();
                autoScroll = true;
                loadTinNhan();
            }
        });
    });

    $('#txtMsg').keypress(function (e) {
        if (e.which === 13) { $("#btnSend").click(); return false; }
    });

    setInterval(loadTinNhan, 2000);
    loadTinNhan();
    function danhDauDaDoc() {
        const idGui = $('#NguoiGuiID').val();
        const idNhan = $('#NguoiNhanID').val();
        if (idNhan == 0) return;

        $.post('/TinNhan/DanhDauDaDoc', {
            idNguoiGui: idGui,
            idNguoiNhan: idNhan
        });
    }

    loadTinNhan();
    danhDauDaDoc();

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

        if (anhSP) {
            const imgUrl = `/Content/images/${anhSP}`;

            // preview ảnh
            $("#previewImg").attr("src", imgUrl);
            $("#imagePreview").show();

            // convert URL → File (để gửi như upload)
            fetch(imgUrl)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], anhSP, { type: blob.type });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    document.getElementById("imgUpload").files = dt.files;
                });

            $("#txtMsg").val("Mình muốn hỏi về sản phẩm {!! HttpUtility.JavaScriptStringEncode($TenSP !!}) này");
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
    .user-item[data-unread="True"] {
        background: #2a2d44;
    }
</style>
