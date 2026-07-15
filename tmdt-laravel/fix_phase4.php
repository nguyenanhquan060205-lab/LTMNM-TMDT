<?php

$taoMoiView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <button onclick="history.back()" class="btn btn-outline-secondary rounded-pill shadow-sm me-3">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <h3 class="fw-bold text-dark mb-0"><i class="fa fa-plus-circle text-warning me-2"></i>Đăng Bán Sản Phẩm Mới</h3>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('sanpham.taomoi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="TenSP" class="form-control form-control-lg bg-light" placeholder="Nhập tên sản phẩm (VD: iPhone 13 Pro Max 256GB)" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="Gia" class="form-control form-control-lg bg-light text-danger fw-bold" placeholder="VD: 15000000" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="SoLuong" class="form-control form-control-lg bg-light" placeholder="VD: 1" min="1" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                                <select name="MaLoai" class="form-select form-select-lg bg-light" required>
                                    <option value="" disabled selected>-- Chọn loại sản phẩm --</option>
                                    @foreach($loaiSP as $loai)
                                        <option value="{{ $loai->MaLoai }}">{{ $loai->TenLoai }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tình trạng <span class="text-danger">*</span></label>
                                <select name="TinhTrang" class="form-select form-select-lg bg-light" required>
                                    <option value="Mới 100%">Mới 100%</option>
                                    <option value="Mới 99%">Mới 99% (Like New)</option>
                                    <option value="Mới 95%">Mới 95%</option>
                                    <option value="Cũ">Cũ</option>
                                    <option value="Hỏng/Xác">Hỏng / Bán xác</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="MoTa" class="form-control bg-light" rows="6" placeholder="Mô tả chi tiết tình trạng, xuất xứ, phụ kiện đi kèm..." required></textarea>
                        </div>

                        <div class="mb-5 p-4 border rounded-3 bg-light">
                            <label class="form-label fw-bold mb-3"><i class="fa fa-image text-primary me-2"></i>Ảnh Sản Phẩm (Tối đa 3 ảnh)</label>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Ảnh bìa chính <span class="text-danger">*</span></label>
                                <input type="file" name="AnhBia" class="form-control" accept="image/*" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label small fw-semibold text-muted">Ảnh phụ 1 (Không bắt buộc)</label>
                                    <input type="file" name="AnhPhu1" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Ảnh phụ 2 (Không bắt buộc)</label>
                                    <input type="file" name="AnhPhu2" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm rounded-pill py-3">
                            <i class="fa fa-paper-plane me-2"></i>Đăng Bán Ngay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/taomoi.blade.php', $taoMoiView);

$cuaToiView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fa-solid fa-box-open text-warning me-2"></i>Quản Lý Sản Phẩm Của Bạn</h2>
        <a href="{{ route('sanpham.taomoi') }}" class="btn btn-warning rounded-pill fw-bold shadow-sm px-4">
            <i class="fa fa-plus-circle me-2"></i>Đăng bán mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if ($dsSanPham->isEmpty())
                <div class="text-center p-5">
                    <i class="fa-solid fa-box fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Bạn chưa đăng bán sản phẩm nào.</h5>
                    <a href="{{ route('sanpham.taomoi') }}" class="btn btn-outline-warning rounded-pill mt-3 px-4">Đăng bán ngay</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th class="text-center">Số lượng</th>
                                <th>Tình trạng duyệt</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dsSanPham as $sp)
                                @php
                                    $anh = $sp->hinhAnhs->where('AnhBia', true)->first();
                                    $anhUrl = $anh ? $anh->URLAnh : 'noimage.jpg';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('Content/Images/' . $anhUrl) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                    </td>
                                    <td>
                                        <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block" style="max-width: 200px;">
                                            {{ $sp->TenSP }}
                                        </a>
                                        <small class="text-muted">{{ $sp->loaiSanPham->TenLoai ?? '' }}</small>
                                    </td>
                                    <td class="text-danger fw-bold">{{ number_format($sp->Gia, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        @if ($sp->SoLuong > 0)
                                            <span class="badge bg-success rounded-pill px-3">{{ $sp->SoLuong }}</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sp->TrangThai == 'Đã duyệt')
                                            <span class="badge bg-success"><i class="fa fa-check me-1"></i>Đã duyệt</span>
                                        @elseif ($sp->TrangThai == 'Từ chối')
                                            <span class="badge bg-danger"><i class="fa fa-times me-1"></i>Từ chối</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i>Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm rounded-pill" role="group">
                                            <a href="{{ route('sanpham.sua', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('sanpham.xoa', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $dsSanPham->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/cuatoi.blade.php', $cuaToiView);

$suaView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <button onclick="history.back()" class="btn btn-outline-secondary rounded-pill shadow-sm me-3">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <h3 class="fw-bold text-dark mb-0"><i class="fa fa-edit text-warning me-2"></i>Sửa Thông Tin Sản Phẩm</h3>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('sanpham.sua', ['id' => $sp->MaSP]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="TenSP" class="form-control form-control-lg bg-light" value="{{ $sp->TenSP }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="Gia" class="form-control form-control-lg bg-light text-danger fw-bold" value="{{ (int)$sp->Gia }}" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="SoLuong" class="form-control form-control-lg bg-light" value="{{ $sp->SoLuong }}" min="0" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                                <select name="MaLoai" class="form-select form-select-lg bg-light" required>
                                    @foreach($loaiSP as $loai)
                                        <option value="{{ $loai->MaLoai }}" {{ $loai->MaLoai == $sp->MaLoai ? 'selected' : '' }}>{{ $loai->TenLoai }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tình trạng <span class="text-danger">*</span></label>
                                <select name="TinhTrang" class="form-select form-select-lg bg-light" required>
                                    <option value="Mới 100%" {{ $sp->TinhTrang == 'Mới 100%' ? 'selected' : '' }}>Mới 100%</option>
                                    <option value="Mới 99%" {{ $sp->TinhTrang == 'Mới 99%' ? 'selected' : '' }}>Mới 99% (Like New)</option>
                                    <option value="Mới 95%" {{ $sp->TinhTrang == 'Mới 95%' ? 'selected' : '' }}>Mới 95%</option>
                                    <option value="Cũ" {{ $sp->TinhTrang == 'Cũ' ? 'selected' : '' }}>Cũ</option>
                                    <option value="Hỏng/Xác" {{ $sp->TinhTrang == 'Hỏng/Xác' ? 'selected' : '' }}>Hỏng / Bán xác</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="MoTa" class="form-control bg-light" rows="6" required>{{ $sp->MoTa }}</textarea>
                        </div>

                        <div class="mb-5 p-4 border rounded-3 bg-light">
                            <label class="form-label fw-bold mb-3"><i class="fa fa-image text-primary me-2"></i>Cập Nhật Ảnh Mới (Tùy chọn)</label>
                            <p class="text-muted small">Nếu bạn không chọn ảnh mới, hệ thống sẽ giữ nguyên ảnh cũ.</p>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Ảnh bìa chính</label>
                                <input type="file" name="AnhBia" class="form-control" accept="image/*">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label small fw-semibold text-muted">Ảnh phụ 1</label>
                                    <input type="file" name="AnhPhu1" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Ảnh phụ 2</label>
                                    <input type="file" name="AnhPhu2" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm rounded-pill py-3">
                            <i class="fa fa-save me-2"></i>Lưu Thay Đổi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/sua.blade.php', $suaView);

$hoaDonPdfView = <<<'EOD'
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
EOD;
if (!is_dir(__DIR__ . '/resources/views/hoadon')) {
    mkdir(__DIR__ . '/resources/views/hoadon', 0777, true);
}
file_put_contents(__DIR__ . '/resources/views/hoadon/chitiet.blade.php', $hoaDonPdfView);

echo "Phase 4 & PDF views updated!\n";
