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