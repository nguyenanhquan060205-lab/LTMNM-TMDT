@extends('layouts.app')

@section('title', 'Chi tiết hóa đơn')

@php
    $hd = $HoaDon;
    $DonHuy = $hd->TrangThai != null && strcasecmp(trim($hd->TrangThai), "Đã huỷ") === 0 || strcasecmp(trim($hd->TrangThai), "Đã hủy") === 0;
@endphp

@section('content')
<style>
    .order-card {
        background: #fff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .order-title {
        font-size: 28px;
        font-weight: 700;
    }

    .table thead th {
        background: #1e1e1e;
        color: white;
    }

    .price-red {
        color: #e60000;
        font-weight: 700;
    }

    .badge-status {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="order-card">

        <h2 class="order-title mb-4">
            Chi tiết hóa đơn #{{ $hd->MaHD }}
        </h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- THÔNG TIN HÓA ĐƠN -->
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Ngày đặt:</strong> {{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('d/m/Y HH:mm') : '-' }}</p>
                <p><strong>Ngày thanh toán:</strong> {{ $hd->NgayTT ? \Carbon\Carbon::parse($hd->NgayTT)->format('d/m/Y HH:mm') : '-' }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Địa chỉ giao hàng:</strong> {{ $hd->DiaChiGiaoHang }}</p>

                <p>
                    <strong>Trạng thái hóa đơn:</strong>
                    @if ($DonHuy)
                        <span class="badge bg-danger badge-status">Đã huỷ</span>
                    @elseif ($hd->TrangThai == "Đã thanh toán")
                        <span class="badge bg-success badge-status">Đã thanh toán</span>
                    @else
                        <span class="badge bg-warning text-dark badge-status">Đang chờ xử lý</span>
                    @endif
                </p>
            </div>
        </div>

        <hr />

        <!-- DANH SÁCH SẢN PHẨM CỦA NGƯỜI BÁN -->
        <h4 class="mb-3">Sản phẩm bạn đã bán</h4>

        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Thành tiền</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($chiTiet as $item)
                    @php
                        $HuyCT = $item->TrangThaiCT != null && (strcasecmp(trim($item->TrangThaiCT), "Đã huỷ") === 0 || strcasecmp(trim($item->TrangThaiCT), "Đã hủy") === 0);
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $item->sanPham->TenSP ?? '' }}</td>

                        <td class="text-center">{{ $item->SoLuong }}</td>

                        <td class="text-end price-red">
                            {{ number_format($item->ThanhTien, 0, ',', '.') }} đ
                        </td>

                        <td class="text-center">
                            @if ($DonHuy || $HuyCT)
                                <span class="badge bg-danger badge-status">Đã huỷ</span>
                            @elseif ($item->TrangThaiCT == "Đã xác nhận")
                                <span class="badge bg-success badge-status">Đã xác nhận</span>
                            @else
                                <span class="badge bg-secondary badge-status">Chờ xác nhận</span>
                            @endif
                        </td>

                        <td class="text-center text-nowrap">
                            <!-- XEM SẢN PHẨM -->
                            <a class="btn btn-primary btn-sm"
                               href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                <i class="bi bi-eye"></i> Xem
                            </a>

                            @if (!$DonHuy && !$HuyCT && $item->TrangThaiCT == "Chờ xác nhận")
                                <!-- DUYỆT TỪNG SẢN PHẨM -->
                                <form action="{{ route('hoadon.xacnhansanpham', ['mahd' => $item->MaHD, 'masp' => $item->MaSP]) }}"
                                      method="post"
                                      class="d-inline"
                                      onsubmit="return confirm('Xác nhận sản phẩm này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm ms-1">
                                        <i class="bi bi-check-circle"></i> Duyệt
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Không có sản phẩm nào thuộc hóa đơn này
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('sanpham.daban') }}"
               class="btn btn-secondary px-4">
                ⬅ Quay lại
            </a>

            @if (!$DonHuy && collect($chiTiet)->contains('TrangThaiCT', 'Chờ xác nhận'))
                <form action="{{ route('sanpham.hoanthanhhoadon', ['id' => $hd->MaHD]) }}"
                      method="post"
                      class="d-inline"
                      onsubmit="return confirm('Xác nhận hoàn thành toàn bộ sản phẩm trong hóa đơn này?')">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">
                        ✅ Hoàn thành đơn
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
@endsection
