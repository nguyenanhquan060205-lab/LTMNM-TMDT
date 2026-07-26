<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hoá đơn #{{ $hd->MaHD }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #d9534f;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .product-table th {
            background-color: #f2f2f2;
        }
        .total-row td {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>TechSecond - Hoá đơn bán hàng</h1>
    <p>Mã Hóa Đơn: <strong>#{{ $hd->MaHD }}</strong></p>
    <p>Ngày xuất: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

<table class="info-table">
    <tr>
        <td style="width: 50%;">
            <strong>Thông tin người mua:</strong><br>
            Tên: {{ $hd->nguoiDung->HoTen ?? 'Khách hàng' }}<br>
            SĐT: {{ $hd->nguoiDung->SDT ?? 'Chưa cập nhật' }}<br>
            Email: {{ $hd->nguoiDung->Email ?? 'Chưa cập nhật' }}
        </td>
        <td style="width: 50%;">
            <strong>Thông tin đơn hàng:</strong><br>
            Địa chỉ giao: {{ $hd->DiaChiGiaoHang }}<br>
            Ngày đặt: {{ $hd->NgayDat ? \Carbon\Carbon::parse($hd->NgayDat)->format('d/m/Y H:i') : '' }}<br>
            Trạng thái: {{ $hd->TrangThai }}
        </td>
    </tr>
</table>

<table class="product-table">
    <thead>
        <tr>
            <th class="text-center">STT</th>
            <th>Tên sản phẩm</th>
            <th class="text-right">Đơn giá</th>
            <th class="text-center">SL</th>
            <th class="text-right">Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        @php $stt = 1; $tong = 0; @endphp
        @foreach($hd->ctHoaDons as $ct)
            @php 
                $tong += $ct->ThanhTien; 
            @endphp
            <tr>
                <td class="text-center">{{ $stt++ }}</td>
                <td>{{ $ct->sanPham->TenSP ?? 'Sản phẩm' }}</td>
                <td class="text-right">{{ number_format($ct->sanPham->Gia ?? 0, 0, ',', '.') }} đ</td>
                <td class="text-center">{{ $ct->SoLuong }}</td>
                <td class="text-right">{{ number_format($ct->ThanhTien, 0, ',', '.') }} đ</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4" class="text-right">Tổng thanh toán:</td>
            <td class="text-right" style="color: #d9534f;">{{ number_format($tong, 0, ',', '.') }} đ</td>
        </tr>
    </tbody>
</table>

<div style="margin-top: 50px;">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; width: 50%;">
                <strong>Người Mua Hàng</strong><br>
                <em>(Ký, ghi rõ họ tên)</em>
            </td>
            <td style="text-align: center; width: 50%;">
                <strong>Đại diện TechSecond</strong><br>
                <em>(Ký, ghi rõ họ tên)</em>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
