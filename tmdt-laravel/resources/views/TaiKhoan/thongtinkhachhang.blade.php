@extends('Shared._Layout')
@section('title', 'Thông tin khách hàng')

@section('content')
<section class="container my-5">
    <h3 class="fw-bold mb-4">Thông tin khách hàng</h3>

    @if (session('Success'))
        <div class="alert alert-success">{{ session('Success') }}</div>
    @endif
    @if (session('Error'))
        <div class="alert alert-danger">{{ session('Error') }}</div>
    @endif

    <form action="{{ url('/taikhoan/capnhatthongtin') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />

        <div class="row">
            <!-- Ảnh đại diện -->
            <div class="col-md-3 text-center">
                <div class="avatar-upload position-relative mb-3">
                    <img id="previewAvatar"
                         src="{{ url('Content/Avatars/' . ($targetUser->AnhDaiDien ?? 'default.jpg')) }}"
                         class="rounded-circle shadow-sm avatar-img"
                         style="width: 130px; height: 130px; object-fit: cover; border: 3px solid #f1f1f1;" />

                    <div class="avatar-overlay d-flex align-items-center justify-content-center">
                        <i class="fa fa-camera fa-lg text-white"></i>
                    </div>
                </div>

                <!-- Nút chọn file -->
                <input type="file" id="fileUpload" name="fileUpload" class="d-none" accept="image/*" onchange="previewFile(event)" />
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="document.getElementById('fileUpload').click()">
                    <i class="fa fa-camera me-1"></i> Đổi ảnh đại diện
                </button>
            </div>

            <!-- Thông tin -->
            <div class="col-md-9">
                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="HoTen" class="form-control" value="{{ $targetUser->HoTen }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="Email" class="form-control" value="{{ $targetUser->Email }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="SDT" class="form-control" value="{{ $targetUser->SDT }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control" value="{{ $targetUser->DiaChi }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="GioiTinh" class="form-control">
                        <option value="">Chọn giới tính</option>
                        <option value="Nam" {{ $targetUser->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nữ" {{ $targetUser->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khác" {{ $targetUser->GioiTinh == 'Khác' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#modalDoiMatKhau">
                    Cập nhật mật khẩu
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalChuyenKhoan">
                    Cập nhật thông tin chuyển khoản
                </button>
            </div>
        </div>
    </form>
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
                <form action="{{ url('/taikhoan/capnhatmatkhau') }}" method="POST">
                    @csrf
                    <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="MatKhauHienTai" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="MatKhauMoi" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="XacNhanMatKhauMoi" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                </form>
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

            <form action="{{ url('/taikhoan/capnhatchuyenkhoan') }}" method="POST">
                @csrf
                <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="SoTaiKhoan" class="form-label">Số tài khoản</label>
                        <input type="text" name="SoTaiKhoan" class="form-control" placeholder="Nhập số tài khoản" value="{{ $targetUser->SoTaiKhoan }}" />
                    </div>
                    <div class="mb-3">
                        <label for="TenNganHang" class="form-label">Ngân hàng</label>
                        <select name="TenNganHang" class="form-control">
                            <option value="">Chọn ngân hàng</option>
                            @php
                                $nganHangs = ["MB Bank", "Vietcombank", "Techcombank", "ACB", "VietinBank", "Agribank", "BIDV", "VPBank", "TPBank", "Sacombank"];
                            @endphp
                            @foreach ($nganHangs as $nh)
                                <option value="{{ $nh }}" {{ $targetUser->TenNganHang == $nh ? 'selected' : '' }}>{{ $nh }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewFile(event) {
        const img = document.getElementById("previewAvatar");
        const file = event.target.files[0];
        if (file) img.src = URL.createObjectURL(file);
    }
</script>
@endsection
