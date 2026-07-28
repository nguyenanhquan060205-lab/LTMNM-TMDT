@extends('Shared._Layout')
@section('title', 'Thông tin khách hàng')

@section('content')
<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h3 class="fw-bold mb-4 text-dark text-center">Hồ sơ cá nhân</h3>

            @if (session('success'))
                <div class="alert alert-success rounded-3 shadow-sm border-0 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm border-0" style="border-radius: 20px;">
                <div class="p-4 p-md-5">
                    <form action="{{ url('/taikhoan/capnhatthongtin') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />

                        <div class="row">
                            <!-- Ảnh đại diện -->
                            <div class="col-md-4 d-flex flex-column align-items-center mb-4 mb-md-0 border-end-md">
                                <div class="avatar-upload position-relative mb-4">
                                    <img id="previewAvatar"
                                         src="{{ url('Content/Avatars/' . ($targetUser->AnhDaiDien ?? 'default.jpg')) }}"
                                         class="rounded-circle shadow-sm avatar-img"
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;" />
                                </div>

                                <input type="file" id="fileUpload" name="fileUpload" class="d-none" accept="image/*" onchange="previewFile(event)" />
                                <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-semibold mb-3" onclick="document.getElementById('fileUpload').click()" style="transition: all 0.3s;">
                                    <i class="fa fa-camera me-2"></i> Đổi ảnh đại diện
                                </button>
                                <p class="text-muted small text-center mb-0">Hỗ trợ định dạng JPG, PNG hoặc GIF.<br>Dung lượng tối đa 5MB.</p>
                            </div>

                            <!-- Thông tin -->
                            <div class="col-md-8 ps-md-4">
                                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Thông tin liên hệ</h5>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-semibold small">Họ và tên</label>
                                        <input type="text" name="HoTen" class="form-control form-control-lg border-0 bg-light rounded-3" value="{{ $targetUser->HoTen }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted fw-semibold small">Số điện thoại</label>
                                        <input type="text" name="SDT" class="form-control form-control-lg border-0 bg-light rounded-3" value="{{ $targetUser->SDT }}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted fw-semibold small">Email</label>
                                        <input type="email" name="Email" class="form-control form-control-lg border-0 bg-light rounded-3" value="{{ $targetUser->Email }}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted fw-semibold small">Địa chỉ</label>
                                        <input type="text" name="DiaChi" class="form-control form-control-lg border-0 bg-light rounded-3" value="{{ $targetUser->DiaChi }}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted fw-semibold small">Giới tính</label>
                                        <select name="GioiTinh" class="form-select form-select-lg border-0 bg-light rounded-3">
                                            <option value="">Chọn giới tính</option>
                                            <option value="Nam" {{ $targetUser->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                            <option value="Nữ" {{ $targetUser->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                            <option value="Khác" {{ $targetUser->GioiTinh == 'Khác' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
                                    </button>
                                    <button type="button" class="btn btn-outline-dark rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalDoiMatKhau">
                                        <i class="fa-solid fa-lock me-2"></i> Đổi mật khẩu
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalChuyenKhoan">
                                        <i class="fa-solid fa-building-columns me-2"></i> Ngân hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Đổi mật khẩu -->
<div class="modal fade" id="modalDoiMatKhau" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark fs-4">Đổi mật khẩu</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ url('/taikhoan/capnhatmatkhau') }}" method="POST">
                    @csrf
                    <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small">Mật khẩu hiện tại</label>
                        <input type="password" name="MatKhauHienTai" class="form-control form-control-lg border-0 bg-light rounded-3" placeholder="Nhập mật khẩu cũ" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small">Mật khẩu mới</label>
                        <input type="password" name="MatKhauMoi" class="form-control form-control-lg border-0 bg-light rounded-3" placeholder="Nhập mật khẩu mới" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold small">Xác nhận mật khẩu mới</label>
                        <input type="password" name="XacNhanMatKhauMoi" class="form-control form-control-lg border-0 bg-light rounded-3" placeholder="Nhập lại mật khẩu mới" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">Xác nhận đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật thông tin chuyển khoản -->
<div class="modal fade" id="modalChuyenKhoan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark fs-4">Thông tin ngân hàng</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/taikhoan/capnhatchuyenkhoan') }}" method="POST">
                @csrf
                <input type="hidden" name="MaKH" value="{{ $targetUser->MaKH }}" />
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="TenNganHang" class="form-label text-muted fw-semibold small">Ngân hàng</label>
                        <select name="TenNganHang" class="form-select form-select-lg border-0 bg-light rounded-3">
                            <option value="">Chọn ngân hàng</option>
                            @php
                                $nganHangs = ["MB Bank", "Vietcombank", "Techcombank", "ACB", "VietinBank", "Agribank", "BIDV", "VPBank", "TPBank", "Sacombank"];
                            @endphp
                            @foreach ($nganHangs as $nh)
                                <option value="{{ $nh }}" {{ $targetUser->TenNganHang == $nh ? 'selected' : '' }}>{{ $nh }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="SoTaiKhoan" class="form-label text-muted fw-semibold small">Số tài khoản</label>
                        <input type="text" name="SoTaiKhoan" class="form-control form-control-lg border-0 bg-light rounded-3" placeholder="Nhập số tài khoản" value="{{ $targetUser->SoTaiKhoan }}" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8fafc;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border: 1px solid #0d6efd !important;
    }

    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #f1f5f9;
        }
    }
</style>

<script>
    function previewFile(event) {
        const img = document.getElementById("previewAvatar");
        const file = event.target.files[0];
        if (file) img.src = URL.createObjectURL(file);
    }
</script>
@endsection
