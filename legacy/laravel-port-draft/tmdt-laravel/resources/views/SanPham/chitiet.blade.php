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
                     src="{{ asset('Content/Images/' . $anhBia) }}"
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
                <img src="{{ asset('Content/Images/' . $anhBia) }}"
                     class="thumb thumb-active"
                     onclick="changeImage(0)" />

                <!-- Ảnh phụ -->
                @foreach ($AnhChiTiet as $i => $anh)
                    <img src="{{ asset('Content/Images/' . $anh->URLAnh) }}"
                         class="thumb"
                         onclick="changeImage({{ $i + 1 }})" />
                @endforeach
            </div>

        </div>

        <!-- ====================== -->
        <!-- THÔNG TIN SẢN PHẨM -->
        <!-- ====================== -->
        <div class="col-md-6">

            <h3 class="fw-bold mb-2">{{ $sp->TenSP }}</h3>
            <p class="text-danger fs-3 fw-bold">{{ number_format($sp->Gia, 0, ',', '.') }} ₫</p>

            <p><b>Mô tả:</b> {{ $sp->MoTa }}</p>

            <p>
                <b>Số lượng còn:</b>
                <span class="fw-bold {{ $sp->SoLuong > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $sp->SoLuong > 0 ? $sp->SoLuong : 'Hết hàng' }}
                </span>
            </p>

            <p>
                <b>Trạng thái:</b>
                <span class="badge {{ $sp->TrangThai == 'Đã bán' ? 'bg-danger' :
                                     ($sp->TrangThai == 'Đã duyệt' ? 'bg-success' : 'bg-secondary') }}">
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
                       class="btn btn-warning fw-bold text-dark px-4">
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                    </a>
                @else
                    <button class="btn btn-secondary fw-bold px-4" disabled>
                        <i class="bi bi-x-circle"></i> Hết hàng
                    </button>
                @endif

                <a href="{{ route('tinnhan.chat', [
                        'idNguoiNhan' => $sp->nguoiDung->MaKH,
                        'maSP' => $sp->MaSP
                    ]) }}"
                   class="btn btn-outline-primary fw-bold px-4">
                    <i class="bi bi-chat-dots"></i> Liên hệ
                </a>

                <!-- Nút khiếu nại: chỉ hiện nếu user KHÔNG PHẢI người bán -->
                @if ($currentUser && $currentUser->MaKH != $sp->nguoiDung->MaKH)
                    <a href="{{ route('khieunai.taokhieunai', ['idSanPham' => $sp->MaSP]) }}"
                       class="btn btn-outline-danger fw-bold px-4">
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
                        <span class="text-muted small"> • {{ $dg->NgayDG ? \Carbon\Carbon::parse($dg->NgayDG)->format('d/m/Y HH:mm') : '' }}</span>
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
                    <div class="card product-card border-0 shadow-sm h-100">
                        <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                            <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                                <img src="{{ asset('Content/Images/' . $anhBiaItem) }}"
                                     class="card-img-top p-3"
                                     style="object-fit: contain; width:100%; height:100%;"
                                     alt="{{ $item->TenSP }}" />
                            </div>
                        </a>

                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-semibold text-truncate" title="{{ $item->TenSP }}">{{ $item->TenSP }}</h6>
                                <p class="text-danger fw-bold mb-1">{{ number_format($item->Gia, 0, ',', '.') }} ₫</p>
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-person"></i> {{ $item->nguoiDung->HoTen ?? '' }}
                                </p>
                            </div>

                            <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}"
                               class="btn btn-warning w-100 fw-semibold mt-3 rounded-pill shadow-sm">
                                Xem chi tiết
                            </a>
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
@endsection
