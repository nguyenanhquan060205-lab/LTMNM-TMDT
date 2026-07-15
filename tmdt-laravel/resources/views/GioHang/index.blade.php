@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <h2 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-cart-shopping text-warning me-2"></i>Giỏ Hàng Của Bạn</h2>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif

    @if (empty($gioHang) || count($gioHang) == 0)
        <div class="text-center p-5 bg-white shadow-sm rounded-4 border">
            <img src="{{ asset('Content/Images/empty-cart.png') }}" alt="Giỏ hàng trống" style="width: 150px; opacity: 0.5; margin-bottom: 20px;" onerror="this.style.display='none';">
            <h4 class="text-muted mb-3">Giỏ hàng của bạn đang trống!</h4>
            <a href="{{ route('home.index') }}" class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow-sm">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th class="text-center pe-4">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gioHang as $item)
                                        @php
                                            $anhUrl = $item['AnhBia'] ? asset('Content/Images/' . $item['AnhBia']) : asset('Content/Images/noimage.jpg');
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('sanpham.chitiet', ['id' => $item['MaSP']]) }}">
                                                        <img src="{{ $anhUrl }}" class="rounded shadow-sm me-3" style="width: 70px; height: 70px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                                    </a>
                                                    <div>
                                                        <a href="{{ route('sanpham.chitiet', ['id' => $item['MaSP']]) }}" class="text-decoration-none text-dark fw-bold d-block text-truncate" style="max-width: 200px;">
                                                            {{ $item['TenSP'] }}
                                                        </a>
                                                        <small class="text-muted">Người bán: {{ $item['NguoiBan'] ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-danger fw-bold">{{ number_format($item['DonGia'], 0, ',', '.') }}₫</td>
                                            <td>
                                                <div class="input-group input-group-sm mx-auto" style="width: 100px;">
                                                    <a href="{{ route('giohang.giam', ['id' => $item['MaSP']]) }}" class="btn btn-outline-secondary px-2"><i class="fa fa-minus"></i></a>
                                                    <input type="text" class="form-control text-center fw-bold bg-white" value="{{ $item['SoLuong'] }}" readonly>
                                                    <a href="{{ route('giohang.tang', ['id' => $item['MaSP']]) }}" class="btn btn-outline-secondary px-2"><i class="fa fa-plus"></i></a>
                                                </div>
                                            </td>
                                            <td class="text-danger fw-bold">{{ number_format($item['ThanhTien'], 0, ',', '.') }}₫</td>
                                            <td class="text-center pe-4">
                                                <a href="{{ route('giohang.xoa', ['id' => $item['MaSP']]) }}" class="text-secondary hover-danger transition-02" title="Xóa">
                                                    <i class="fa-solid fa-trash-can fa-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng Tiền & Thanh Toán -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky top-sticky">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-3">Tóm Tắt Đơn Hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính ({{ $tongSoLuong }} sản phẩm):</span>
                            <span class="fw-bold">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                            <span class="text-muted">Phí giao hàng:</span>
                            <span class="fw-bold text-success">Miễn phí</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="text-danger fw-bold fs-4">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                        </div>

                        <a href="{{ route('hoadon.dathang') }}" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill shadow-sm py-3 mb-2">
                            Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                        <a href="{{ route('home.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .hover-danger:hover { color: #dc3545 !important; }
    .transition-02 { transition: 0.2s ease; }
    .top-sticky { top: 90px; }
</style>
@endsection