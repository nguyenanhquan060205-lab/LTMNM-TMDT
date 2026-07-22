@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@php
    $hd = $HoaDon;
    $DonHuy = $hd->TrangThai != null && (strcasecmp(trim($hd->TrangThai), "Đã huỷ") === 0 || strcasecmp(trim($hd->TrangThai), "Đã hủy") === 0);
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


<div class="container mt-4">
    <div class="order-card">

        <h2 class="order-title mb-4">Chi tiết đơn hàng #{{ $hd->MaHD }}</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Ngày đặt:</strong> {{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('d/m/Y HH:mm') : '-' }}</p>
                <p><strong>Ngày thanh toán:</strong> {{ $hd->NgayTT ? \Carbon\Carbon::parse($hd->NgayTT)->format('d/m/Y HH:mm') : '-' }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Địa chỉ giao hàng:</strong> {{ $hd->DiaChiGiaoHang }}</p>

                <p>
                    <strong>Trạng thái đơn:</strong>
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

        <h4 class="mb-3">Danh sách sản phẩm</h4>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($chiTietVm as $item)
                    @php
                        $HuyCT = $item->TrangThaiCT != null && (strcasecmp(trim($item->TrangThaiCT), "Đã huỷ") === 0 || strcasecmp(trim($item->TrangThaiCT), "Đã hủy") === 0);
                    @endphp

                    <tr>
                        <td>{{ $item->TenSP }}</td>
                        <td>{{ $item->SoLuong }}</td>
                        <td class="price-red">{{ number_format($item->ThanhTien, 0, ',', '.') }} đ</td>

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
                            <a class="btn btn-primary btn-sm"
                               href="{{ route('sanpham.chitiet', ['id' => $item->MaSP]) }}">
                                Xem sản phẩm
                            </a>

                            @if (!$DonHuy && $item->TrangThaiCT == "Đã xác nhận")
                                @if (!$item->DaDanhGia)
                                    <a class="btn btn-success btn-sm ms-1"
                                       href="{{ route('taikhoan.danhgia', ['maHD' => $item->MaHD, 'maSP' => $item->MaSP]) }}">
                                        ⭐ Đánh giá
                                    </a>
                                @else
                                    <span class="text-muted ms-2">⭐ Đã đánh giá</span>
                                @endif

                                @if (!$item->DaKhieuNai)
                                    {{-- Temporary fallback if route doesn't exist, we will fix routes later --}}
                                    <a href="/khieunai/tao/{{ $item->MaSP }}"
                                       class="btn btn-danger btn-sm ms-1">
                                        Khiếu nại
                                    </a>
                                @else
                                    <span class="btn btn-outline-danger btn-sm ms-1 disabled">Đã khiếu nại</span>
                                @endif
                            @elseif ($DonHuy)

                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('taikhoan.lichsu') }}"
               class="btn btn-secondary px-4">
                ⬅ Quay lại
            </a>
            @if ($hd->TrangThai == "Đã thanh toán" && $chiTietVm->every(fn($x) => $x->TrangThaiCT == "Đã xác nhận"))
                <a href="{{ route('hoadon.inhoadon', ['id' => $hd->MaHD]) }}"
                   class="btn btn-success px-4">
                    🧾 In hoá đơn
                </a>
            @endif
        </div>

    </div>
</div>
@endsection

