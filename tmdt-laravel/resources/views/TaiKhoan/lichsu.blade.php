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