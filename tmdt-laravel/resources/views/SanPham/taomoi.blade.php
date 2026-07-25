@extends('Shared._Layout')
@section('title', 'Đăng tin bán hàng')

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
        border: 2px solid #ffc107;
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
        background: linear-gradient(90deg, #ffca2c, #ffc107);
        color: #000;
        transition: 0.2s;
    }

        .btn-submit:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #ffd953, #ffcd29);
        }

    .title-icon {
        color: #dc3545;
        font-size: 28px;
    }
</style>


<div class="container py-4">

    <h3 class="fw-bold mb-4">
        <i class="bi bi-megaphone-fill title-icon"></i> Đăng tin bán hàng
    </h3>

    <form action="{{ url('/sanpham/taomoi') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-box">

            <div class="row">

                <!-- CỘT TRÁI -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag-fill"></i> Tên sản phẩm
                    </label>
                    <input type="text" name="TenSP" class="form-control mb-3" placeholder="Nhập tên sản phẩm..." required="required" value="{{ old('TenSP') }}" />


                    <label class="form-label fw-semibold">
                        <i class="bi bi-grid-fill"></i> Danh mục
                    </label>
                    <select name="MaLoai" class="form-select mb-3" required="required">
                        <option value="">Chọn danh mục</option>
                        @foreach ($maLoai as $loai)
                            <option value="{{ $loai->MaLoai }}">{{ $loai->TenLoai }}</option>
                        @endforeach
                    </select>


                    <label class="form-label fw-semibold">
                        <i class="bi bi-cash-coin"></i> Giá bán (VNĐ)
                    </label>
                    <input name="Gia"
                           class="form-control mb-3 no-spin"
                           type="number" min="0" placeholder="Ví dụ: 1500000" required />


                    <label class="form-label fw-semibold">
                        <i class="bi bi-stack"></i> Số lượng
                    </label>
                    <input name="SoLuong"
                           class="form-control mb-3 no-spin"
                           type="number" min="1" placeholder="Nhập số lượng" required />


                    <!-- ẢNH BÌA -->
                    <label class="form-label fw-semibold mt-2">
                        <i class="bi bi-image-fill"></i> Ảnh bìa sản phẩm
                    </label>
                    <input type="file" name="files[]"
                           class="form-control mb-2"
                           accept="image/*"
                           required
                           onchange="previewCover(this)" />

                    <img id="previewCoverImg" class="d-none" style="width:180px;" />
                </div>


                <!-- CỘT PHẢI -->
                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        <i class="bi bi-journal-text"></i> Mô tả chi tiết
                    </label>
                    <textarea name="MoTa" rows="7" class="form-control mb-3" placeholder="Mô tả tình trạng sản phẩm, phụ kiện đi kèm..." required="required">{{ old('MoTa') }}</textarea>


                    <label class="form-label fw-semibold">
                        <i class="bi bi-images"></i> Ảnh chi tiết sản phẩm (nhiều ảnh)
                    </label>
                    <input type="file"
                           name="files[]"
                           class="form-control"
                           multiple
                           accept="image/*"
                           onchange="previewImages(this)" />

                    <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc.</small>

                    <div id="previewList" class="mt-3 d-flex flex-wrap gap-2"></div>
                </div>


                <!-- NÚT ĐĂNG -->
                <div class="col-12 mt-4 text-end">
                    <button class="btn btn-submit">
                        <i class="bi bi-cloud-upload-fill"></i> Đăng tin
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>


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
        preview.innerHTML = "";

        [...input.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList = "preview-thumb";
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection

