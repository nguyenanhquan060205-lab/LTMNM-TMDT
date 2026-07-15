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