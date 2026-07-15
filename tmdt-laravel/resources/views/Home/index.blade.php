@extends('shared._layout')
@section('content')
<div class="hero-section text-center position-relative mb-5" style="background: linear-gradient(135deg, #2d3436, #000000); color: white; padding: 100px 20px; overflow: hidden;">
    <!-- Nền Particles (trang trí) -->
    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-25" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="container position-relative" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 text-warning">TechSecond</h1>
        <p class="lead mb-4 fw-light text-light" style="max-width: 600px; margin: 0 auto;">Nền tảng thương mại điện tử C2C an toàn, kết nối người mua & người bán đồ công nghệ cũ nhanh chóng.</p>
        <a href="{{ route('sanpham.index') }}" class="btn btn-warning btn-lg px-5 py-3 shadow-sm" style="border-radius: 30px; font-weight: 600;">
            Khám phá ngay <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<div class="container my-5">
    @if(session('ThongBao'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('ThongBao') }}
        </div>
    @endif

    <!-- THỐNG KÊ -->
    <div class="row text-center mb-5 gx-4 gy-4">
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-users fs-1 text-primary mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $tongUser ?? 0 }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Người dùng</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-box-open fs-1 text-success mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $tongSP ?? 0 }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Sản phẩm</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 bg-white rounded-4 shadow-sm border-0 d-flex flex-column align-items-center justify-content-center h-100 stat-card">
                <i class="fa-solid fa-handshake fs-1 text-warning mb-3"></i>
                <h3 class="fw-bold text-dark mb-1">{{ $tyLeThanhCong ?? '0%' }}</h3>
                <p class="text-muted mb-0 small text-uppercase">Tỷ lệ thành công</p>
            </div>
        </div>
    </div>

    <!-- SẢN PHẨM MỚI -->
    <h2 class="fw-bold mb-4 text-dark border-start border-5 border-warning ps-3">Sản Phẩm Mới Đăng</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
        @foreach($sanPhamMoi as $item)
            @php
                $bia = $item->AnhBia ? asset('Content/Images/' . $item->AnhBia) : asset('Content/Images/noimage.jpg');
            @endphp
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <!-- Ảnh sản phẩm -->
                    <div class="position-relative overflow-hidden" style="border-radius: 10px 10px 0 0;">
                        <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                            <img src="{{ $bia }}" class="card-img-top object-fit-cover" alt="{{ $item->TenSP }}" style="height: 250px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';">
                        </a>
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 px-3 py-2 rounded-pill shadow-sm">Mới</span>
                    </div>

                    <!-- Thông tin -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-truncate fw-bold mb-2">
                            <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}" class="text-decoration-none text-dark">{{ $item->TenSP }}</a>
                        </h5>
                        <h4 class="text-danger fw-bold mb-3">{{ number_format($item->Gia, 0, ',', '.') }} đ</h4>
                        
                        <div class="mt-auto d-flex align-items-center justify-content-between text-muted small">
                            <span><i class="fa-solid fa-box me-1"></i>Còn: {{ $item->SoLuong }}</span>
                            <span><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($item->NgayDang)->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-3 pt-0">
                        <a href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}" class="btn btn-outline-dark w-100" style="border-radius: 8px;">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('sanpham.index') }}" class="btn btn-outline-warning btn-lg px-5 py-2 rounded-pill text-dark" style="font-weight: 500;">
            Xem tất cả sản phẩm <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>
@endsection