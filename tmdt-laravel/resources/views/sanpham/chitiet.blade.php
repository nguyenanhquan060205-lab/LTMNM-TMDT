@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@php
    $trungBinh = $TrungBinhDanhGia ?? 0;
    $tongDanhGia = $TongDanhGia ?? 0;

    $anhBiaObj = collect($sp->hinhAnhs)->firstWhere('AnhBia', true);
    $anhBia = $anhBiaObj ? $anhBiaObj->URLAnh : ($sp->AnhBia ?? "noimage.jpg");
    
    $related = $SPLienQuan ?? collect();
    $currentUser = Session::get('user');
@endphp

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
                @foreach ($AnhChiTiet as $i => $anh)
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

            <h3 class="fw-bold mb-2 text-dark">{{ $sp->TenSP }}</h3>
            <p class="fs-3 fw-bold" style="color: #0d6efd;">{{ number_format($sp->Gia, 0, ',', '.') }} ₫</p>

            <p><b>Mô tả:</b> {!! nl2br(e($sp->MoTa)) !!}</p>

            <p>
                <b>Số lượng còn:</b>
                <span class="fw-bold {{ $sp->SoLuong > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $sp->SoLuong > 0 ? $sp->SoLuong : 'Hết hàng' }}
                </span>
            </p>

            <style>
                .badge-custom { padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; letter-spacing: 0.5px; }
                .badge-success-custom { background-color: #0d6efd; color: white; }
                .badge-danger-custom { background-color: #1e293b; color: white; }
                .badge-pending-custom { background-color: white; color: #1e293b; border: 1px solid #1e293b; }
            </style>
            <p>
                <b>Trạng thái:</b>
                <span class="badge badge-custom {{ $sp->TrangThai == 'Đã bán' ? 'badge-danger-custom' :
                                     ($sp->TrangThai == 'Đã duyệt' ? 'badge-success-custom' : 'badge-pending-custom') }}">
                    {{ $sp->TrangThai }}
                </span>
            </p>

            <p>
                <b>Đánh giá:</b>

                @php
                    $rating = (float)$trungBinh;
                    $fullStars = (int)floor($rating);
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
                <a href="{{ route('sanpham.thongtinnguoiban', ['id' => $sp->nguoiDung->MaKH]) }}" class="text-primary fw-bold">
                    {{ $sp->nguoiDung->HoTen }}
                </a><br />
                <small class="text-muted">{{ $sp->nguoiDung->Email }}</small>
            </p>

            <div class="mt-4 d-flex flex-wrap gap-3">
                @if ($sp->SoLuong > 0 && $sp->TrangThai != "Đã bán")
                    <a href="{{ route('giohang.them', ['id' => $sp->MaSP]) }}"
                       class="btn btn-dark fw-bold px-4 rounded-pill shadow-sm">
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                    </a>
                @else
                    <button class="btn btn-secondary fw-bold px-4 rounded-pill" disabled>
                        <i class="bi bi-x-circle"></i> Hết hàng
                    </button>
                @endif

                <a href="{{ route('tinnhan.chat', [
                        'idNguoiNhan' => $sp->nguoiDung->MaKH,
                        'maSP' => $sp->MaSP
                    ]) }}"
                   class="btn btn-outline-dark fw-bold px-4 rounded-pill">
                    <i class="bi bi-chat-dots"></i> Liên hệ
                </a>

                <!-- Nút khiếu nại: chỉ hiện nếu user KHÔNG PHẢI người bán -->
                @if ($currentUser && $currentUser->MaKH != $sp->nguoiDung->MaKH)
                    <a href="{{ route('khieunai.taokhieunai', ['idsanpham' => $sp->MaSP]) }}"
                       class="btn btn-light text-danger border fw-bold px-4 rounded-pill shadow-sm">
                        <i class="bi bi-exclamation-triangle"></i> Khiếu nại
                    </a>
                @endif
            </div>

        </div>
    </div>
    <h4 class="fw-bold mt-4">Đánh giá từ người mua</h4>

    @if ($ListDanhGia && count($ListDanhGia) > 0)
        @foreach ($ListDanhGia as $dg)
            <div class="review-box p-3 mt-3 rounded shadow-sm" style="background:#fafafa;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center" style="width:38px; height:38px;">
                        {{ strtoupper(substr($dg->nguoiDung->HoTen ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <b>{{ $dg->nguoiDung->HoTen ?? 'Khách hàng' }}</b> <br />
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $dg->SoSao ?? $dg->DiemDG)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="text-muted small"> • {{ $dg->NgayDG ? \Carbon\Carbon::parse($dg->NgayDG)->format('d/m/Y H:i') : '' }}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="mb-1">{{ $dg->NoiDung }}</p>
                </div>
            </div>
        @endforeach

        <!-- PHÂN TRANG ĐÁNH GIÁ -->
        @php
            $current = $PageDG;
            $total = $TotalPageDG;
        @endphp

        @if ($total > 1)
            <nav class="mt-3 d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $current - 1, 'pageSP' => $PageSP]) }}">«</a>
                    </li>

                    <li class="page-item {{ $current == 1 ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => 1, 'pageSP' => $PageSP]) }}">1</a>
                    </li>

                    @if ($current > 3)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    @for ($i = $current - 1; $i <= $current + 1; $i++)
                        @if ($i > 1 && $i < $total)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $i, 'pageSP' => $PageSP]) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    @if ($current < $total - 2)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif

                    @if ($total > 1)
                        <li class="page-item {{ $current == $total ? 'active' : '' }}">
                            <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $total, 'pageSP' => $PageSP]) }}">{{ $total }}</a>
                        </li>
                    @endif

                    <li class="page-item {{ $current == $total ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $current + 1, 'pageSP' => $PageSP]) }}">»</a>
                    </li>
                </ul>
            </nav>
        @endif
    @else
        <p class="text-muted">Chưa có đánh giá nào.</p>
    @endif



    <!-- ========================== -->
    <!-- SẢN PHẨM LIÊN QUAN -->
    <!-- ========================== -->
    <h3 class="fw-bold mt-5 mb-4 text-center">Sản phẩm liên quan</h3>

    <div class="row g-4 justify-content-center">
        @if ($related->isNotEmpty())
            @foreach ($related as $item)
                @php
                    $anhBiaItemObj = collect($item->hinhAnhs)->firstWhere('AnhBia', true);
                    $anhBiaItem = $anhBiaItemObj ? $anhBiaItemObj->URLAnh : ($item->AnhBia ?? "noimage.jpg");
                @endphp

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100 shadow-sm border-0 position-relative">
                        <div class="position-relative rounded-top bg-white" style="border-bottom: 1px solid #f8f9fa;">
                            <div class="ratio ratio-1x1 overflow-hidden">
                                <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                    <img src="{{ str_starts_with($anhBiaItem, 'http') ? $anhBiaItem : asset('Content/Images/' . $anhBiaItem) }}" alt="{{ $item->TenSP }}" class="object-fit-contain w-100 h-100 p-3" />
                                </a>
                            </div>
                            
                            @if(($item->DanhGiaTB ?? 0) > 0)
                            <div class="position-absolute bottom-0 end-0 m-2 z-3 bg-white rounded-pill px-2 py-1 shadow-sm d-flex align-items-center">
                                <i class="fa-solid fa-star text-warning me-1" style="font-size: 10px;"></i>
                                <span class="fw-bold" style="font-size: 11px;">{{ number_format($item->DanhGiaTB, 1) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <small class="text-primary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                {{ $item->loaiSanPham->TenLoai ?? 'Khác' }}
                            </small>
                            
                            <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}" class="text-decoration-none text-dark">
                                <h6 class="product-title line-clamp-2 mb-3" style="min-height: 2.4rem;">
                                    {{ $item->TenSP }}
                                </h6>
                            </a>

                            <div class="mt-auto d-flex align-items-end justify-content-between">
                                <div class="product-price text-primary" style="font-size: 1.1rem; font-weight: 700;">
                                    {{ number_format($item->Gia, 0, ',', '.') }}₫
                                </div>
                                <a class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                                   style="width: 40px; height: 40px; transition: transform 0.2s;"
                                   onmouseover="this.style.transform='scale(1.1)'"
                                   onmouseout="this.style.transform='scale(1)'"
                                   href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                    <i class="fa-solid fa-cart-shopping fs-6"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-muted">Không có sản phẩm liên quan.</p>
        @endif
    </div>

    <!-- PHÂN TRANG SẢN PHẨM LIÊN QUAN -->
    @php
        $currentSP = $PageSP ?? 1;
        $totalSP = $TotalPageSP ?? 0;
    @endphp

    @if ($totalSP > 1)
        <nav class="mt-4 d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item {{ $currentSP == 1 ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $PageDG, 'pageSP' => $currentSP - 1]) }}">«</a>
                </li>

                <li class="page-item {{ $currentSP == 1 ? 'active' : '' }}">
                    <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $PageDG, 'pageSP' => 1]) }}">1</a>
                </li>

                @if ($currentSP > 3)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif

                @for ($i = $currentSP - 1; $i <= $currentSP + 1; $i++)
                    @if ($i > 1 && $i < $totalSP)
                        <li class="page-item {{ $i == $currentSP ? 'active' : '' }}">
                            <a class="page-link"
                               href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $PageDG, 'pageSP' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                @if ($currentSP < $totalSP - 2)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif

                @if ($totalSP > 1)
                    <li class="page-item {{ $currentSP == $totalSP ? 'active' : '' }}">
                        <a class="page-link" href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $PageDG, 'pageSP' => $totalSP]) }}">{{ $totalSP }}</a>
                    </li>
                @endif

                <li class="page-item {{ $currentSP == $totalSP ? 'disabled' : '' }}">
                    <a class="page-link"
                       href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP, 'pageDG' => $PageDG, 'pageSP' => $currentSP + 1]) }}">»</a>
                </li>
            </ul>
        </nav>
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
        border-color: #0d6efd !important;
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

    @keyframes slideFade {
        0% { opacity: 0.4; transform: translateX(20px); }
        100% { opacity: 1; transform: translateX(0); }
    }
    .slide-anim {
        animation: slideFade 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
</style>
@endsection

@section('scripts')
<!-- ======================= -->
<!-- SLIDER JS -->
<!-- ======================= -->
<script>
    let images = [
        "{{ $anhBia }}",
        @foreach ($AnhChiTiet as $item)
            "{!! $item->URLAnh !!}",
        @endforeach
    ];

    let currentIndex = 0;

    function updateMainImg() {
        const mainImg = document.getElementById("mainImg");
        
        // Remove class to reset animation
        mainImg.classList.remove("slide-anim");
        
        // Trigger DOM reflow to apply the animation again
        void mainImg.offsetWidth;
        
        mainImg.src = images[currentIndex].startsWith('http') ? images[currentIndex] : '/Content/Images/' + images[currentIndex];
        mainImg.classList.add("slide-anim");

        document.querySelectorAll(".thumb").forEach((t, i) => {
            t.classList.toggle("thumb-active", i === currentIndex);
        });
    }

    let autoSlideInterval;

    function startAutoSlide() {
        if (images.length > 1) {
            autoSlideInterval = setInterval(() => {
                nextImage();
            }, 5000);
        }
    }

    function resetAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }
    }

    function changeImage(index) {
        currentIndex = index;
        updateMainImg();
        resetAutoSlide();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateMainImg();
        resetAutoSlide();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateMainImg();
        resetAutoSlide();
    }

    function openLightbox() {
        if (autoSlideInterval) clearInterval(autoSlideInterval);
        const src = document.getElementById("mainImg").src;
        document.getElementById("lightboxImg").src = src;
        document.getElementById("lightbox").style.display = "flex";
    }

    function closeLightbox() {
        document.getElementById("lightbox").style.display = "none";
        startAutoSlide();
    }

    document.addEventListener("DOMContentLoaded", startAutoSlide);
</script>
@endsection
