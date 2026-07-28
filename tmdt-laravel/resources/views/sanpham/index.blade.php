@extends('layouts.app')

@section('title', 'Tất cả sản phẩm')

@section('content')
<div class="container mt-4 mb-5">
    
    <div class="row">
        <!-- SIDEBAR BỘ LỌC -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="pe-lg-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fa-solid fa-sliders me-2"></i> Bộ lọc
                    </h5>
                    @if(request()->hasAny(['maloai', 'min_price', 'max_price', 'rating', 'sort']))
                    <a href="{{ route('sanpham.index', ['q' => request('q')]) }}" class="text-danger small text-decoration-none fw-semibold">
                        <i class="fa-solid fa-rotate-left me-1"></i>Xóa lọc
                    </a>
                    @endif
                </div>

                <!-- CATEGORIES -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3" style="font-size: 0.95rem;">Danh mục</h6>
                    <div class="form-check mb-2 custom-checkbox">
                        <input class="form-check-input" type="radio" name="maloai" value="" id="catAll" {{ empty($maloai) ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label text-muted" for="catAll">Tất cả sản phẩm</label>
                    </div>
                    @if ($loai)
                        @foreach ($loai as $l)
                            @php
                                $isActive = ($maloai == $l->MaLoai);
                            @endphp
                            <div class="form-check mb-2 custom-checkbox">
                                <input class="form-check-input" type="radio" name="maloai" value="{{ $l->MaLoai }}" id="cat{{ $l->MaLoai }}" {{ $isActive ? 'checked' : '' }} onchange="applyFilters()">
                                <label class="form-check-label text-muted" for="cat{{ $l->MaLoai }}">{{ $l->TenLoai }}</label>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- PRICE RANGE -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3" style="font-size: 0.95rem;">Khoảng giá</h6>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="number" id="min_price" value="{{ request('min_price') }}" class="form-control form-control-sm border-0 bg-light" placeholder="Từ" style="border-radius: 8px;">
                        <span class="text-muted small">-</span>
                        <input type="number" id="max_price" value="{{ request('max_price') }}" class="form-control form-control-sm border-0 bg-light" placeholder="Đến" style="border-radius: 8px;">
                    </div>
                    <button class="btn btn-primary btn-sm w-100 rounded-pill" onclick="applyFilters()">Áp dụng</button>
                </div>

                <!-- RATING -->
                <div>
                    <h6 class="fw-bold mb-3" style="font-size: 0.95rem;">Đánh giá</h6>
                    <div class="form-check mb-2 custom-checkbox">
                        <input class="form-check-input" type="radio" name="rating" value="" id="ratingAll" {{ empty(request('rating')) ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label text-muted" for="ratingAll">
                            Tất cả
                        </label>
                    </div>
                    @for($i=4; $i>=1; $i--)
                    <div class="form-check mb-2 custom-checkbox">
                        <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}" {{ request('rating') == $i ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label text-muted d-flex align-items-center" for="rating{{ $i }}">
                            <div class="text-warning me-2" style="font-size: 0.85rem;">
                                @for($j=1; $j<=5; $j++)
                                    <i class="fa-solid fa-star {{ $j <= $i ? '' : 'text-light' }}"></i>
                                @endfor
                            </div>
                            <span class="small">trở lên</span>
                        </label>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-9 col-md-8">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 2rem;">Tất cả sản phẩm</h2>
                    <p class="text-muted mb-0">Khám phá hàng ngàn sản phẩm, công nghệ chất lượng cao</p>
                </div>
                
                <div class="d-flex align-items-center mt-3 mt-md-0 flex-wrap gap-3">
                    <span class="text-muted small">Hiển thị {{ $dsSanPham->count() }} trên {{ $dsSanPham->total() }} sản phẩm</span>
                    <div class="d-flex align-items-center">
                        <span class="text-muted small me-2 text-nowrap">Sắp xếp theo:</span>
                        <select id="sortSelect" class="form-select form-select-sm border-0 bg-light shadow-none" style="border-radius: 20px; min-width: 150px; cursor: pointer;" onchange="applyFilters()">
                            <option value="moi-nhat" {{ request('sort') == 'moi-nhat' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="ban-chay" {{ request('sort') == 'ban-chay' ? 'selected' : '' }}>Bán chạy</option>
                            <option value="danh-gia" {{ request('sort') == 'danh-gia' ? 'selected' : '' }}>Đánh giá cao</option>
                            <option value="gia-tang" {{ request('sort') == 'gia-tang' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="gia-giam" {{ request('sort') == 'gia-giam' ? 'selected' : '' }}>Giá giảm dần</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="row g-4">
                @foreach ($dsSanPham as $sp)
                    @php
                        $anhObj = $sp->hinhAnhs ? collect($sp->hinhAnhs)->firstWhere('AnhBia', true) : null;
                        $anh = $anhObj ? $anhObj->URLAnh : ($sp->AnhBia ?? "noimage.jpg");
                        $danhGia = $sp->DanhGiaTB ?? 0;
                    @endphp

                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card product-card h-100 shadow-sm border-0 position-relative">
                            
                            <div class="position-relative rounded-top bg-white" style="border-bottom: 1px solid #f8f9fa;">
                                <div class="ratio ratio-1x1 overflow-hidden">
                                    <img src="{{ str_starts_with($anh, 'http') ? $anh : asset('Content/Images/' . $anh) }}" alt="{{ $sp->TenSP }}" class="object-fit-contain w-100 h-100 p-3" />
                                </div>
                                
                                @if($danhGia > 0)
                                <!-- Rating Badge on Image -->
                                <div class="position-absolute bottom-0 end-0 m-2 z-3 bg-white rounded-pill px-2 py-1 shadow-sm d-flex align-items-center">
                                    <i class="fa-solid fa-star text-warning me-1" style="font-size: 10px;"></i>
                                    <span class="fw-bold" style="font-size: 11px;">{{ number_format($danhGia, 1) }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <small class="text-primary text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                    {{ collect($loai)->firstWhere('MaLoai', $sp->MaLoai)->TenLoai ?? 'Khác' }}
                                </small>
                                
                                <h6 class="product-title line-clamp-2 mb-3" style="min-height: 2.4rem;">
                                    {{ $sp->TenSP }}
                                </h6>

                                <div class="mt-auto d-flex align-items-end justify-content-between">
                                    <div class="product-price text-primary">
                                        {{ number_format($sp->Gia, 0, ',', '.') }}₫
                                    </div>
                                    <a class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center"
                                       style="width: 40px; height: 40px; transition: transform 0.2s;"
                                       onmouseover="this.style.transform='scale(1.1)'"
                                       onmouseout="this.style.transform='scale(1)'"
                                       href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}">
                                        <i class="fa-solid fa-cart-shopping fs-6"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $dsSanPham->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    let url = new URL('{{ route("sanpham.index") }}', window.location.origin);
    
    let q = '{{ request("q") }}';
    if(q) url.searchParams.set('q', q);

    let selectedCat = document.querySelector('input[name="maloai"]:checked');
    if (selectedCat && selectedCat.value) {
        url.searchParams.set('maloai', selectedCat.value);
    }
    
    let minPrice = document.getElementById('min_price').value;
    let maxPrice = document.getElementById('max_price').value;
    if (minPrice) url.searchParams.set('min_price', minPrice);
    if (maxPrice) url.searchParams.set('max_price', maxPrice);

    let selectedRating = document.querySelector('input[name="rating"]:checked');
    if (selectedRating && selectedRating.value) {
        url.searchParams.set('rating', selectedRating.value);
    }

    let sort = document.getElementById('sortSelect').value;
    if (sort && sort !== 'moi-nhat') {
        url.searchParams.set('sort', sort);
    }

    window.location.href = url.toString();
}
</script>

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Custom Checkbox / Radio */
    .custom-checkbox .form-check-input {
        border-radius: 4px;
        border-color: #cbd5e1;
        cursor: pointer;
    }
    .custom-checkbox .form-check-input[type="radio"] {
        border-radius: 50%;
    }
    .custom-checkbox .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .custom-checkbox .form-check-label {
        cursor: pointer;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .custom-checkbox:hover .form-check-label {
        color: #0d6efd !important;
    }

    /* Product Card */
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
    }
    .product-card .ratio img {
        transition: transform 0.6s ease;
    }
    .product-card:hover .ratio img {
        transform: scale(1.08);
    }
    .product-title {
        color: #1e293b;
        font-weight: 700;
        font-size: 1.05rem;
        transition: color 0.2s;
    }
    .product-card:hover .product-title {
        color: #0d6efd;
    }
    .product-price {
        font-weight: 900;
        font-size: 1.4rem;
        line-height: 1.1;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection
