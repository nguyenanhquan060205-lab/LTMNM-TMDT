@extends('Shared._LayoutAdmin')
@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid px-4">

    @if (session('Success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>{{ session('Success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('Error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ url('/taikhoan/capnhatthongtin') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="MaKH" value="{{ $user->MaKH }}" />

        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0 shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Ảnh đại diện</div>
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block">
                            <img id="previewAvatar"
                                 src="{{ url('Content/Avatars/' . ($user->AnhDaiDien ?? 'default.jpg')) }}"
                                 class="rounded-circle img-thumbnail"
                                 style="width: 160px; height: 160px; object-fit: cover;" />

                            <label for="fileUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" style="cursor: pointer; width: 40px; height: 40px;">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                        </div>

                        <div class="small font-italic text-muted mb-4 mt-2">JPG hoặc PNG không quá 5MB</div>

                        <input type="file" id="fileUpload" name="fileUpload" class="d-none" accept="image/*" onchange="previewFile(event)" />

                        <h5 class="fw-bold text-dark">{{ $user->HoTen }}</h5>
                        <span class="badge bg-info text-dark">Quản trị viên (Admin)</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Chi tiết tài khoản</div>
                    <div class="card-body">
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Họ và tên</label>
                                <input type="text" name="HoTen" class="form-control" placeholder="Nhập họ tên" value="{{ $user->HoTen }}" />
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Số điện thoại</label>
                                <input type="text" name="SDT" class="form-control" placeholder="Nhập số điện thoại" value="{{ $user->SDT }}" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1 fw-bold">Email (Tên đăng nhập)</label>
                            <input type="email" name="Email" class="form-control bg-light" readonly value="{{ $user->Email }}" />
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Giới tính</label>
                                <select name="GioiTinh" class="form-select">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" {{ $user->GioiTinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ $user->GioiTinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ $user->GioiTinh == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1 fw-bold">Địa chỉ</label>
                                <input type="text" name="DiaChi" class="form-control" placeholder="Nhập địa chỉ" value="{{ $user->DiaChi }}" />
                            </div>
                        </div>

                        <hr class="my-4" />

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDoiMatKhauAdmin">
                                <i class="fa-solid fa-key me-1"></i> Đổi mật khẩu
                            </button>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Đổi mật khẩu Admin -->
<div class="modal fade" id="modalDoiMatKhauAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/taikhoan/capnhatmatkhau') }}" method="POST">
                    @csrf
                    <input type="hidden" name="MaKH" value="{{ $user->MaKH }}" />

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="MatKhauHienTai" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="MatKhauMoi" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="XacNhanMatKhauMoi" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                </form>
            </div>
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
