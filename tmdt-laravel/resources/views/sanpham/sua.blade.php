@php
    $anhBiaCu = $sanPham->hinhAnhSPs->firstWhere('AnhBia', true)?->URLAnh;
    $anhChiTietCu = $sanPham->hinhAnhSPs->where('AnhBia', false);
@endphp

@extends('shared._layout')
@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<style>
    .form-box {
        border-radius: 18px;
        padding: 30px;
        background: #ffffff;
        border: 1px solid #e6e6e6;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .form-label i {
        margin-right: 6px;
        color: #0d6efd;
    }

    .form-control, .form-select {
        border-radius: 10px !important;
        padding: 10px 14px;
        border: 1px solid #d6d6d6;
        transition: .2s;
    }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }

    #previewCoverImg {
        border-radius: 10px;
        margin-top: 10px;
        border: 2px solid #0d6efd;
    }

    .preview-thumb {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #eee;
        transition: .2s;
    }

        .preview-thumb:hover {
            transform: scale(1.05);
            border-color: #0d6efd;
        }

    .btn-submit {
        padding: 10px 35px;
        font-weight: 600;
        font-size: 17px;
        border-radius: 10px;
        background-color: #0d6efd;
        color: white;
        border: none;
        transition: 0.2s;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

        .btn-submit:hover {
            transform: scale(1.05);
            background-color: #0b5ed7;
            color: white;
        }

    .title-icon {
        color: #0d6efd;
        font-size: 28px;
    }
</style>

<div class="container py-4">
    <h3 class="fw-bold mb-4">
        <i class="bi bi-pencil-square title-icon"></i> Chỉnh sửa sản phẩm: {{ $sanPham->TenSP }}
    </h3>

    <form action="{{ url('/sanpham/sua/'.$sanPham->MaSP) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="MaSP" value="{{ $sanPham->MaSP }}" />
        <input type="hidden" name="MaKH" value="{{ $sanPham->MaKH }}" />
        
        <div class="form-box">
            <div class="row">
            <!-- CỘT TRÁI -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tên sản phẩm</label>
                <input type="text" name="TenSP" class="form-control mb-3" placeholder="Nhập tên sản phẩm..." required="required" value="{{ $sanPham->TenSP }}" />

                <label class="form-label fw-semibold">Danh mục</label>
                <select name="MaLoai" class="form-select mb-3" required="required">
                    <option value="">Chọn danh mục</option>
                    @foreach ($maLoai as $loai)
                        <option value="{{ $loai->MaLoai }}" {{ $sanPham->MaLoai == $loai->MaLoai ? 'selected' : '' }}>{{ $loai->TenLoai }}</option>
                    @endforeach
                </select>

                <label class="form-label fw-semibold">Giá bán (VNĐ)</label>
                <input name="Gia" class="form-control mb-3 no-spin" type="number" min="0" placeholder="Ví dụ: 1500000" required value="{{ $sanPham->Gia }}" />

                <label class="form-label fw-semibold">Số lượng</label>
                <input name="SoLuong" class="form-control mb-3 no-spin" type="number" min="1" placeholder="Nhập số lượng" required value="{{ $sanPham->SoLuong }}" />

                <!-- ẢNH BÌA -->
                <label class="form-label fw-semibold mt-2">Ảnh bìa sản phẩm (Chọn file mới nếu muốn thay đổi)</label>
                <input type="file" name="anhBiaMoi" class="form-control mb-2" accept="image/*" onchange="previewCover(this)" />
                <small class="text-muted mb-2 d-block">Ảnh bìa hiện tại:</small>

                @if (!empty($anhBiaCu))
                    <img id="previewCoverImg" src="{{ str_starts_with($anhBiaCu, 'http') ? $anhBiaCu : asset('Content/Images/' . $anhBiaCu) }}" class="mt-2" style="width:160px;height:auto;" />
                @else
                    <img id="previewCoverImg" class="mt-2 d-none" style="width:160px;height:auto;" />
                @endif
            </div>

            <!-- CỘT PHẢI -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                <textarea name="MoTa" rows="6" class="form-control mb-3" placeholder="Mô tả tình trạng sản phẩm, phụ kiện đi kèm..." required="required">{{ $sanPham->MoTa }}</textarea>

                <!-- ẢNH CHI TIẾT -->
                <label class="form-label fw-semibold">Ảnh chi tiết sản phẩm (Chọn file mới để THAY THẾ toàn bộ ảnh chi tiết cũ)</label>
                <input type="file" name="files[]" class="form-control" multiple accept="image/*" onchange="previewImages(this)" />
                <small class="text-muted">Chọn file mới để thay thế toàn bộ ảnh chi tiết cũ. (Nếu không chọn, ảnh cũ sẽ giữ nguyên).</small>

                <div id="previewList" class="mt-3 d-flex flex-wrap gap-2">
                    @foreach ($anhChiTietCu as $anh)
                        <img src="{{ str_starts_with($anh->URLAnh, 'http') ? $anh->URLAnh : asset('Content/Images/' . $anh->URLAnh) }}" class="preview-thumb" />
                    @endforeach
                </div>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-upload"></i> Cập nhật tin bán
                </button>
                <a href="{{ url('/sanpham/chitiet/' . $sanPham->MaSP) }}" class="btn btn-outline-secondary ms-2" style="font-weight: 600; font-size: 17px; border-radius: 10px; padding: 10px 35px;">
                    Hủy bỏ
                </a>
            </div>
            </div>
    </form>
</div>

<style>
    .no-spin::-webkit-inner-spin-button,
    .no-spin::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<script>
    // Preview ảnh bìa
    function previewCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById("previewCoverImg");
                img.src = e.target.result;
                img.classList.remove("d-none");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Preview nhiều ảnh chi tiết
    function previewImages(input) {
        const preview = document.getElementById("previewList");
        preview.innerHTML = ""; // Xóa ảnh cũ khi chọn ảnh mới

        [...input.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList = "rounded border";
                img.style = "width:100px;height:100px;object-fit:cover;";
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection
