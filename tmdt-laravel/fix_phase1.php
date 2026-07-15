<?php

$indexView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <h2 class="fw-bold text-center mb-4 text-dark">
        🛍️ Sản phẩm mới nhất
    </h2>

    <div class="d-flex justify-content-center mb-4 flex-wrap gap-2">
        <a class="btn filter-btn {{ !request('maloai') ? 'active' : '' }}"
           href="{{ route('sanpham.index', ['q' => request('q')]) }}">
            Tất cả
        </a>

        @if (isset($loai))
            @foreach ($loai as $l)
                <a class="btn filter-btn {{ request('maloai') == $l->MaLoai ? 'active' : '' }}"
                   href="{{ route('sanpham.index', ['maloai' => $l->MaLoai, 'q' => request('q')]) }}">
                    {{ $l->TenLoai }}
                </a>
            @endforeach
        @endif
    </div>

    <div class="row g-4">
        @foreach ($dsSanPham as $sp)
            @php
                $anh = $sp->hinhAnhs->where('AnhBia', true)->first();
                $anhUrl = $anh ? $anh->URLAnh : 'noimage.jpg';
            @endphp

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card card-product h-100 border-0 shadow-sm">
                    <div class="product-img">
                        <img src="{{ asset('Content/Images/' . $anhUrl) }}" alt="{{ $sp->TenSP }}" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-semibold text-truncate mb-1">{{ $sp->TenSP }}</h6>

                        <p class="text-danger fw-bold mb-1">
                            {{ number_format($sp->Gia, 0, ',', '.') }}₫
                        </p>

                        <small class="text-muted mb-3">
                            {{ $sp->loaiSanPham->TenLoai ?? '' }}
                        </small>

                        <a class="btn btn-warning w-100 mt-auto fw-semibold"
                           href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $dsSanPham->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
    body { background-color: #f7f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .filter-btn { border-radius: 50px; border: 1px solid #ffc107; background: #fff; color: #444; padding: 6px 18px; font-weight: 500; transition: .2s; }
    .filter-btn:hover { background: #ffec99; color: #000; }
    .filter-btn.active { background: #ffc107; color: #000; box-shadow: 0 0 0 2px rgba(255, 193, 7, .35); }
    .card-product { border-radius: 12px; overflow: hidden; transition: .25s ease; background: #fff; }
    .card-product:hover { transform: translateY(-6px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); }
    .product-img { width: 100%; height: 210px; background: #fff; display: flex; justify-content: center; align-items: center; overflow: hidden; border-bottom: 1px solid #eee; }
    .product-img img { max-width: 100%; max-height: 100%; object-fit: contain; object-position: center; }
    .card-product .card-body { padding: 14px; }
</style>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/index.blade.php', $indexView);

$chitietView = <<<'EOD'
@extends('shared._layout')
@section('content')
@php
    $anh = $sanPham->hinhAnhs->where('AnhBia', true)->first();
    $anhUrl = $anh ? $anh->URLAnh : 'noimage.jpg';
    $user = session('user');
@endphp

<div class="container mt-4 mb-5">
    <!-- Nút Quay lại -->
    <a href="{{ route('sanpham.index') }}" class="btn btn-outline-secondary mb-4 rounded-pill px-4 shadow-sm">
        <i class="fa fa-arrow-left me-2"></i>Tiếp tục mua sắm
    </a>

    <!-- Flash message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row bg-white p-4 rounded-4 shadow-sm border">
        <!-- ẢNH SẢN PHẨM -->
        <div class="col-md-5 mb-4 mb-md-0 d-flex flex-column align-items-center">
            <div class="border rounded-3 p-2 mb-3 shadow-sm d-flex justify-content-center align-items-center bg-light w-100" style="height: 400px; overflow: hidden;">
                <img id="main-image" src="{{ asset('Content/Images/' . $anhUrl) }}" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
            </div>

            @if($sanPham->hinhAnhs->count() > 1)
                <div class="d-flex gap-2 justify-content-center flex-wrap w-100">
                    @foreach($sanPham->hinhAnhs as $ha)
                        <img src="{{ asset('Content/Images/' . $ha->URLAnh) }}"
                             class="thumbnail-img rounded border shadow-sm"
                             style="width: 70px; height: 70px; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                             onmouseover="document.getElementById('main-image').src=this.src; this.style.transform='scale(1.1)';"
                             onmouseout="this.style.transform='scale(1)';"
                             onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                    @endforeach
                </div>
            @endif
        </div>

        <!-- THÔNG TIN SẢN PHẨM -->
        <div class="col-md-7 d-flex flex-column">
            <h2 class="fw-bold text-dark mb-2">{{ $sanPham->TenSP }}</h2>
            <p class="text-muted mb-3"><i class="fa fa-tags me-2"></i>{{ $sanPham->loaiSanPham->TenLoai ?? '' }}</p>
            <h3 class="text-danger fw-bold mb-4">{{ number_format($sanPham->Gia, 0, ',', '.') }}₫</h3>

            <div class="bg-light p-3 rounded-3 mb-4 shadow-sm border">
                <p class="mb-2"><i class="fa fa-box me-2 text-secondary"></i><strong>Tình trạng:</strong> {{ $sanPham->TinhTrang }}</p>
                <p class="mb-2"><i class="fa fa-cubes me-2 text-secondary"></i><strong>Số lượng còn:</strong> <span class="badge bg-warning text-dark">{{ $sanPham->SoLuong }}</span></p>
                <p class="mb-0"><i class="fa fa-calendar-alt me-2 text-secondary"></i><strong>Ngày đăng:</strong> {{ \Carbon\Carbon::parse($sanPham->NgayDang)->format('d/m/Y') }}</p>
            </div>

            <!-- CHỦ SỞ HỮU -->
            <div class="border p-3 rounded-3 mb-4 d-flex align-items-center justify-content-between shadow-sm bg-white">
                <div class="d-flex align-items-center">
                    @php
                        $avatar = $sanPham->nguoiDung && !empty($sanPham->nguoiDung->AnhDaiDien) ? asset('Content/Avatars/' . $sanPham->nguoiDung->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                    @endphp
                    <img src="{{ $avatar }}" class="rounded-circle me-3 border shadow-sm" width="55" height="55" style="object-fit: cover;" onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';" />
                    <div>
                        <p class="mb-0 text-muted small">Người bán</p>
                        <strong class="fs-5 text-dark">{{ $sanPham->nguoiDung->HoTen ?? 'Ẩn danh' }}</strong>
                    </div>
                </div>
                <div>
                    <a href="{{ route('sanpham.thongtinnguoiban', ['id' => $sanPham->MaKH]) }}" class="btn btn-outline-info btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-eye me-1"></i>Xem Shop
                    </a>
                </div>
            </div>

            <div class="mt-auto d-flex gap-2">
                @if ($sanPham->SoLuong > 0)
                    @if ($user && $user->MaKH == $sanPham->MaKH)
                        <div class="alert alert-warning w-100 mb-0"><i class="fa fa-info-circle me-2"></i>Đây là sản phẩm của bạn.</div>
                    @else
                        <a href="{{ route('giohang.them', ['id' => $sanPham->MaSP]) }}" class="btn btn-warning btn-lg flex-grow-1 fw-bold rounded-pill shadow-sm">
                            <i class="fa fa-cart-plus me-2"></i>Thêm vào giỏ
                        </a>
                        <a href="{{ route('giohang.them', ['id' => $sanPham->MaSP]) }}?buy=1" class="btn btn-danger btn-lg flex-grow-1 fw-bold rounded-pill shadow-sm">
                            Mua ngay
                        </a>
                    @endif
                @else
                    <button class="btn btn-secondary btn-lg w-100 rounded-pill shadow-sm" disabled>
                        <i class="fa fa-ban me-2"></i>Hết hàng
                    </button>
                @endif
            </div>
            
            <div class="mt-3 text-end">
                <a href="{{ route('khieunai.taokhieunai', ['id' => $sanPham->MaSP]) }}" class="text-danger small text-decoration-none">
                    <i class="fa fa-flag me-1"></i>Báo cáo vi phạm
                </a>
            </div>
        </div>
    </div>

    <!-- MÔ TẢ & ĐÁNH GIÁ -->
    <div class="row mt-5">
        <div class="col-md-8">
            <h4 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fa-solid fa-file-lines me-2"></i>Mô tả sản phẩm</h4>
            <div class="bg-white p-4 rounded-4 shadow-sm border mb-5 lh-lg" style="white-space: pre-line; font-size: 15px;">
                {{ $sanPham->MoTa }}
            </div>

            <h4 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fa-solid fa-star text-warning me-2"></i>Đánh giá ({{ $danhGias->count() }})</h4>
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                @if ($danhGias->isEmpty())
                    <p class="text-muted text-center my-3"><i class="fa fa-inbox fa-2x mb-2 text-light"></i><br>Chưa có đánh giá nào.</p>
                @else
                    @foreach ($danhGias as $dg)
                        <div class="d-flex mb-4 border-bottom pb-3">
                            @php
                                $dgAvatar = $dg->nguoiDung && !empty($dg->nguoiDung->AnhDaiDien) ? asset('Content/Avatars/' . $dg->nguoiDung->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                            @endphp
                            <img src="{{ $dgAvatar }}" class="rounded-circle me-3 border" width="45" height="45" style="object-fit: cover;" onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';" />
                            <div>
                                <h6 class="fw-bold mb-1">{{ $dg->nguoiDung->HoTen ?? 'Ẩn danh' }}</h6>
                                <div class="text-warning small mb-2">
                                    @for ($i = 0; $i < $dg->Diem; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                    @for ($i = $dg->Diem; $i < 5; $i++)
                                        <i class="fa-regular fa-star"></i>
                                    @endfor
                                    <span class="text-muted ms-2">{{ \Carbon\Carbon::parse($dg->NgayDG)->format('d/m/Y') }}</span>
                                </div>
                                <p class="mb-0 text-dark">{{ $dg->NhanXet }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="fa-solid fa-layer-group me-2"></i>Cùng danh mục</h4>
            <div class="d-flex flex-column gap-3">
                @foreach ($sanPhamCungLoai as $spcl)
                    @if ($spcl->MaSP != $sanPham->MaSP)
                        @php
                            $spclAnh = $spcl->hinhAnhs->where('AnhBia', true)->first();
                            $spclAnhUrl = $spclAnh ? $spclAnh->URLAnh : 'noimage.jpg';
                        @endphp
                        <a href="{{ route('sanpham.chitiet', ['id' => $spcl->MaSP]) }}" class="text-decoration-none text-dark">
                            <div class="d-flex bg-white rounded-3 p-2 shadow-sm border align-items-center transition-hover">
                                <img src="{{ asset('Content/Images/' . $spclAnhUrl) }}" class="rounded me-3" width="70" height="70" style="object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                <div>
                                    <h6 class="mb-1 text-truncate" style="max-width: 200px;">{{ $spcl->TenSP }}</h6>
                                    <strong class="text-danger">{{ number_format($spcl->Gia, 0, ',', '.') }}₫</strong>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
                @if ($sanPhamCungLoai->count() <= 1)
                     <p class="text-muted small">Không có sản phẩm nào khác.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/chitiet.blade.php', $chitietView);

$thongtinNguoiBanView = <<<'EOD'
@extends('shared._layout')
@section('content')
@php
    $avatar = $nguoiBan && !empty($nguoiBan->AnhDaiDien) ? asset('Content/Avatars/' . $nguoiBan->AnhDaiDien) : asset('Content/Avatars/default.jpg');
    $user = session('user');
@endphp

<div class="container mt-4 mb-5">
    <!-- Nút Quay lại -->
    <button onclick="history.back()" class="btn btn-outline-secondary mb-4 rounded-pill px-4 shadow-sm">
        <i class="fa fa-arrow-left me-2"></i>Quay lại
    </button>

    <div class="row">
        <!-- Cột trái: Thông tin cá nhân -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4 text-center overflow-hidden">
                <div class="bg-warning" style="height: 100px;"></div>
                <div class="card-body position-relative pt-0">
                    <img src="{{ $avatar }}"
                         class="rounded-circle border border-4 border-white shadow bg-white"
                         style="width: 120px; height: 120px; object-fit: cover; margin-top: -60px; z-index: 2; position: relative;" 
                         onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';">
                    
                    <h3 class="fw-bold mt-3 mb-1 text-dark">{{ $nguoiBan->HoTen ?? 'Người dùng Ẩn danh' }}</h3>
                    <p class="text-muted mb-3"><i class="fa fa-clock me-1"></i>Tham gia: {{ \Carbon\Carbon::parse($nguoiBan->NgayTao)->format('d/m/Y') }}</p>
                    
                    <div class="d-flex justify-content-center gap-4 mb-4 text-dark">
                        <div>
                            <h4 class="fw-bold mb-0 text-warning">{{ $tongSanPham }}</h4>
                            <small class="text-muted">Sản phẩm</small>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-success">{{ $tongSanPhamDaBan }}</h4>
                            <small class="text-muted">Đã bán</small>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-primary">{{ $diemDanhGiaTrungBinh }}/5</h4>
                            <small class="text-muted">Đánh giá</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start px-2">
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-2 text-danger"></i><strong>Khu vực:</strong> {{ $nguoiBan->DiaChi ?? 'Chưa cập nhật' }}</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-2 text-success"></i><strong>Số ĐT:</strong> {{ $nguoiBan->SDT ?? 'Chưa cập nhật' }}</p>
                        <p class="mb-0"><i class="fa fa-envelope me-2 text-primary"></i><strong>Email:</strong> {{ $nguoiBan->Email ?? 'Chưa cập nhật' }}</p>
                    </div>

                    @if ($user && $user->MaKH != $nguoiBan->MaKH)
                        <div class="mt-4 d-flex justify-content-center">
                            <a href="{{ route('tinnhan.index', ['userId' => $nguoiBan->MaKH]) }}" class="btn btn-warning rounded-pill px-4 shadow-sm w-100">
                                <i class="fa-regular fa-comment-dots me-2"></i>Nhắn tin ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Cột phải: Các sản phẩm đang bán -->
        <div class="col-md-8">
            <h4 class="fw-bold border-bottom pb-2 mb-4 text-dark"><i class="fa-solid fa-shop me-2"></i>Sản phẩm đang bán</h4>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($sanPham as $sp)
                    @php
                        $anh = $sp->hinhAnhs->where('AnhBia', true)->first();
                        $anhUrl = $anh ? $anh->URLAnh : 'noimage.jpg';
                    @endphp
                    <div class="col">
                        <div class="card card-product h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="product-img bg-light d-flex justify-content-center align-items-center" style="height: 180px; overflow: hidden;">
                                <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}">
                                    <img src="{{ asset('Content/Images/' . $anhUrl) }}" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                </a>
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="fw-semibold text-truncate mb-2">
                                    <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="text-decoration-none text-dark">{{ $sp->TenSP }}</a>
                                </h6>
                                <strong class="text-danger mb-2">{{ number_format($sp->Gia, 0, ',', '.') }}₫</strong>
                                <small class="text-muted mt-auto"><i class="fa-solid fa-box me-1"></i>Còn lại: {{ $sp->SoLuong }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($sanPham->isEmpty())
                <div class="text-center p-5 bg-white shadow-sm rounded-4 border">
                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Người dùng này chưa có sản phẩm nào đang bán.</h5>
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-center">
                {{ $sanPham->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<style>
    .card-product { transition: transform 0.25s ease, box-shadow 0.25s; }
    .card-product:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,.1); }
</style>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/thongtinnguoiban.blade.php', $thongtinNguoiBanView);
echo "Phase 1: Sản phẩm views updated!\n";
