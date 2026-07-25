@extends('layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@php
    $anhBiaCuObj = collect($sanPham->hinhAnhs)->firstWhere('AnhBia', true);
    $anhBiaCu = $anhBiaCuObj ? $anhBiaCuObj->URLAnh : ($sanPham->AnhBia ?? '');
    
    $anhChiTietCu = collect($sanPham->hinhAnhs)->where('AnhBia', false)->values();
@endphp

@section('content')
<div class="container py-4 mb-5">
    <h3 class="fw-bold mb-4 text-primary">📝 Chỉnh sửa sản phẩm: {{ $sanPham->TenSP }}</h3>

    <form method="POST" action="{{ route('sanpham.sua', ['id' => $sanPham->MaSP]) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="MaSP" value="{{ $sanPham->MaSP }}" />
        <input type="hidden" name="MaKH" value="{{ $sanPham->MaKH }}" />
        <input type="hidden" name="NgayTao" value="{{ $sanPham->NgayTao }}" />

        <div class="row bg-white shadow rounded p-4">

            <!-- CỘT TRÁI -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">Tên sản phẩm</label>
                <input type="text" name="TenSP" class="form-control mb-3" placeholder="Nhập tên sản phẩm..." required value="{{ $sanPham->TenSP }}" />

                <label class="form-label fw-semibold">Danh mục</label>
                <select name="MaLoai" class="form-select mb-3" required>
                    <option value="">Chọn danh mục</option>
                    @foreach($loaiSP as $loai)
                        <option value="{{ $loai->MaLoai }}" {{ $sanPham->MaLoai == $loai->MaLoai ? 'selected' : '' }}>
                            {{ $loai->TenLoai }}
                        </option>
                    @endforeach
                </select>

                <label class="form-label fw-semibold">Giá bán (VNĐ)</label>
                <input name="Gia"
                       class="form-control mb-3 no-spin"
                       type="number" min="0"
                       placeholder="Ví dụ: 1500000" required
                       value="{{ $sanPham->Gia }}" />

                <label class="form-label fw-semibold">Số lượng</label>
                <input name="SoLuong"
                       class="form-control mb-3 no-spin"
                       type="number" min="1"
                       placeholder="Nhập số lượng" required
                       value="{{ $sanPham->SoLuong }}" />

                <!-- ẢNH BÌA -->
                <label class="form-label fw-semibold mt-2">Ảnh bìa sản phẩm (Chọn file mới nếu muốn thay đổi)</label>

                <input type="file" name="anhBiaMoi" class="form-control mb-2" accept="image/*" onchange="previewCover(this)" />

                <small class="text-muted mb-2 d-block">Ảnh bìa hiện tại:</small>

                <!-- Hiển thị ảnh bìa cũ hoặc ảnh mới sau khi chọn -->
                @if (!empty($anhBiaCu))
                    <img id="previewCoverImg"
                         src="{{ asset('Content/Images/' . $anhBiaCu) }}"
                         class="mt-2 rounded"
                         style="width:160px;height:auto;border:1px solid #eee;" />
                @else
                    <img id="previewCoverImg" class="mt-2 rounded d-none" style="width:160px;height:auto;border:1px solid #eee;" />
                @endif
            </div>

            <!-- CỘT PHẢI -->
            <div class="col-md-6">

                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                <textarea name="MoTa" rows="6" class="form-control mb-3" placeholder="Mô tả tình trạng sản phẩm, phụ kiện đi kèm..." required>{{ $sanPham->MoTa }}</textarea>

                <!-- ẢNH CHI TIẾT -->
                <label class="form-label fw-semibold">Ảnh chi tiết sản phẩm (Chọn file mới để THAY THẾ toàn bộ ảnh chi tiết cũ)</label>
                <input type="file" name="files[]" class="form-control" multiple accept="image/*" onchange="previewImages(this)" />

                <small class="text-muted">Chọn file mới để thay thế toàn bộ ảnh chi tiết cũ. (Nếu không chọn, ảnh cũ sẽ giữ nguyên).</small>

                <div id="previewList" class="mt-3 d-flex flex-wrap gap-2">
                    <!-- Hiển thị ảnh chi tiết cũ -->
                    @foreach ($anhChiTietCu as $anh)
                        <img src="{{ asset('Content/Images/' . $anh->URLAnh) }}"
                             class="rounded border"
                             style="width:100px;height:100px;object-fit:cover;" />
                    @endforeach
                </div>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-warning px-4 py-2 fw-semibold">
                    <i class="bi bi-upload"></i> Cập nhật tin bán
                </button>
                <a href="{{ route('sanpham.chitiet', ['id' => $sanPham->MaSP]) }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold ms-2">
                    Hủy bỏ
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
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
