{{-- @model ThuongMaiDienTu_DoAn.Models.NGUOIDUNG --}}
@extends('shared._layoutAdmin')

<div class="container-fluid px-4">

    @if (TempData["Success"] != null)
    {
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>@TempData["Success"]
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    }
    @if (TempData["Error"] != null)
    {
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>@TempData["Error"]
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    }

    @using (Html.BeginForm("CapNhatThongTin", "TaiKhoan", FormMethod.Post, new { enctype = "multipart/form-data" }))
    {
        @Html.AntiForgeryToken()
        @Html.HiddenFor(m => m.MaKH)

        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0 shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">?nh d?i di?n</div>
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block">
                            <img id="previewAvatar"
                                 src="{{ asset('Content/') }}/Avatars/@(Model.AnhDaiDien ?? "default.jpg")"
                                 class="rounded-circle img-thumbnail"
                                 style="width: 160px; height: 160px; object-fit: cover;" />

                            <label for="fileUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" style="cursor: pointer; width: 40px; height: 40px;">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                        </div>

                        <div class="small font-italic text-muted mb-4 mt-2">JPG ho?c PNG không quá 5MB</div>

                        <input type="file" id="fileUpload" name="fileUpload" class="d-none" accept="image/*" onchange="previewFile(event)" />

                        <h5 class="fw-bold text-dark">$Model.HoTen</h5>
                        <span class="badge bg-info text-dark">Qu?n tr? viên (Admin)</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Chi ti?t tài kho?n</div>
                    <div class="card-body">
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">H? và tên</label>
                                @Html.TextBoxFor(m => m.HoTen, new { @class = "form-control", placeholder = "Nh?p h? tên" })
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">S? di?n tho?i</label>
                                @Html.TextBoxFor(m => m.SDT, new { @class = "form-control", placeholder = "Nh?p s? di?n tho?i" })
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1 fw-bold">Email (Tên dang nh?p)</label>
                            @Html.TextBoxFor(m => m.Email, new { @class = "form-control bg-light", @readonly = "readonly" })
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Gi?i tính</label>
                                @Html.DropDownListFor(m => m.GioiTinh,
                                         new SelectList(new[] { "Nam", "N?", "Khác" }, Model.GioiTinh),
                                         "Ch?n gi?i tính", new { @class = "form-select" })
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Ð?a ch?</label>
                                @Html.TextBoxFor(m => m.DiaChi, new { @class = "form-control", placeholder = "Nh?p d?a ch?" })
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-key me-1"></i> Ð?i m?t kh?u
                            </button>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-save me-1"></i> Luu thay d?i
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    }
</div>

@section('Scripts')

    <script>function previewFile(event) {
            const img = document.getElementById("previewAvatar");
            const file = event.target.files[0];
            if (file) img.src = URL.createObjectURL(file);
        }</script>
}
