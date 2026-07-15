<?php

$lichSuView = <<<'EOD'
@extends('shared._layout')
@section('content')
@php
    $anh = $user && !empty($user->AnhDaiDien) ? asset('Content/Avatars/' . $user->AnhDaiDien) : asset('Content/Avatars/default.jpg');
@endphp
<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <img src="{{ $anh }}" class="rounded-circle mx-auto mb-3 shadow" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.src='{{ asset('Content/Avatars/default.jpg') }}';" />
                <h5 class="fw-bold">{{ $user->HoTen ?? 'Ẩn danh' }}</h5>
                <div class="list-group list-group-flush text-start mt-3">
                    <a href="{{ route('taikhoan.thongtin') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-user me-2"></i>Thông tin cá nhân</a>
                    <a href="{{ route('taikhoan.doimatkhau') }}" class="list-group-item list-group-item-action border-0"><i class="fa fa-lock me-2"></i>Đổi mật khẩu</a>
                    <a href="{{ route('taikhoan.lichsu') }}" class="list-group-item list-group-item-action active fw-bold border-0 rounded"><i class="fa fa-history me-2"></i>Lịch sử giao dịch</a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Lịch Sử Mua Hàng</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
                    @endif
                    
                    @if ($lichSuHD->isEmpty())
                        <div class="text-center p-5">
                            <i class="fa fa-file-invoice fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('home.index') }}" class="btn btn-warning rounded-pill mt-2">Mua sắm ngay</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã HĐ</th>
                                        <th>Ngày lập</th>
                                        <th>Sản phẩm</th>
                                        <th>Tổng tiền</th>
                                        <th>Tình trạng</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lichSuHD as $hd)
                                        <tr>
                                            <td class="fw-bold">#{{ $hd->MaHD }}</td>
                                            <td>{{ \Carbon\Carbon::parse($hd->NgayLap)->format('d/m/Y') }}</td>
                                            <td>
                                                <ul class="list-unstyled mb-0 small text-truncate" style="max-width: 200px;">
                                                    @foreach ($hd->cTHoaDons as $ct)
                                                        <li>- {{ $ct->sanPham->TenSP ?? 'Sản phẩm đã xóa' }} (x{{ $ct->SoLuong }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="text-danger fw-bold">{{ number_format($hd->TongTien, 0, ',', '.') }}₫</td>
                                            <td>
                                                @if ($hd->TinhTrang == 'Đang xử lý' || $hd->TinhTrang == 'Chờ xác nhận')
                                                    <span class="badge bg-warning text-dark">{{ $hd->TinhTrang }}</span>
                                                @elseif ($hd->TinhTrang == 'Đã hoàn thành')
                                                    <span class="badge bg-success">{{ $hd->TinhTrang }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $hd->TinhTrang }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('taikhoan.chitiethoadon', ['id' => $hd->MaHD]) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                    Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $lichSuHD->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/lichsu.blade.php', $lichSuView);

$chiTietHoaDonKhachView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex align-items-center mb-4">
        <button onclick="history.back()" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm me-3">
            <i class="fa fa-arrow-left me-2"></i>Quay lại
        </button>
        <h3 class="fw-bold text-dark mb-0">Chi Tiết Đơn Hàng #{{ $hoaDon->MaHD }}</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Thông tin đơn hàng & Người nhận -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fa fa-info-circle me-2 text-warning"></i>Thông Tin Đơn Hàng</h5>
                    <p class="mb-2"><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($hoaDon->NgayLap)->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($hoaDon->TongTien, 0, ',', '.') }}₫</span></p>
                    <p class="mb-3">
                        <strong>Trạng thái:</strong> 
                        @if ($hoaDon->TinhTrang == 'Đang xử lý' || $hoaDon->TinhTrang == 'Chờ xác nhận')
                            <span class="badge bg-warning text-dark">{{ $hoaDon->TinhTrang }}</span>
                        @elseif ($hoaDon->TinhTrang == 'Đã hoàn thành')
                            <span class="badge bg-success">{{ $hoaDon->TinhTrang }}</span>
                        @else
                            <span class="badge bg-danger">{{ $hoaDon->TinhTrang }}</span>
                        @endif
                    </p>

                    <h5 class="fw-bold border-bottom pb-2 mb-3 mt-4"><i class="fa fa-truck me-2 text-primary"></i>Thông Tin Giao Hàng</h5>
                    <p class="mb-2"><strong>Người nhận:</strong> {{ $hoaDon->NguoiNhan ?? $user->HoTen }}</p>
                    <p class="mb-2"><strong>Số ĐT:</strong> {{ $hoaDon->SDTNhan ?? $user->SDT }}</p>
                    <p class="mb-2"><strong>Địa chỉ:</strong> {{ $hoaDon->DiaChiNhan ?? $user->DiaChi }}</p>
                    <p class="mb-0"><strong>Ghi chú:</strong> {{ $hoaDon->GhiChu ?? 'Không có' }}</p>

                    @if ($hoaDon->TinhTrang == 'Chờ xác nhận' || $hoaDon->TinhTrang == 'Đang xử lý')
                        <form action="{{ route('taikhoan.huydonhang', ['id' => $hoaDon->MaHD]) }}" method="POST" class="mt-4" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">Hủy Đơn Hàng</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-4"><i class="fa fa-box-open me-2 text-success"></i>Sản Phẩm Đã Đặt</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th>Thành tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hoaDon->cTHoaDons as $ct)
                                    @php
                                        $sp = $ct->sanPham;
                                        $anh = $sp && $sp->hinhAnhs->where('AnhBia', true)->first() ? $sp->hinhAnhs->where('AnhBia', true)->first()->URLAnh : 'noimage.jpg';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('Content/Images/' . $anh) }}" class="rounded me-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                                <a href="{{ $sp ? route('sanpham.chitiet', ['id' => $sp->MaSP]) : '#' }}" class="text-dark fw-bold text-decoration-none">
                                                    {{ $sp->TenSP ?? 'Sản phẩm không tồn tại' }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-danger fw-bold">{{ number_format($ct->DonGia, 0, ',', '.') }}₫</td>
                                        <td class="text-center">{{ $ct->SoLuong }}</td>
                                        <td class="text-danger fw-bold">{{ number_format($ct->ThanhTien, 0, ',', '.') }}₫</td>
                                        <td>
                                            @if ($ct->TinhTrang == 'Đã xác nhận')
                                                <span class="badge bg-success">Đã xác nhận</span>
                                                @if ($hoaDon->TinhTrang == 'Đã hoàn thành')
                                                    <!-- Nút Đánh giá -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning mt-1 rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $ct->MaSP }}">Đánh giá</button>

                                                    <!-- Modal Đánh giá -->
                                                    <div class="modal fade" id="reviewModal{{ $ct->MaSP }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content rounded-4 border-0 shadow">
                                                                <div class="modal-header bg-warning">
                                                                    <h5 class="modal-title fw-bold text-dark">Đánh giá sản phẩm</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form action="{{ route('taikhoan.danhgiasanpham') }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="MaSP" value="{{ $ct->MaSP }}">
                                                                        <div class="mb-3 text-center">
                                                                            <label class="form-label fw-bold">Điểm đánh giá (1-5)</label>
                                                                            <input type="number" name="Diem" class="form-control text-center mx-auto" style="width: 80px;" min="1" max="5" value="5" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold">Nhận xét</label>
                                                                            <textarea name="NhanXet" class="form-control" rows="3" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..." required></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer border-0">
                                                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
                                                                        <button type="submit" class="btn btn-warning rounded-pill fw-bold">Gửi đánh giá</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @elseif ($ct->TinhTrang == 'Đã hủy')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @else
                                                <span class="badge bg-secondary">Chờ xác nhận</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/taikhoan/chitiethoadon.blade.php', $chiTietHoaDonKhachView);

