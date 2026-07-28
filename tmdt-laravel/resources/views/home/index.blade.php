@extends('shared._layout')

@section('title', 'Trang chủ')

@section('content')
<style>
    /* Base Color Overrides */
    .text-primary-custom { color: #0d6efd !important; }
    .bg-primary-custom { background-color: #0d6efd !important; color: #fff; }
    .btn-primary-custom { background-color: #0d6efd; color: white; border: none; }
    .btn-primary-custom:hover { background-color: #0b5ed7; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13,110,253,0.3); }

    /* Hero Carousel */
    .hero-carousel {
        position: relative;
        overflow: hidden;
        /* No margin-top, no border radius -> edge to edge */
    }
    .carousel-item {
        height: 65vh; /* Bigger height for more impact */
        min-height: 500px;
        background: #000;
    }
    .carousel-item img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        opacity: 0.6; /* Darken for text readability */
    }
    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes zoomIn {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }

    .carousel-caption {
        top: 50%;
        bottom: auto;
        transform: translateY(-50%);
        text-align: left;
        z-index: 10;
        left: 0;
        right: 0;
        padding: 0;
        position: absolute;
        pointer-events: none; /* Let clicks pass through to background if needed, except buttons */
    }
    .carousel-caption .container {
        padding-left: 20px;
        padding-right: 20px;
        pointer-events: auto; /* Re-enable clicks for buttons inside */
    }
    .carousel-caption h1 {
        font-weight: 900;
        font-size: 4.5rem;
        letter-spacing: -1.5px;
        text-transform: capitalize;
        animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }
    .carousel-caption p {
        font-size: 1.1rem;
        max-width: 600px;
        line-height: 1.6;
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        animation-delay: 0.4s;
    }
    .carousel-caption .d-flex {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        animation-delay: 0.6s;
    }
    @media (max-width: 768px) {
        .carousel-caption h1 {
            font-size: 2.5rem;
        }
    }
    .stats-bar {
        background: white;
        border-radius: 20px;
        padding: 35px 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        margin-top: 40px; /* Space it out below banner instead of overlapping */
        position: relative;
        z-index: 20;
    }
    .stat-item h3 {
        color: #0d6efd;
        font-weight: 800;
        margin-bottom: 5px;
    }
    .stat-item p {
        color: #6c757d;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    /* Categories */
    .category-card {
        background: white;
        border-radius: 16px;
        padding: 25px 20px;
        text-align: center;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .category-card:hover {
        background: #0d6efd;
        color: white !important;
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(13,110,253,0.2);
        border-color: #0d6efd;
    }
    .category-card .icon-wrapper {
        font-size: 2.5rem;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }
    .category-card:hover .icon-wrapper {
        transform: scale(1.1);
    }
    .category-card h5 {
        font-weight: 700;
        margin-bottom: 8px;
        color: inherit; /* inherit from parent to become white on hover */
    }
    .category-card p {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
        transition: color 0.3s ease;
    }
    .category-card:hover p {
        color: rgba(255,255,255,0.8);
    }

    /* Product Cards */
    .product-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #edf2f7;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
    }
    .product-card img {
        transition: transform 0.5s ease;
    }
    .product-card:hover img {
        transform: scale(1.05);
    }
    .product-title {
        color: #212529;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 10px;
        transition: color 0.2s;
    }
    .product-card:hover .product-title {
        color: #0d6efd;
    }
    .product-price {
        color: #111;
        font-weight: 800;
        font-size: 1.25rem;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>

<!-- CAROUSEL BANNER (FULL WIDTH) -->
<div id="heroCarousel" class="carousel slide hero-carousel carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?q=80&w=2000&auto=format&fit=crop" class="d-block w-100" alt="Tech">
        </div>
        
        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2000&auto=format&fit=crop" class="d-block w-100" alt="Setup">
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=2000&auto=format&fit=crop" class="d-block w-100" alt="Laptop">
        </div>
    </div>
    
    <!-- STATIC CAPTION -->
    <div class="carousel-caption">
        <div class="container">
            <h1 class="text-white mb-3">Đồ Cũ Công Nghệ<br><span style="color: #4facfe;">Giá Sinh Viên</span></h1>
            <p class="text-white mb-4 fw-medium">Nền tảng giao dịch thiết bị điện tử cũ uy tín. Tìm kiếm món hời hoặc thanh lý đồ cũ của bạn một cách dễ dàng và an toàn.</p>
            <div class="d-flex gap-3">
                <a href="{{ url('/sanpham') }}" class="btn btn-primary-custom btn-lg rounded-pill px-4 fw-bold">Săn đồ ngay <i class="fa fa-arrow-right ms-2"></i></a>
                <a href="{{ url('/sanpham/taomoi') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold text-dark">Đăng bán miễn phí</a>
            </div>
        </div>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="container mb-5">
    <!-- STATS BAR -->
    <div class="stats-bar mx-lg-4">
        <div class="row g-4 text-center">
            <div class="col-md-4 stat-item">
                <div class="d-flex flex-column align-items-center">
                    <div style="background: rgba(13,110,253,0.1); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <i class="fa fa-truck fs-3 text-primary-custom"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Giao Hàng Nhanh</h4>
                    <p class="text-muted small">Phạm vi toàn quốc</p>
                </div>
            </div>
            <div class="col-md-4 stat-item border-start border-end">
                <div class="d-flex flex-column align-items-center">
                    <div style="background: rgba(13,110,253,0.1); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <i class="fa fa-shield-halved fs-3 text-primary-custom"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Đảm Bảo An Toàn</h4>
                    <p class="text-muted small">Giao dịch uy tín 100%</p>
                </div>
            </div>
            <div class="col-md-4 stat-item">
                <div class="d-flex flex-column align-items-center">
                    <div style="background: rgba(13,110,253,0.1); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <i class="fa fa-clock fs-3 text-primary-custom"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Hỗ Trợ 24/7</h4>
                    <p class="text-muted small">Luôn sẵn sàng giải đáp</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DANH MỤC NỔI BẬT -->
<section class="py-5 bg-light mt-5" style="border-radius: 40px; margin-bottom: 2rem;">
    <div class="container text-center">
        <h2 class="fw-bold mb-5 text-dark">Khám Phá Danh Mục</h2>
        <div class="row justify-content-center g-4">

            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ url('/sanpham/index?maloai=1') }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="icon-wrapper">📱</div>
                        <h5>Điện thoại</h5>
                        <p>Smartphone giá tốt</p>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ url('/sanpham/index?maloai=2') }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="icon-wrapper">💻</div>
                        <h5>Laptop</h5>
                        <p>Phục vụ học tập, làm việc</p>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ url('/sanpham/index?maloai=4') }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="icon-wrapper">🎧</div>
                        <h5>Phụ kiện</h5>
                        <p>Tai nghe, loa, cáp sạc</p>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ url('/sanpham/index?maloai=6') }}" class="text-decoration-none text-dark">
                    <div class="category-card">
                        <div class="icon-wrapper">🧩</div>
                        <h5>Sản phẩm khác</h5>
                        <p>Nhiều món đồ độc đáo</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- SẢN PHẨM MỚI NHẤT -->
