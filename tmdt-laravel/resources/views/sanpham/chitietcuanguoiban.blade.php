@php
    $trungBinh = $TrungBinhDanhGia ?? 0;
    $tongDanhGia = $TongDanhGia ?? 0;

    $anhBia = 'no-image.jpg';
    $anhChiTiet = [];
    if (isset($sp) && $sp->hinhAnhs) {
        $anhBiaObj = collect($sp->hinhAnhs)->firstWhere('AnhBia', true);
        if ($anhBiaObj) {
            $anhBia = $anhBiaObj->URLAnh;
        }
        $anhChiTiet = collect($sp->hinhAnhs)->where('AnhBia', false)->values();
    }
    
    $related = $SPLienQuan ?? [];
@endphp

@extends('shared._layout')
@section('title', 'Chi tiết sản phẩm')

@section('content')

<div class="container mt-5">

    <div class="row">

        <!-- ====================== -->
        <!-- ẢNH SẢN PHẨM CHÍNH -->
        <!-- ====================== -->
        <div class="col-md-6 text-center">

            <!-- Hộp ảnh cố định giống CellphoneS -->
            <div class="main-img-box position-relative mx-auto mb-3">
                <img id="mainImg"
                     src="{{ str_starts_with($anhBia, 'http') ? $anhBia : asset('Content/Images/' . $anhBia) }}"
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
                <img src="{{ str_starts_with($anhBia, 'http') ? $anhBia : asset('Content/Images/' . $anhBia) }}"
                     class="thumb thumb-active"
                     onclick="changeImage(0)" />

                <!-- Ảnh phụ -->
                @foreach ($anhChiTiet as $i => $anh)
                    <img src="{{ str_starts_with($anh->URLAnh, 'http') ? $anh->URLAnh : asset('Content/Images/' . $anh->URLAnh) }}"
                         class="thumb"
                         onclick="changeImage({{ $i + 1 }})" />
                @endforeach
            </div>

        </div>

        <!-- ====================== -->
        <!-- THÔNG TIN SẢN PHẨM -->
        <!-- ====================== -->
        <div class="col-md-6">

            <h3 class="fw-bold mb-2">{{ $sp->TenSP ?? '' }}</h3>
            <p class="text-danger fs-3 fw-bold">{{ number_format($sp->Gia ?? 0, 0, ',', '.') }} ₫</p>

            <p><b>Mô tả:</b> {{ $sp->MoTa ?? '' }}</p>

            <p>
                <b>Số lượng còn:</b>
                <span class="fw-bold {{ ($sp->SoLuong ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                    {{ ($sp->SoLuong ?? 0) > 0 ? $sp->SoLuong : 'Hết hàng' }}
                </span>
            </p>

            <p>
                <b>Trạng thái:</b>
                @php
                    $trangThai = $sp->TrangThai ?? '';
                    $badgeClass = 'bg-secondary';
                    if ($trangThai == 'Đã bán') $badgeClass = 'bg-danger';
                    if ($trangThai == 'Đã duyệt') $badgeClass = 'bg-success';
                @endphp
                <span class="badge {{ $badgeClass }}">
                    {{ $trangThai }}
                </span>
            </p>

            <p>
                <b>Đánh giá:</b>

                @php
                    $rating = $trungBinh;
                    $fullStars = floor($rating);
                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                @endphp

                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $fullStars)
                        <i class="bi bi-star-fill text-warning"></i>
                    @elseif ($i == $fullStars + 1 && $hasHalfStar)
                        <i class="bi bi-star-half text-warning"></i>
                    @else
                        <i class="bi bi-star text-warning"></i>
                    @endif
                @endfor

                <span> ({{ $tongDanhGia }} đánh giá)</span>
            </p>

            <hr>

            <p>
                <b>Người bán:</b>
                <span class="text-primary fw-bold">{{ $sp->nguoiDung->HoTen ?? '' }}</span><br />
                <small class="text-muted">{{ $sp->nguoiDung->Email ?? '' }}</small>
            </p>

            <div class="mt-4 d-flex flex-wrap gap-3">

                <!-- NÚT CHO NGƯỜI BÁN (QUẢN LÝ) -->
                <a href="{{ url('/sanpham/sua/' . ($sp->MaSP ?? '')) }}"
                   class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-pencil-square"></i> Sửa sản phẩm
                </a>
                <a href="{{ url('/sanpham/xoa/' . ($sp->MaSP ?? '')) }}"
                   class="btn btn-danger fw-bold px-4"
                   onclick="event.preventDefault(); Swal.fire({ title: 'Xác nhận', text: 'Bạn có chắc chắn muốn xóa sản phẩm này không?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy', confirmButtonColor: '#d33' }).then((result) => { if (result.isConfirmed) window.location.href = this.href; });">
                    <i class="bi bi-trash"></i> Xóa sản phẩm
                </a>

                <!-- KHÔNG HIỂN THỊ NÚT THÊM VÀO GIỎ / LIÊN HỆ -->
                {{-- Nút Khiếu nại (Chủ sở hữu không bao giờ thấy nút khiếu nại) --}}
            </div>

        </div>
    </div>
    <!-- ========================== -->
    <!-- ĐÁNH GIÁ TỪ NGƯỜI MUA -->
    <!-- ========================== -->
    <h4 class="fw-bold mt-4">Đánh giá từ người mua</h4>

    @if (isset($ListDanhGia) && collect($ListDanhGia)->isNotEmpty())
        @foreach ($ListDanhGia as $dg)
            <div class="review-box p-3 mt-3 rounded shadow-sm" style="background:#fafafa;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                         style="width:38px; height:38px;">
                        {{ substr($dg->NGUOIDUNG->HoTen ?? 'U', 0, 1) }}
                    </div>

                    <div>
                        <b>{{ $dg->NGUOIDUNG->HoTen ?? '' }}</b> <br />
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $dg->SoSao)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="text-muted small">
                            • {{ $dg->NgayDG ? \Carbon\Carbon::parse($dg->NgayDG)->format('d/m/Y HH:mm') : '' }}
                        </span>
                    </div>
                </div>

                <div class="mt-2">
                    <p class="mb-1">{{ $dg->NoiDung }}</p>
                </div>
            </div>
        @endforeach

        <!-- PHÂN TRANG -->
        @php
            $current = $PageDG ?? 1;
            $total = $TotalPageDG ?? 1;
        @endphp

        @if ($total > 1)
            <nav class="mt-3 d-flex justify-content-center">
                <ul class="pagination">

                    <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ url('/sanpham/chitiet/' . $sp->MaSP . '?pageDG=' . ($current - 1) . '&pageSP=' . ($PageSP ?? 1)) }}">«</a>
                    </li>

                    <li class="page-item {{ $current == 1 ? 'active' : '' }}">
                        <a class="page-link" href="{{ url('/sanpham/chitiet/' . $sp->MaSP . '?pageDG=1&pageSP=' . ($PageSP ?? 1)) }}">1</a>
                    </li>

                    @if ($current > 3)
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    @endif

                    @for ($i = $current - 1; $i <= $current + 1; $i++)
                        @if ($i > 1 && $i < $total)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ url('/sanpham/chitiet/' . $sp->MaSP . '?pageDG=' . $i . '&pageSP=' . ($PageSP ?? 1)) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    @if ($current < $total - 2)
                        <li class="page-item disabled">
                            <span class="page-link">…</span>
                        </li>
                    @endif

                    @if ($total > 1)
                        <li class="page-item {{ $current == $total ? 'active' : '' }}">
                            <a class="page-link" href="{{ url('/sanpham/chitiet/' . $sp->MaSP . '?pageDG=' . $total . '&pageSP=' . ($PageSP ?? 1)) }}">{{ $total }}</a>
                        </li>
                    @endif

                    <li class="page-item {{ $current == $total ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ url('/sanpham/chitiet/' . $sp->MaSP . '?pageDG=' . ($current + 1) . '&pageSP=' . ($PageSP ?? 1)) }}">»</a>
                    </li>

                </ul>
            </nav>
        @endif
    @else
        <p class="text-muted">Chưa có đánh giá nào.</p>
    @endif

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

    @keyframes zoomIn {
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
    let images = @json(collect($anhChiTiet)->pluck('URLAnh')->prepend($anhBia));

    let currentIndex = 0;

    function updateMainImg() {
        document.getElementById("mainImg").src = images[currentIndex].startsWith('http') ? images[currentIndex] : '/Content/Images/' + images[currentIndex];

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
@endsection
