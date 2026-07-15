@extends('shared._layoutadmin')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Sản Phẩm Đang Chờ</h6>
                    <h2 class="fw-bold mb-0">{{ $sanPhamChoDuyet }}</h2>
                </div>
                <i class="fa fa-box fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Tổng Người Dùng</h6>
                    <h2 class="fw-bold mb-0">{{ $tongNguoiDung }}</h2>
                </div>
                <i class="fa fa-users fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Đơn Hàng Hôm Nay</h6>
                    <h2 class="fw-bold mb-0">{{ $donHangMoi }}</h2>
                </div>
                <i class="fa fa-shopping-cart fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 bg-danger text-white h-100">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-uppercase mb-1 opacity-75">Khiếu Nại Mới</h6>
                    <h2 class="fw-bold mb-0">{{ $khieuNaiMoi }}</h2>
                </div>
                <i class="fa fa-exclamation-triangle fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Hoạt động gần đây</h5>
                <p class="text-muted">Hệ thống đang hoạt động ổn định.</p>
                <a href="{{ route('admin.quanlysanpham') }}" class="btn btn-primary rounded-pill px-4">Duyệt sản phẩm ngay</a>
            </div>
        </div>
    </div>
</div>
@endsection