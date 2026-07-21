@extends('layouts.app')

@section('title', 'Sửa đơn hàng')

@section('content')
<div class="container mt-4 mb-5">
    <h2>Sửa đơn hàng</h2>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('taikhoan.suadonhang', ['id' => $hd->MaHD]) }}">
        @csrf
        <input type="hidden" name="MaHD" value="{{ $hd->MaHD }}" />

        <div class="mb-3">
            <label>Phương thức thanh toán: </label>
            <input type="text" name="PhuongThucTT" class="form-control" style="width:400px;" value="{{ $hd->PhuongThucTT }}" />
            
            <label class="mt-2">Địa chỉ giao hàng: </label>
            <input type="text" name="DiaChiGiaoHang" class="form-control" style="width:400px;" value="{{ $hd->DiaChiGiaoHang }}" />
        </div>

        <h4>Chi tiết hóa đơn</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hd->ctHoaDons as $i => $item)
                    <tr>
                        <td>{{ $item->sanPham->TenSP ?? '' }}</td>
                        <td>
                            <input type="number" name="CT_HOADON[{{ $i }}][SoLuong]" value="{{ $item->SoLuong }}" class="form-control" />
                            <input type="hidden" name="CT_HOADON[{{ $i }}][MaSP]" value="{{ $item->MaSP }}" />
                        </td>
                        <td>{{ number_format($item->ThanhTien, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Nút lưu thay đổi phải nằm trong form -->
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('taikhoan.lichsu') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</div>
@endsection
