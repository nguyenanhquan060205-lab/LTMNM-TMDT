@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@php
    $hd = $HoaDon;
    $DonHuy = $hd->TrangThai != null && (strcasecmp(trim($hd->TrangThai), "Đã huỷ") === 0 || strcasecmp(trim($hd->TrangThai), "Đã hủy") === 0);
@endphp

@section('content')
<style>
    body { background-color: #f4f6f9; }
    
    .page-title {
        font-weight: 700;
        color: #2a2a40;
        font-size: 1.5rem;
    }
    .order-id { color: #667eea; }
    
    /* Card Component */
    .info-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: none;
        height: 100%;
        transition: transform 0.2s;
    }
    .info-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.06); }
    .card-header-custom {
        background: transparent;
        border-bottom: 1px solid #edf2f7;
        padding: 1rem 1.25rem;
        font-weight: 700;
        color: #4a5568;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-body-custom { padding: 1.5rem; }

    /* Product Table */
    .table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .table-custom th {
        background: #1e293b;
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border: none;
    }
    .table-custom td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
    }
    
    /* Image */
    .product-img {
        width: 65px;
        height: 65px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #edf2f7;
        padding: 2px;
        background: #fff;
    }

    /* Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 0.85rem;
        white-space: nowrap;
        display: inline-block;
    }
    .badge-success-custom { background-color: #0d6efd; color: white; }
    .badge-pending-custom { background-color: white; color: #1e293b; border: 1px solid #1e293b; }
    .badge-danger-custom { background-color: #1e293b; color: white; }
    
    /* Text Helpers */
    .text-label {
        font-size: 0.85rem;
        color: #a0aec0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .text-value {
        font-size: 1rem;
        color: #2d3748;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    /* Total Box */
    .total-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #edf2f7;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        color: #718096;
    }
    .total-row.final {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0d6efd;
        border-top: 2px dashed #cbd5e0;
        padding-top: 12px;
        margin-top: 12px;
        margin-bottom: 0;
    }
</style>

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">
            Chi tiết đơn hàng <span class="order-id">#{{ $hd->MaHD }}</span>
        </h2>
        <a href="{{ route('taikhoan.lichsu') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i> Trở về
        </a>
    </div>

    <!-- Grid Info -->
    <div class="row g-4 mb-4">
        <!-- Cột 1: Trạng thái & Lịch trình -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fa-solid fa-box-open text-primary"></i> Thông tin đơn hàng
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-label">Ngày đặt hàng</div>
                            <div class="text-value">
                                <i class="fa-regular fa-clock text-muted me-1"></i> 
                                {{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('H:i - d/m/Y') : '-' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-label">Ngày thanh toán</div>
                            <div class="text-value">
                                <i class="fa-solid fa-check-double text-success me-1"></i>
                                {{ $hd->NgayTT ? \Carbon\Carbon::parse($hd->NgayTT)->format('H:i - d/m/Y') : 'Chưa thanh toán' }}
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="text-label">Trạng thái hiện tại</div>
                            <div>
                                @if ($DonHuy)
                                    <span class="badge badge-danger-custom badge-status"><i class="fa-solid fa-xmark me-1"></i> Đã huỷ</span>
                                @elseif ($hd->TrangThai == "Đã thanh toán")
                                    <span class="badge badge-success-custom badge-status"><i class="fa-solid fa-check me-1"></i> Thành công</span>
                                @else
                                    <span class="badge badge-pending-custom badge-status"><i class="fa-solid fa-spinner fa-spin me-1"></i> Đang chờ xử lý</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột 2: Giao hàng & Liên hệ -->
        <div class="col-lg-6">
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fa-solid fa-truck-fast text-info"></i> Thông tin giao nhận
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-label">Người nhận</div>
                            <div class="text-value fw-bold text-dark">
                                <i class="fa-regular fa-user text-muted me-1"></i>
                                {{ $hd->nguoiDung->HoTen ?? 'Khách hàng' }}
                                <span class="text-muted fw-normal mx-2">|</span> 
                                <i class="fa-solid fa-phone text-muted me-1"></i>
                                {{ $hd->nguoiDung->SDT ?? 'Chưa cập nhật SĐT' }}
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="text-label">Địa chỉ giao hàng</div>
                            <div class="text-value mb-0">
                                <i class="fa-solid fa-location-dot text-danger me-1"></i> 
                                {{ $hd->DiaChiGiaoHang ?? 'Không có địa chỉ' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Sản phẩm -->
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clipboard-list text-secondary me-2"></i> Danh sách sản phẩm</h5>
    <div class="table-container mb-4">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr class="text-center">
                        <th class="text-start" style="width: 45%;">Sản phẩm</th>
                        <th style="width: 15%;">Đơn giá</th>
                        <th style="width: 10%;">Số lượng</th>
                        <th style="width: 15%;">Thành tiền</th>
                        <th style="width: 15%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $tongTienSanPham = 0; @endphp
                    
                    @foreach ($chiTietVm as $item)
                        @php
                            $HuyCT = $item->TrangThaiCT != null && (strcasecmp(trim($item->TrangThaiCT), "Đã huỷ") === 0 || strcasecmp(trim($item->TrangThaiCT), "Đã hủy") === 0);
                            
                            // Lấy hình ảnh
                            $anhObj = collect($item->sanPham->hinhAnhs ?? $item->sanPham->hinhAnhSPs ?? [])->firstWhere('AnhBia', true);
                            if (!$anhObj) {
                                $anhObj = collect($item->sanPham->hinhAnhs ?? $item->sanPham->hinhAnhSPs ?? [])->first();
                            }
                            $anhUrl = $anhObj ? url('Content/Images/' . $anhObj->URLAnh) : url('content/images/no-image.jpg');
                            
                            $donGia = $item->sanPham->Gia ?? 0;
                            $thanhTien = $item->ThanhTien;
                            if (!$HuyCT && !$DonHuy) {
                                $tongTienSanPham += $thanhTien;
                            }
                        @endphp

                        <tr>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $anhUrl }}" class="product-img" alt="Sản phẩm">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">{{ $item->TenSP }}</h6>
                                        <span class="badge {{ ($DonHuy || $HuyCT) ? 'badge-danger-custom' : ($item->TrangThaiCT == 'Đã xác nhận' ? 'badge-success-custom' : 'badge-pending-custom') }} badge-status py-1 px-2" style="font-size: 0.75rem;">
                                            {{ ($DonHuy || $HuyCT) ? 'Đã huỷ' : ($item->TrangThaiCT == 'Đã xác nhận' ? 'Thành công' : 'Chờ xác nhận') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted fw-medium">{{ number_format($donGia, 0, ',', '.') }} ₫</td>
                            <td class="fw-bold">x{{ $item->SoLuong }}</td>
                            <td class="fw-bold" style="color: #0d6efd;">{{ number_format($thanhTien, 0, ',', '.') }} ₫</td>
                            
                            <td>
                                <div class="d-flex flex-column gap-2 justify-content-center">
                                    <a class="btn btn-dark btn-sm rounded-pill fw-bold" href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                        <i class="fa-solid fa-eye"></i> Xem SP
                                    </a>

                                    @if (!$DonHuy && $item->TrangThaiCT == "Đã xác nhận")
                                        @if (!$item->DaDanhGia)
                                            <a class="btn btn-outline-warning btn-sm rounded-pill" href="{{ route('taikhoan.danhgia', ['mahd' => $item->MaHD, 'masp' => $item->MaSP]) }}">
                                                <i class="fa-regular fa-star"></i> Đánh giá
                                            </a>
                                        @else
                                            <span class="btn btn-sm btn-light text-warning rounded-pill disabled" style="opacity: 1;">
                                                <i class="fa-solid fa-star"></i> Đã đánh giá
                                            </span>
                                        @endif

                                        @if (!$item->DaKhieuNai)
                                            <a href="{{ route('khieunai.taokhieunai', ['idsanpham' => $item->MaSP]) }}" class="btn btn-outline-danger btn-sm rounded-pill">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Khiếu nại
                                            </a>
                                        @else
                                            <span class="btn btn-light text-danger btn-sm rounded-pill disabled" style="opacity: 1;">
                                                <i class="fa-solid fa-shield-halved"></i> Đã khiếu nại
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Total Area -->
    <div class="row justify-content-end mb-4">
        <div class="col-lg-5 col-md-7">
            <div class="total-box shadow-sm">
                <div class="total-row">
                    <span>Tổng tiền hàng:</span>
                    <span class="text-dark fw-bold">{{ number_format($tongTienSanPham, 0, ',', '.') }} ₫</span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span class="text-dark fw-bold">0 ₫</span>
                </div>
                <div class="total-row final">
                    <span>Thành tiền:</span>
                    <span>{{ number_format($tongTienSanPham, 0, ',', '.') }} ₫</span>
                </div>
                <div class="text-end mt-2">
                    <small class="text-muted">(Đã bao gồm VAT nếu có)</small>
                </div>
            </div>
            
            <div class="mt-4 text-end">
                @if ($hd->TrangThai == "Đã thanh toán" && $chiTietVm->every(fn($x) => $x->TrangThaiCT == "Đã xác nhận"))
                    <a href="{{ route('hoadon.inhoadon', ['id' => $hd->MaHD]) }}" class="btn btn-dark btn-lg rounded-pill shadow-sm px-4 fw-bold">
                        <i class="fa-solid fa-print me-2"></i> In hoá đơn
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
