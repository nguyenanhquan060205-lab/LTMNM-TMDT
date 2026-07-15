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