$sanPhamDaBanView = <<<'EOD'
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
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/sanphamdaban.blade.php', $sanPhamDaBanView);

$ctSanPhamDaBanView = <<<'EOD'
@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex align-items-center mb-4">
        <button onclick="history.back()" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm me-3">
            <i class="fa fa-arrow-left me-2"></i>Quay lại
        </button>
        <h3 class="fw-bold text-dark mb-0">Xử Lý Đơn Hàng #{{ $ctHoaDon->MaCTHD }}</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Thông tin đơn hàng & Người nhận -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fa fa-truck me-2 text-primary"></i>Thông Tin Giao Hàng</h5>
                    @php $hd = $ctHoaDon->hoaDon; @endphp
                    <p class="mb-2"><strong>Mã HĐ:</strong> #{{ $hd->MaHD }}</p>
                    <p class="mb-2"><strong>Người nhận:</strong> {{ $hd->NguoiNhan }}</p>
                    <p class="mb-2"><strong>Số ĐT:</strong> {{ $hd->SDTNhan }}</p>
                    <p class="mb-2"><strong>Địa chỉ:</strong> {{ $hd->DiaChiNhan }}</p>
                    <p class="mb-2"><strong>Ghi chú:</strong> {{ $hd->GhiChu ?? 'Không có' }}</p>
                    <p class="mb-0"><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($hd->NgayLap)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm & Thao tác -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold border-bottom pb-2 mb-4 text-start"><i class="fa fa-box-open me-2 text-success"></i>Sản Phẩm Của Bạn</h5>
                    
                    @php
                        $sp = $ctHoaDon->sanPham;
                        $anh = $sp && $sp->hinhAnhs->where('AnhBia', true)->first() ? $sp->hinhAnhs->where('AnhBia', true)->first()->URLAnh : 'noimage.jpg';
                    @endphp
                    
                    <img src="{{ asset('Content/Images/' . $anh) }}" class="rounded shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                    <h4 class="fw-bold">{{ $sp->TenSP ?? 'Sản phẩm đã xóa' }}</h4>
                    <p class="text-muted mb-2">Số lượng đặt: <strong class="text-dark fs-5">{{ $ctHoaDon->SoLuong }}</strong></p>
                    <p class="text-muted mb-4">Thành tiền: <strong class="text-danger fs-3">{{ number_format($ctHoaDon->ThanhTien, 0, ',', '.') }}₫</strong></p>

                    <div class="border p-4 rounded-3 bg-light d-inline-block w-100" style="max-width: 500px;">
                        <h6 class="fw-bold mb-3">Tình trạng: 
                            @if ($ctHoaDon->TinhTrang == 'Đã xác nhận')
                                <span class="badge bg-success fs-6">Đã xác nhận</span>
                            @elseif ($ctHoaDon->TinhTrang == 'Đã hủy')
                                <span class="badge bg-danger fs-6">Đã hủy</span>
                            @else
                                <span class="badge bg-warning text-dark fs-6">Chờ xác nhận</span>
                            @endif
                        </h6>

                        @if ($ctHoaDon->TinhTrang == 'Chưa xác nhận' || $ctHoaDon->TinhTrang == 'Đang xử lý')
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                <form action="{{ route('sanpham.hoanthanhhoadon', ['id' => $ctHoaDon->MaCTHD]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm px-4">
                                        <i class="fa fa-check-circle me-2"></i>Xác nhận Giao Hàng
                                    </button>
                                </form>
                                <form action="{{ route('sanpham.huyhoadonban', ['id' => $ctHoaDon->MaCTHD]) }}" method="POST" onsubmit="return confirm('Xác nhận TỪ CHỐI đơn hàng này?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-lg rounded-pill fw-bold px-4">
                                        <i class="fa fa-times-circle me-2"></i>Từ chối
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info mt-3 mb-0">
                                Đơn hàng này đã được xử lý ({{ $ctHoaDon->TinhTrang }}).
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;
file_put_contents(__DIR__ . '/resources/views/sanpham/ct_sanphamdaban.blade.php', $ctSanPhamDaBanView);

echo "Phase 3: Checkout & Orders views updated!\n";
