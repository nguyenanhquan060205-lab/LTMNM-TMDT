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