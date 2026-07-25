@extends('Shared._Layout')
@section('title', 'Sửa đơn hàng')

@section('content')
<div class="container my-5">
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

    <form action="{{ url('/taikhoan/suadonhang/' . $hd->MaHD) }}" method="POST">
        @csrf
        <input type="hidden" name="MaHD" value="{{ $hd->MaHD }}" />

        <div class="mb-3">
            <label class="form-label fw-bold">Phương thức thanh toán: </label>
            <input type="text" name="PhuongThucTT" class="form-control" style="max-width:400px;" value="{{ $hd->PhuongThucTT }}" />
            
            <label class="form-label fw-bold mt-3">Địa chỉ giao hàng: </label>
            <input type="text" name="DiaChiGiaoHang" class="form-control" style="max-width:400px;" value="{{ $hd->DiaChiGiaoHang }}" />
        </div>

        <h4 class="mt-4 fw-bold">Chi tiết hóa đơn</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hd->ctHoaDons as $index => $item)
                        <tr>
                            <td>{{ $item->sanPham->TenSP ?? '' }}</td>
                            <td>
                                <input type="number" name="CT_HOADON[{{ $index }}][SoLuong]" value="{{ $item->SoLuong }}" class="form-control" style="width: 100px;" />
                                <input type="hidden" name="CT_HOADON[{{ $index }}][MaSP]" value="{{ $item->MaSP }}" />
                            </td>
                            <td>{{ number_format($item->ThanhTien, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-save me-1"></i> Lưu thay đổi</button>
            <a href="{{ url('/taikhoan/lichsu') }}" class="btn btn-secondary px-4">Quay lại</a>
        </div>
    </form>
</div>
@endsection
