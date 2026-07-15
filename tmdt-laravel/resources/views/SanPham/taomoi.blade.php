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