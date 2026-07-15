@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-shopping-cart me-2 text-success"></i>Quản Lý Đơn Hàng</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Khách đặt</th>
                        <th>Tổng tiền</th>
                        <th>Ngày lập</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoaDons as $hd)
                        <tr>
                            <td class="fw-bold">#{{ $hd->MaHD }}</td>
                            <td>{{ $hd->nguoiDung->HoTen ?? 'N/A' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($hd->TongTien, 0, ',', '.') }}₫</td>
                            <td>{{ \Carbon\Carbon::parse($hd->NgayLap)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($hd->TinhTrang == 'Đã hoàn thành')
                                    <span class="badge bg-success">Đã hoàn thành</span>
                                @elseif ($hd->TinhTrang == 'Đã hủy')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $hd->TinhTrang }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('taikhoan.chitiethoadon', ['id' => $hd->MaHD]) }}" class="btn btn-sm btn-info text-white rounded-pill px-3">Xem</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $hoaDons->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection