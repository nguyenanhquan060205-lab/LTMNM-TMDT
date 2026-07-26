@extends('layouts.app')

@section('title', 'Chi tiết hóa đơn')

@php
    $hd = $HoaDon;
    $DonHuy = $hd->TrangThai != null && strcasecmp(trim($hd->TrangThai), "Đã huỷ") === 0 || strcasecmp(trim($hd->TrangThai), "Đã hủy") === 0;
@endphp

@section('content')
<style>
    .order-title {
        font-size: 24px;
        font-weight: 700;
        color: #2a2a40;
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .table th {
        background: #f8f9fa !important;
        color: #4a5568 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .85rem;
        padding: 16px;
        border-bottom: 2px solid #edf2f7;
    }
    .table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.95rem;
    }
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .total-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #edf2f7;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 1rem;
        color: #4a5568;
    }
    .total-row.final {
        border-top: 2px dashed #cbd5e0;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: 700;
        font-size: 1.25rem;
        color: #e53e3e;
    }
</style>

<div class="container py-4 mb-5">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="order-title mb-0">
            Chi tiết đơn hàng bán <span class="text-primary">#{{ $hd->MaHD }}</span>
        </h2>
        <a href="{{ route('sanpham.daban') }}" class="btn btn-outline-secondary rounded-pill px-4" style="font-weight: 500;">
            <i class="fa-solid fa-arrow-left me-2"></i> Trở về danh sách
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- THÔNG TIN HÓA ĐƠN -->
    <div class="row g-4 mb-4">
        <!-- Thông tin người mua -->
        <div class="col-md-6">
            <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-user me-2"></i>Thông tin người mua</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="text-muted small mb-1">Tên khách hàng</div>
                        <div class="fw-bold text-dark mb-3">{{ $hd->nguoiDung->HoTen ?? 'Khách hàng' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Số điện thoại</div>
                        <div class="fw-bold text-dark mb-3">{{ $hd->nguoiDung->SDT ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="col-md-6">
            <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-truck-fast me-2"></i>Thông tin đơn hàng</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="text-muted small mb-1">Ngày đặt hàng</div>
                        <div class="fw-bold text-dark mb-3">{{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Trạng thái hóa đơn</div>
                        <div class="fw-bold text-dark mb-3">
                            @if ($DonHuy)
                                <span class="badge bg-danger badge-status">Đã huỷ</span>
                            @elseif ($hd->TrangThai == "Đã thanh toán")
                                <span class="badge bg-success badge-status">Đã thanh toán</span>
                            @else
                                <span class="badge bg-warning text-dark badge-status">Đang chờ xử lý</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small mb-1">Địa chỉ giao hàng</div>
                        <div class="fw-bold text-dark"><i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $hd->DiaChiGiaoHang ?? 'Không có địa chỉ' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DANH SÁCH SẢN PHẨM -->
    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clipboard-list text-secondary me-2"></i> Sản phẩm bạn đã bán</h5>
    
    <div class="table-container mb-4">
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr class="text-center">
                        <th class="text-start" style="width: 45%;">Sản phẩm</th>
                        <th style="width: 15%;">Đơn giá</th>
                        <th style="width: 10%;">Số lượng</th>
                        <th style="width: 15%;">Thành tiền</th>
                        <th style="width: 15%;">Trạng thái</th>
                        <th style="width: 15%;">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="text-center">
                    @php $tongTienSanPham = 0; @endphp
                    @forelse ($chiTiet as $item)
                        @php
                            $HuyCT = $item->TrangThaiCT != null && (strcasecmp(trim($item->TrangThaiCT), "Đã huỷ") === 0 || strcasecmp(trim($item->TrangThaiCT), "Đã hủy") === 0);
                            
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
                                        <h6 class="mb-1 fw-bold text-dark">{{ $item->sanPham->TenSP ?? 'Sản phẩm' }}</h6>
                                    </div>
                                </div>
                            </td>

                            <td class="text-muted fw-medium">{{ number_format($donGia, 0, ',', '.') }} ₫</td>
                            <td class="fw-bold">x{{ $item->SoLuong }}</td>
                            <td class="text-danger fw-bold">{{ number_format($thanhTien, 0, ',', '.') }} ₫</td>

                            <td>
                                @if ($DonHuy || $HuyCT)
                                    <span class="badge bg-danger badge-status">Đã huỷ</span>
                                @elseif ($item->TrangThaiCT == "Đã xác nhận")
                                    <span class="badge bg-success badge-status">Đã xác nhận</span>
                                @else
                                    <span class="badge bg-secondary badge-status">Chờ xác nhận</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-2 justify-content-center">
                                    <!-- XEM SẢN PHẨM -->
                                    <a class="btn btn-outline-primary btn-sm rounded-pill"
                                       href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                        <i class="fa-solid fa-eye"></i> Xem SP
                                    </a>

                                    @if (!$DonHuy && !$HuyCT && $item->TrangThaiCT == "Chờ xác nhận")
                                        <!-- DUYỆT TỪNG SẢN PHẨM -->
                                        <form action="{{ route('hoadon.xacnhansanpham', ['mahd' => $item->MaHD, 'masp' => $item->MaSP]) }}"
                                              method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Xác nhận sản phẩm này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm rounded-pill w-100">
                                                <i class="fa-solid fa-check"></i> Duyệt
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Không có sản phẩm nào thuộc hóa đơn này
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TỔNG KẾT VÀ THAO TÁC -->
    <div class="d-flex flex-wrap justify-content-end align-items-start mb-5 mt-4">
        
        <!-- Bên trái có thể để trống hoặc để nút, tùy thiết kế -->
        <div class="d-flex gap-3 mb-4 mt-2 me-auto">
            @if (!$DonHuy && collect($chiTiet)->contains('TrangThaiCT', 'Chờ xác nhận'))
                <form action="{{ route('sanpham.hoanthanhhoadon', ['id' => $hd->MaHD]) }}"
                      method="post"
                      class="d-inline"
                      onsubmit="return confirm('Xác nhận duyệt toàn bộ sản phẩm trong hóa đơn này?')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm px-4" style="font-weight: 500;">
                        <i class="fa-solid fa-check-double me-2"></i> Duyệt tất cả
                    </button>
                </form>
            @endif
        </div>

        <!-- Bên phải: Tổng kết doanh thu -->
        <div class="total-box shadow-sm" style="min-width: 380px;">
            <div class="total-row">
                <span>Tổng tiền hàng:</span>
                <span class="text-dark fw-bold">{{ number_format($tongTienSanPham, 0, ',', '.') }} ₫</span>
            </div>
            <div class="total-row final">
                <span>Bạn sẽ nhận được:</span>
                <span>{{ number_format($tongTienSanPham, 0, ',', '.') }} ₫</span>
            </div>
        </div>
    </div>

</div>
@endsection
