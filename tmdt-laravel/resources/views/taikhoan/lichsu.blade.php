@extends('shared._layout')

@section('title', 'Lịch sử mua hàng')

@php
    function isHuy($t) {
        if (empty($t)) return false;
        return strcasecmp(trim($t), "Đã huỷ") === 0 || strcasecmp(trim($t), "Đã hủy") === 0;
    }
    function isThanhToan($t) {
        if (empty($t)) return false;
        return strcasecmp(trim($t), "Đã thanh toán") === 0;
    }
@endphp

@section('content')
<style>
    .page-title {
        color: #2a2a40;
        margin-top: 20px;
    }
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .table th {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: .85rem;
        padding: 18px 16px;
        border: none;
    }
    .table td {
        padding: 18px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: #334155;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    .badge-status {
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
        white-space: nowrap;
        display: inline-block;
    }
    .badge-success-custom { background-color: #0d6efd; color: white; }
    .badge-pending-custom { background-color: white; color: #1e293b; border: 1px solid #1e293b; }
    .badge-danger-custom { background-color: #1e293b; color: white; }
</style>

<div class="container my-5">
    <h3 class="fw-bold mb-4 text-center page-title">
        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>
        Lịch sử mua hàng
    </h3>

    @if ($dsDonHang->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
        <div class="table-container mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th style="width: 15%">Ngày đặt</th>
                            <th style="width: 15%">Ngày thanh toán</th>
                            <th style="width: 20%">Tình trạng đơn</th>
                            <th style="width: 25%">Phương thức thanh toán</th>
                            <th style="width: 25%">Thao tác</th>
                        </tr>
                    </thead>

            <tbody>
                @foreach ($dsDonHang as $item)
                    @php
                        $huy = isHuy($item->TrangThai);
                        $daTT = isThanhToan($item->TrangThai);
                    @endphp

                    <tr>
                        <td>
                            {{ $item->NgayDat ? \Carbon\Carbon::parse($item->NgayDat)->format('d/m/Y') : '-' }}
                        </td>

                        <td>
                            {{ $item->NgayTT ? \Carbon\Carbon::parse($item->NgayTT)->format('d/m/Y') : '-' }}
                        </td>

                        <td>
                            @if ($huy)
                                <span class="badge-status badge-danger-custom">Đã hủy</span>
                            @elseif ($daTT)
                                <span class="badge-status badge-success-custom">Thành công</span>
                            @elseif ($item->TrangThai == "Đang chờ xử lý")
                                <span class="badge-status badge-pending-custom">Đang chờ xử lý</span>
                                <div class="mt-2"><small class="text-muted fw-semibold">Chờ người bán xác nhận đủ</small></div>
                            @else
                                <span class="badge-status bg-secondary text-white">{{ $item->TrangThai }}</span>
                            @endif
                        </td>

                        <td>{{ $item->PhuongThucTT }}</td>

                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a class="btn btn-sm btn-dark rounded-pill px-4 fw-bold shadow-sm"
                                   href="{{ route('taikhoan.ct_lichsu', ['id' => $item->MaHD]) }}">
                                    <i class="fa-solid fa-eye me-1"></i> Xem
                                </a>

                                @if (!$huy && !$daTT)
                                    <a href="{{ route('taikhoan.suadonhang', ['id' => $item->MaHD]) }}"
                                       class="btn btn-sm btn-outline-dark rounded-pill px-4 fw-bold">
                                        <i class="fa-solid fa-pen"></i> Sửa
                                    </a>

                                    <div class="vr mx-1 text-muted"></div>
                                    <a href="{{ route('taikhoan.huydonhang', ['id' => $item->MaHD]) }}"
                                       class="btn btn-sm text-danger fw-bold"
                                       onclick="event.preventDefault(); Swal.fire({ title: 'Xác nhận', text: 'Bạn có chắc muốn hủy đơn hàng này không?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Đồng ý', cancelButtonText: 'Hủy' }).then((result) => { if (result.isConfirmed) window.location.href = this.href; })">
                                        Hủy đơn
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
