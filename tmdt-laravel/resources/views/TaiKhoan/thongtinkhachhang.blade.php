{{-- @model ThuongMaiDienTu_DoAn.Models.NGUOIDUNG --}}
@extends('shared._layout')

<section class="container my-5">
    <h3 class="fw-bold mb-4">Thông tin khách hàng</h3>

    @if (TempData["Success"] != null)
    {
        <div class="alert alert-success">@TempData["Success"]</div>
    }
    @if (TempData["Error"] != null)
    {
        <div class="alert alert-danger">@TempData["Error"]</div>
    }

    @using (Html.BeginForm("CapNhatThongTin", "TaiKhoan", FormMethod.Post, new { enctype = "multipart/form-data" }))
    {
        @Html.AntiForgeryToken()

        @Html.HiddenFor(m => m.MaKH)

        <div class="row">
            <!-- Ảnh đại diện -->
            <div class="col-md-3 text-center">
                <div class="avatar-upload position-relative mb-3">
                    <img id="previewAvatar"
                         src="{{ asset('Content/') }}/Avatars/@(Model.AnhDaiDien ?? "default.jpg")"
                         class="rounded-circle shadow-sm avatar-img"
                         style="width: 130px; height: 130px; object-fit: cover; border: 3px solid #f1f1f1;" />

                    <div class="avatar-overlay d-flex align-items-center justify-content-center">
                        <i class="fa fa-camera fa-lg text-white"></i>
                    </div>
                </div>

                <!-- Nút chọn file -->
                <input type="file" id="fileUpload" name="fileUpload"
                       class="d-none" accept="image/*" onchange="previewFile(event)" />
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3"
                        onclick="document.getElementById('fileUpload').click()">
                    <i class="fa fa-camera me-1"></i> Đổi ảnh đại diện
                </button>
            </div>


            <!-- Thông tin -->
            <div class="col-md-9">
                <div class="mb-3">
                    @Html.LabelFor(m => m.HoTen)
                    @Html.TextBoxFor(m => m.HoTen, new { @class = "form-control" })
                </div>
                <div class="mb-3">
                    @Html.LabelFor(m => m.Email)
                    @Html.TextBoxFor(m => m.Email, new { @class = "form-control" })
                </div>
                <div class="mb-3">
                    @Html.LabelFor(m => m.SDT, "Số điện thoại")
                    @Html.TextBoxFor(m => m.SDT, new { @class = "form-control" })
                </div>
                <div class="mb-3">
                    @Html.LabelFor(m => m.DiaChi)
                    @Html.TextBoxFor(m => m.DiaChi, new { @class = "form-control" })
                </div>
                <div class="mb-3">
                    @Html.LabelFor(m => m.GioiTinh)
                    @Html.DropDownListFor(m => m.GioiTinh,
                        new SelectList(new[] { "Nam", "Nữ", "Khác" }, Model.GioiTinh),
                        "Chọn giới tính", new { @class = "form-control" })
                </div>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <!-- Nút mở modal -->
                <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#modalDoiMatKhau">
                    Cập nhật mật khẩu
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalChuyenKhoan">
                    Cập nhật thông tin chuyển khoản
                </button>
            </div>
        </div>
    }
</section>

<!-- Modal Đổi mật khẩu -->
<div class="modal fade" id="modalDoiMatKhau" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @using (Html.BeginForm("CapNhatMatKhau", "TaiKhoan", FormMethod.Post))
                {
                    @Html.AntiForgeryToken()
                    @Html.HiddenFor(m => m.MaKH)

                    <div class="mb-3">
                        @Html.Label("Mật khẩu hiện tại")
                        <input type="password" name="MatKhauHienTai" class="form-control" />
                    </div>
                    <div class="mb-3">
                        @Html.Label("Mật khẩu mới")
                        <input type="password" name="MatKhauMoi" class="form-control" />
                    </div>
                    <div class="mb-3">
                        @Html.Label("Xác nhận mật khẩu mới")
                        <input type="password" name="XacNhanMatKhauMoi" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                }
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật thông tin chuyển khoản -->
<div class="modal fade" id="modalChuyenKhoan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật thông tin chuyển khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            @using (Html.BeginForm("CapNhatChuyenKhoan", "TaiKhoan", FormMethod.Post))
            {
                @Html.AntiForgeryToken()
                @Html.HiddenFor(m => m.MaKH)

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="SoTaiKhoan" class="form-label">Số tài khoản</label>
                        @Html.TextBoxFor(m => m.SoTaiKhoan, new { @class = "form-control", placeholder = "Nhập số tài khoản" })
                    </div>
                    <div class="mb-3">
                        <label for="TenNganHang" class="form-label">Ngân hàng</label>
                        @Html.DropDownListFor(m => m.TenNganHang,
                            new SelectList(new[] {
                                "MB Bank",
                                "Vietcombank",
                                "Techcombank",
                                "ACB",
                                "VietinBank",
                                "Agribank",
                                "BIDV",
                                "VPBank",
                                "TPBank",
                                "Sacombank"
                            }, Model.TenNganHang),
                            "Chọn ngân hàng", new { @class = "form-control" })
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            }
        </div>
    </div>
</div>

@section('Scripts')

    <script>
        function previewFile(event) {
            const img = document.getElementById("previewAvatar");
            const file = event.target.files[0];
            if (file) img.src = URL.createObjectURL(file);
        }
        $("#btnCapNhatChuyenKhoan").click(function () {
            $("#modalChuyenKhoan").modal("show");
        });
    </script>
}
