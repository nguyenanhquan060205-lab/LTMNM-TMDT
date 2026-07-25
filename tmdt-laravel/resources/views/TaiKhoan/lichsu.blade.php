@extends('layouts.app')

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
<div class="container mt-4">
    <h2 class="mb-3"><i class="bi bi-clock-history"></i> Lịch sử mua hàng</h2>

    @if ($dsDonHang->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Ngày đặt</th>
                    <th>Ngày thanh toán</th>
                    <th>Tình trạng đơn</th>
                    <th>Phương thức thanh toán</th>
                    <th>Thao tác</th>
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
                                <span class="badge bg-danger">Đã hủy</span>
                            @elseif ($daTT)
                                <span class="badge bg-success">Thành công</span>
                            @elseif ($item->TrangThai == "Đang chờ xử lý")
                                <span class="badge bg-warning text-dark">Đang chờ xử lý</span>
                                <div><small class="text-muted">Chờ người bán xác nhận đủ</small></div>
                            @else
                                <span class="badge bg-secondary">{{ $item->TrangThai }}</span>
                            @endif
                        </td>

                        <td>{{ $item->PhuongThucTT }}</td>

                        <td>
                            <a class="btn btn-primary btn-sm"
                               href="{{ route('taikhoan.ct_lichsu', ['id' => $item->MaHD]) }}">
                                Xem chi tiết
                            </a>

                            @if (!$huy && !$daTT)
                                <a href="{{ route('taikhoan.huydonhang', ['id' => $item->MaHD]) }}"
                                   class="btn btn-danger btn-sm ms-1"
                                   onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
                                    Hủy
                                </a>

                                <a href="{{ route('taikhoan.suadonhang', ['id' => $item->MaHD]) }}"
                                   class="btn btn-warning btn-sm ms-1">
                                    Sửa
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
