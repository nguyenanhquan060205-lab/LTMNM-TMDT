@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- 🎨 HERO SECTION -->
<section class="hero-section text-center text-white py-5">
    <div class="container" style="position: relative; z-index: 10;">
        <h1 class="display-5 fw-bold mb-3">TechSecond - Chợ Đồ Cũ</h1>
        <p class="lead mb-4">Mua bán đồ cũ nhanh chóng, an toàn và uy tín</p>

        <a href="{{ route('sanpham.taomoi') }}" class="btn btn-warning btn-lg rounded-pill fw-semibold shadow-lg">
            <i class="fa fa-upload me-2"></i> Đăng tin ngay
        </a>

        <div class="row mt-5 text-center">
            <div class="col-md-4">
                <h3 class="text-warning fw-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{{ $tongSP ?? 0 }} +</h3>
                <p style="color: rgba(255,255,255,0.95);">Sản phẩm</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-warning fw-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{{ $tongUser ?? 0 }} +</h3>
                <p style="color: rgba(255,255,255,0.95);">Người dùng</p>
            </div>
            <div class="col-md-4">
                <h3 class="text-warning fw-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{{ $tyLeThanhCong ?? '99%' }}</h3>
                <p style="color: rgba(255,255,255,0.95);">Giao dịch thành công</p>
            </div>
        </div>
    </div>
</section>

<!-- 🎯 DANH MỤC NỔI BẬT -->
<section class="category-section">
    <div class="container text-center my-5">
        <h2 class="fw-bold mb-4">Danh mục nổi bật</h2>
        <div class="row g-4">

            <div class="col-md-3 col-6">
                <a href="{{ route('sanpham.index', ['maloai' => 1]) }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="fs-1 mb-2">📱</div>
                        <h5>Điện thoại</h5>
                        <p class="text-muted small mb-0">Smartphone cũ giá rẻ</p>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-6">
                <a href="{{ route('sanpham.index', ['maloai' => 2]) }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="fs-1 mb-2">💻</div>
                        <h5>Laptop</h5>
                        <p class="text-muted small mb-0">Phục vụ học tập và làm việc</p>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-6">
                <a href="{{ route('sanpham.index', ['maloai' => 4]) }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="fs-1 mb-2">🏠</div>
                        <h5>Đồ gia dụng</h5>
                        <p class="text-muted small mb-0">Dùng tốt - Giá hợp lý</p>
                    </div>
                </a>
            </div>

            <div class="col-md-3 col-6">
                <a href="{{ route('sanpham.index', ['maloai' => 6]) }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="fs-1 mb-2">🧩</div>
                        <h5>Khác</h5>
                        <p class="text-muted small mb-0">Nhiều sản phẩm đa dạng</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- 🛍️ SẢN PHẨM MỚI NHẤT -->
<section class="product-section">
    <div class="container my-5">
        <h2 class="fw-bold text-center mb-4">Sản phẩm mới nhất</h2>
        <div class="row g-4 justify-content-center">

            @foreach ($sanPhamMoi as $item)
                @php
                    $anhObj = collect($item->hinhAnhs)->firstWhere('AnhBia', true);
                    $anh = $anhObj ? $anhObj->URLAnh : ($item->AnhBia ?? "noimage.jpg");
                @endphp

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm product-card h-100">

                        <div class="ratio ratio-1x1 bg-light rounded-top overflow-hidden">
                            <img src="{{ asset('Content/Images/' . $anh) }}"
                                 class="card-img-top p-3"
                                 style="object-fit: contain;"
                                 alt="{{ $item->TenSP }}" />
                        </div>

                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-semibold text-truncate">{{ $item->TenSP }}</h6>
                                <p class="text-danger fw-bold mb-1">{{ number_format($item->Gia, 0, ',', '.') }} ₫</p>

                                <p class="small text-muted">
                                    {{ (strlen(strip_tags($item->MoTa)) > 45) ? substr(strip_tags($item->MoTa), 0, 45) . "..." : strip_tags($item->MoTa) }}
                                </p>
                            </div>

                            <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}"
                               class="btn btn-primary btn-sm w-100 rounded-pill fw-semibold shadow-sm">
                                <i class="fa fa-eye me-1"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="text-center mt-5">
            <a href="{{ route('sanpham.index') }}" class="btn-see-more">
                Khám phá thêm <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endsection
