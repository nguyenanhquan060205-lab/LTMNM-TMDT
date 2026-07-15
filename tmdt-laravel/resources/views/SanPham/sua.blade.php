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