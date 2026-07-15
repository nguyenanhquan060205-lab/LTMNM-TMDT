{{-- @model ThuongMaiDienTu_DoAn.Models.SANPHAM --}}

@extends('shared._layout')

<div class="container mt-5">

    <div class="row">

        <!-- ====================== -->
        <!-- ẢNH SẢN PHẨM CHÍNH -->
        <!-- ====================== -->
        <div class="col-md-6 text-center">

            <!-- Hộp ảnh cố định giống CellphoneS -->
            <div class="main-img-box position-relative mx-auto mb-3">
                <img id="mainImg"
                     src="{{ asset('Content/') }}/Images/@anhBia"
                     class="main-img" 
                     onclick="openLightbox()"
                     style="cursor: zoom-in;" />

                <!-- Mũi tên chuyển ảnh -->
                <button class="img-nav left" onclick="prevImage()">❮</button>
                <button class="img-nav right" onclick="nextImage()">❯</button>
            </div>

            <!-- Thumbnail -->
            <div class="d-flex justify-content-center gap-2 flex-wrap mt-2">

                <!-- Ảnh bìa -->
                <img src="{{ asset('Content/') }}/Images/@anhBia"
                     class="thumb thumb-active"
                     onclick="changeImage(0)" />

                <!-- Ảnh phụ -->
                @for (int i = 0; i < anhChiTiet.Count; i++)
                {
                    <img src="{{ asset('Content/') }}/Images/@anhChiTiet[i].URLAnh"
                         class="thumb"
                         onclick="changeImage(@(i+1))" />
                }
            </div>

        </div>

        <!-- ====================== -->
        <!-- THÔNG TIN SẢN PHẨM -->
        <!-- ====================== -->
        <div class="col-md-6">

            <h3 class="fw-bold mb-2">$Model.TenSP</h3>
            <p class="text-danger fs-3 fw-bold">@String.Format("{0:N0} ₫", Model.Gia)</p>

            <p><b>Mô tả:</b> $Model.MoTa</p>

            <p>
                <b>Số lượng còn:</b>
                <span class="fw-bold @(Model.SoLuong > 0 ? "text-success" : "text-danger")">
                    @(Model.SoLuong > 0 ? Model.SoLuong.ToString() : "Hết hàng")
                </span>
            </p>

            <p>
                <b>Trạng thái:</b>
                <span class="badge @(Model.TrangThai == "Đã bán" ? "bg-danger" :
                                     Model.TrangThai == "Đã duyệt" ? "bg-success" : "bg-secondary")">
                    $Model.TrangThai
                </span>
            </p>

            <p>
                <b>Đánh giá:</b>

                @{
                    double rating = trungBinh;
                    int fullStars = (int)Math.Floor(rating);
                    bool hasHalfStar = (rating - fullStars) >= 0.5;
                }

                @for (int i = 1; i <= 5; i++)
                {
                    if (i <= fullStars)
                    {
                        <i class="bi bi-star-fill text-warning"></i>
                    }
                    else if (i == fullStars + 1 && hasHalfStar)
                    {
                        <i class="bi bi-star-half text-warning"></i>
                    }
                    else
                    {
                        <i class="bi bi-star text-warning"></i>
                    }
                }

                <span> (@tongDanhGia đánh giá)</span>
            </p>

            <hr>

            <p>
                <b>Người bán:</b>
                <span class="text-primary fw-bold">$Model.NGUOIDUNG.HoTen</span><br />
                <small class="text-muted">$Model.NGUOIDUNG.Email</small>
            </p>

            <div class="mt-4 d-flex flex-wrap gap-3">

                <!-- NÚT CHO NGƯỜI BÁN (QUẢN LÝ) -->
                <a href="@Url.Action("Sua", "SanPham", new { id = Model.MaSP })"
                   class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-pencil-square"></i> Sửa sản phẩm
                </a>
                <a href="@Url.Action("Xoa", "SanPham", new { id = Model.MaSP })"
                   class="btn btn-danger fw-bold px-4"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                    <i class="bi bi-trash"></i> Xóa sản phẩm
                </a>

                <!-- KHÔNG HIỂN THỊ NÚT THÊM VÀO GIỎ / LIÊN HỆ -->
                @* Nút Khiếu nại (Chủ sở hữu không bao giờ thấy nút khiếu nại) *@
            </div>

        </div>
    </div>
    <!-- ========================== -->
    <!-- ĐÁNH GIÁ TỪ NGƯỜI MUA -->
    <!-- ========================== -->
    <h4 class="fw-bold mt-4">Đánh giá từ người mua</h4>

    @if ($ListDanhGia != null && ((List<ThuongMaiDienTu_DoAn.Models.DANHGIA>)$ListDanhGia).Count > 0)
    {
        var list = $ListDanhGia as List<ThuongMaiDienTu_DoAn.Models.DANHGIA>;

        foreach (var dg in list)
        {
            <div class="review-box p-3 mt-3 rounded shadow-sm" style="background:#fafafa;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                         style="width:38px; height:38px;">
                        @dg.NGUOIDUNG.HoTen.Substring(0, 1)
                    </div>

                    <div>
                        <b>@dg.NGUOIDUNG.HoTen</b> <br />
                        <span class="text-warning">
                            @for (int i = 1; i <= 5; i++)
                            {
                                if (i <= dg.SoSao)
                                {
                                    <i class="bi bi-star-fill"></i>
                                }
                                else
                                {
                                    <i class="bi bi-star"></i>
                                }
                            }
                        </span>
                        <span class="text-muted small">
                            • @(dg.NgayDG?.ToString("dd/MM/yyyy HH:mm"))
                        </span>
                    </div>
                </div>

                <div class="mt-2">
                    <p class="mb-1">@dg.NoiDung</p>
                </div>
            </div>
        }

        <!-- PHÂN TRANG -->
        int current = $PageDG;
        int total = $TotalPageDG;

        if (total > 1)
        {
            <nav class="mt-3 d-flex justify-content-center">
                <ul class="pagination">

                    <li class="page-item @(current == 1 ? "disabled" : "")">
                        <a class="page-link" href="@Url.Action("ChiTiet", new { id = Model.MaSP, pageDG = current - 1, pageSP = $PageSP })">«</a>
                    </li>

                    <li class="page-item @(current == 1 ? "active" : "")">
                        <a class="page-link" href="@Url.Action("ChiTiet", new { id = Model.MaSP, pageDG = 1, pageSP = $PageSP })">1</a>
                    </li>

                    @if (current > 3)
                    {
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    }

                    @for (int i = current - 1; i <= current + 1; i++)
                    {
                        if (i > 1 && i < total)
                        {
                            <li class="page-item @(i == current ? "active" : "")">
                                <a class="page-link" href="@Url.Action("ChiTiet", new { id = Model.MaSP, pageDG = i, pageSP = $PageSP })">@i</a>
                            </li>
                        }
                    }

                    @if (current < total - 2)
                    {
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    }

                    @if (total > 1)
                    {
                        <li class="page-item @(current == total ? "active" : "")">
                            <a class="page-link" href="@Url.Action("ChiTiet", new { id = Model.MaSP, pageDG = total, pageSP = $PageSP })">@total</a>
                        </li>
                    }

                    <li class="page-item @(current == total ? "disabled" : "")">
                        <a class="page-link" href="@Url.Action("ChiTiet", new { id = Model.MaSP, pageDG = current + 1, pageSP = $PageSP })">»</a>
                    </li>

                </ul>
            </nav>
        }
    }
    else
    {
        <p class="text-muted">Chưa có đánh giá nào.</p>
    }

    <!-- ========================== -->
    <!-- SẢN PHẨM LIÊN QUAN -->
    <!-- ========================== -->
    @*<h3 class="fw-bold mt-5 mb-4 text-center">Sản phẩm liên quan</h3>

        <div class="row g-4 justify-content-center">

            @if (related != null && related.Count > 0)
            {
                foreach (var item in related)
                {
                    var anh = item.HINHANHSPs.FirstOrDefault(a => a.AnhBia == true)?.URLAnh ?? "no-image.jpg";

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card product-card border-0 shadow-sm h-100">

                            <a href="@Url.Action("ChiTiet","SanPham", new { id = item.MaSP })">
                                <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                                    <img src="{{ asset('Content/') }}/Images/@anh"
                                         class="card-img-top p-3"
                                         style="object-fit: contain; width:100%; height:100%;" />
                                </div>
                            </a>

                            <div class="card-body text-center d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-semibold text-truncate">@item.TenSP</h6>
                                    <p class="text-danger fw-bold mb-1">@String.Format("{0:N0} ₫", item.Gia)</p>
                                    <p class="small text-muted">@item.LOAISANPHAM.TenLoai</p>
                                </div>

                                <a href="@Url.Action("ChiTiet","SanPham", new { id = item.MaSP })"
                                   class="btn btn-warning w-100 fw-semibold mt-2 rounded-pill shadow-sm">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                }
            }
            else
            {
                <p class="text-center text-muted">Không có sản phẩm liên quan.</p>
            }
        </div>*@
</div>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close" onclick="closeLightbox()">×</span>
    <img id="lightboxImg" class="lightbox-img" />
</div>

<!-- ================================= -->
<!-- CSS CHO SLIDER ẢNH GIỐNG CELLPHONES -->
<!-- ================================= -->
<style>
    .main-img-box {
        width: 450px;
        height: 450px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        position: relative;
    }

    .main-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .thumb {
        width: 90px;
        height: 90px;
        object-fit: cover;
        cursor: pointer;
        border-radius: 6px;
        border: 2px solid transparent;
    }

    .thumb-active {
        border-color: #ff9800 !important;
    }

    .img-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: rgba(0,0,0,0.45);
        color: white;
        padding: 8px 12px;
        font-size: 22px;
        border-radius: 50%;
        cursor: pointer;
        transition: .2s;
    }

        .img-nav.left {
            left: 12px;
        }

        .img-nav.right {
            right: 12px;
        }

        .img-nav:hover {
            background: rgba(0,0,0,0.7);
        }

    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        background: rgba(0,0,0,0.85);
        justify-content: center;
        align-items: center;
    }

    .lightbox-img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        animation: zoomIn 0.3s ease;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 40px;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }

    @@keyframes zoomIn {
        from {
            transform: scale(0.85);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

<!-- ======================= -->
<!-- SLIDER JS -->
<!-- ======================= -->
<script>
    let images = [
        "@anhBia",
        @foreach (var item in anhChiTiet)
        {
            {!! $"\"{item.URLAnh}\"," !!}
        }
    ];

    let currentIndex = 0;

    function updateMainImg() {
        document.getElementById("mainImg").src = "/Content/Images/" + images[currentIndex];

        document.querySelectorAll(".thumb").forEach((t, i) => {
            t.classList.toggle("thumb-active", i === currentIndex);
        });
    }

    function changeImage(index) {
        currentIndex = index;
        updateMainImg();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateMainImg();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateMainImg();
    }

    function openLightbox() {
        const src = document.getElementById("mainImg").src;
        document.getElementById("lightboxImg").src = src;
        document.getElementById("lightbox").style.display = "flex";
    }

    function closeLightbox() {
        document.getElementById("lightbox").style.display = "none";
    }
</script>