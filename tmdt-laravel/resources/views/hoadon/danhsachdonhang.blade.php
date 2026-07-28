@extends('shared._layout')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold text-primary mb-4">Quản lý đơn hàng</h2>

    @if (session('Success'))
        <div class="alert alert-success text-center">{{ session('Success') }}</div>
    @endif

    @if (session('Error'))
        <div class="alert alert-danger text-center">{{ session('Error') }}</div>
    @endif

    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Mã HĐ</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($model as $hd)
                <tr>
                    <td>{{ $hd->MaHD }}</td>
                    <td>{{ $hd->nguoiDung->HoTen ?? '' }}</td>
                    <td class="text-danger fw-bold">{{ number_format($hd->TongTien, 0, ',', '.') }} ₫</td>
                    <td>{{ $hd->PhuongThucTT }}</td>
                    <td>
                        <span class="badge 
                            {{ $hd->TrangThai == 'Đã thanh toán' ? 'bg-success' : 
                               ($hd->TrangThai == 'Đang vận chuyển' ? 'bg-info' : 
                               ($hd->TrangThai == 'Đã huỷ' ? 'bg-danger' : 'bg-warning')) }}">
                            {{ $hd->TrangThai }}
                        </span>
                    </td>
                    <td>{{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('d/m/Y H:i') : '' }}</td>
                    <td>
                        <a href="{{ url('/HoaDon/ChiTiet/' . $hd->MaHD) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Xem
                        </a>

                        @if ($hd->TrangThai != 'Đã thanh toán')
                            <form action="{{ url('/hoadon/xacnhanthanhtoan') }}" method="post" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $hd->MaHD }}" />
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check2-circle"></i> Xác nhận thanh toán
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
