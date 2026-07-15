@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fa-solid fa-bell text-warning me-2"></i>Đơn Hàng Cần Xử Lý</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if ($ctHoaDonList->isEmpty())
                <div class="text-center p-5">
                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Bạn chưa có đơn hàng nào cần xử lý.</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Người đặt</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ctHoaDonList as $ct)
                                @php
                                    $sp = $ct->sanPham;
                                    $hd = $ct->hoaDon;
                                    $anh = $sp && $sp->hinhAnhs->where('AnhBia', true)->first() ? $sp->hinhAnhs->where('AnhBia', true)->first()->URLAnh : 'noimage.jpg';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('Content/Images/' . $anh) }}" class="rounded me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                            <a href="{{ $sp ? route('sanpham.chitiet', ['id' => $sp->MaSP]) : '#' }}" class="text-dark fw-bold text-decoration-none text-truncate d-block" style="max-width: 200px;">
                                                {{ $sp->TenSP ?? 'N/A' }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $hd->NguoiNhan ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $ct->SoLuong }}</td>
                                    <td class="text-danger fw-bold">{{ number_format($ct->ThanhTien, 0, ',', '.') }}₫</td>
                                    <td>{{ \Carbon\Carbon::parse($hd->NgayLap)->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($ct->TinhTrang == 'Đã xác nhận')
                                            <span class="badge bg-success">Đã xác nhận</span>
                                        @elseif ($ct->TinhTrang == 'Đã hủy')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sanpham.ctsanphamdaban', ['id' => $ct->MaCTHD]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $ctHoaDonList->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection