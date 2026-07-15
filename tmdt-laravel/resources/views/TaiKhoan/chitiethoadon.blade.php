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