<section class="py-5 my-4">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-primary-custom fw-bold text-uppercase" style="letter-spacing: 2px; font-size: 0.85rem;">Xu Hướng Mới</span>
                <h2 class="fw-bold text-dark mt-2 mb-0">Sản phẩm mới lên kệ</h2>
            </div>
            <a href="{{ url('/sanpham') }}" class="text-decoration-none fw-bold text-primary-custom mt-3 mt-md-0">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        
        <div class="row g-4 justify-content-center">

            @foreach ($sanPhamMoi as $item)
                @php
                    $anhObj = collect($item->hinhAnhs ?? $item->hinhAnhSPs)->firstWhere('AnhBia', true);
                    $anh = $anhObj ? $anhObj->URLAnh : ($item->AnhBia ?? "noimage.jpg");
                @endphp

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100 shadow-sm border-0">

                        <div class="ratio ratio-1x1 bg-white overflow-hidden p-3" style="border-bottom: 1px solid #f8f9fa;">
                            <img src="{{ str_starts_with($anh, 'http') ? $anh : url('/Content/Images/' . $anh) }}"
                                 class="card-img-top w-100 h-100"
                                 style="object-fit: contain;"
                                 alt="{{ $item->TenSP }}" />
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <a href="{{ url('/sanpham/chitiet/' . $item->MaSP) }}" class="text-decoration-none">
                                    <h5 class="product-title text-truncate" title="{{ $item->TenSP }}">{{ $item->TenSP }}</h5>
                                </a>
                                
                                <p class="small text-muted mb-3 line-clamp-2" style="height: 40px;">
                                    {{ strip_tags($item->MoTa) }}
                                </p>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                <span class="product-price">{{ number_format($item->Gia, 0, ',', '.') }} ₫</span>
                                <a href="{{ url('/sanpham/chitiet/' . $item->MaSP) }}"
                                   class="btn btn-sm btn-primary-custom rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
@endsection
