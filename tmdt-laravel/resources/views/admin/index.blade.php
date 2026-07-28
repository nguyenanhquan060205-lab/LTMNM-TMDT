@extends('shared._layoutadmin')
@section('title', 'Tổng quan hệ thống')

@section('content')
<style>
    .card-hover {
        transition: all 0.3s ease;
        border: none;
        background: #fff;
        border-radius: 16px;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        cursor: pointer;
    }
    .section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #2a2a40;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 12px;
        margin-bottom: 24px;
        margin-top: 35px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .section-title i { 
        color: #667eea; 
        background: rgba(102, 126, 234, 0.1);
        padding: 8px;
        border-radius: 8px;
    }
    .text-primary {
        color: #667eea !important;
    }
</style>

<div class="container-fluid px-2 pb-5">

    <!-- QUẢN LÝ SẢN PHẨM -->
    <div class="section-title">
        <i class="fa-solid fa-box"></i> Quản lý sản phẩm
    </div>
    <div class="row g-3">
        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlysanpham') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle p-3 me-3" style="background-color: rgba(111,66,193,0.15);">
                            <i class="fa-solid fa-tag fs-3" style="color:#6f42c1;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $daban ?? 0 }}</h3>
                            <span class="text-muted small">Đã bán</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlysanpham') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-lock text-secondary fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $an ?? 0 }}</h3>
                            <span class="text-muted small">Ẩn</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlysanpham') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-check-circle text-success fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $tinDaDuyet ?? 0 }}</h3>
                            <span class="text-muted small">Tin đang hiển thị</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlysanpham') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-boxes-stacked text-primary fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $tongSanPham ?? 0 }}</h3>
                            <span class="text-muted small">Tổng sản phẩm</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlysanpham') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-layer-group text-info fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Duyệt tin</h5>
                            <span class="text-muted small">Duyệt sản phẩm mới</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- QUẢN LÝ ĐƠN HÀNG -->
    <div class="section-title">
        <i class="fa-solid fa-file-invoice"></i> Quản lý đơn hàng
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <a href="{{ url('/admin/quanlydonhang') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-clipboard-list text-danger fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Đơn hàng</h5>
                            <span class="text-muted small">Xem và xử lý đơn hàng</span>
                        </div>
                        <div class="ms-auto text-end">
                            <i class="fa-solid fa-arrow-right text-muted"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- QUẢN LÝ NGƯỜI DÙNG -->
    <div class="section-title">
        <i class="fa-solid fa-users"></i> Quản lý người dùng
    </div>
    <div class="row g-3">
        <div class="col-md-4 col-xl-3">
            <a href="{{ url('/admin/quanlynguoidung') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-user-group text-info fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $tongNguoiDung ?? 0 }}</h3>
                            <span class="text-muted small">Tài khoản đăng ký</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- HỖ TRỢ & KHIẾU NẠI -->
    <div class="section-title">
        <i class="fa-solid fa-headset"></i> Hỗ trợ &amp; Khiếu nại
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <a href="{{ url('/admin/quanlykhieunai') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-triangle-exclamation text-secondary fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Khiếu nại</h5>
                            <span class="text-muted small">Giải quyết báo cáo</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-4">
            <a href="{{ url('/tinnhan/chat') }}" class="text-decoration-none text-reset">
                <div class="card card-hover h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fa-solid fa-comments text-primary fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Tin nhắn</h5>
                            <span class="text-muted small">Hỗ trợ trực tuyến</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection
