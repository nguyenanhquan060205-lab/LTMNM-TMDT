<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .invoice-title { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .details { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-title">HÓA ĐƠN MUA HÀNG - TECHSECOND</div>
        <div>Mã Hóa Đơn: #{{ $hoaDon->MaHD }}</div>
        <div>Ngày lập: {{ \Carbon\Carbon::parse($hoaDon->NgayLap)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="details">
        <p><strong>Khách hàng:</strong> {{ $hoaDon->nguoiDung->HoTen ?? 'N/A' }}</p>
        <p><strong>Người nhận:</strong> {{ $hoaDon->NguoiNhan }}</p>
        <p><strong>Số điện thoại:</strong> {{ $hoaDon->SDTNhan }}</p>
        <p><strong>Địa chỉ:</strong> {{ $hoaDon->DiaChiNhan }}</p>
        <p><strong>Trạng thái:</strong> {{ $hoaDon->TinhTrang }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>SL</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hoaDon->cTHoaDons as $index => $ct)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ct->sanPham->TenSP ?? 'Sản phẩm đã xóa' }}</td>
                    <td>{{ number_format($ct->DonGia, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $ct->SoLuong }}</td>
                    <td>{{ number_format($ct->ThanhTien, 0, ',', '.') }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Tổng Cần Thanh Toán: {{ number_format($hoaDon->TongTien, 0, ',', '.') }} VNĐ
    </div>
</body>
</html>