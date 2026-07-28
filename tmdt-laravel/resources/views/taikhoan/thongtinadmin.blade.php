@extends('shared._layoutadmin')
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
                                 src="{{ str_starts_with(($user->AnhDaiDien ?? 'Default.jpg'), 'http') ? ($user->AnhDaiDien ?? 'Default.jpg') : asset('Content/Avatars/' . ($user->AnhDaiDien ?? 'Default.jpg')) }}"
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
                            <div class="input-group">
                                <input type="email" id="currentEmail" class="form-control bg-light" readonly value="{{ $user->Email }}" placeholder="Chưa có email" />
                                @if(empty($user->Email))
                                    <button class="btn btn-outline-success" type="button" onclick="startAddEmailFlow()">
                                        <i class="fa-solid fa-plus-circle me-1"></i> Thêm Email
                                    </button>
                                @else
                                    <button class="btn btn-outline-primary" type="button" onclick="startChangeEmailFlow()">
                                        <i class="fa-solid fa-envelope-circle-check me-1"></i> Đổi Email
                                    </button>
                                @endif
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function startChangeEmailFlow() {
    Swal.fire({
        title: 'Đang gửi mã OTP...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch('{{ route("taikhoan.sendOtpEmail") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if(!data.success) {
            Swal.fire('Lỗi', data.message, 'error');
            return;
        }
        showOtpPopup();
    })
    .catch(() => Swal.fire('Lỗi', 'Có lỗi kết nối', 'error'));
}

function showOtpPopup() {
    let timeLeft = 60;
    let timerInterval;

    Swal.fire({
        title: 'Nhập mã OTP',
        html: `
            Mã OTP 4 số đã được gửi về email hiện tại.<br>
            Vui lòng kiểm tra hộp thư của bạn.<br><br>
            <b id="swal-timer" style="color: #dc3545; font-size: 24px;">60s</b><br><br>
            <input type="text" id="otp-input" class="swal2-input" placeholder="Nhập mã 4 số" maxlength="4" style="text-align: center; font-size: 20px; letter-spacing: 5px;">
        `,
        confirmButtonText: 'Xác nhận',
        confirmButtonColor: '#0d6efd',
        allowOutsideClick: false,
        didOpen: () => {
            const b = Swal.getHtmlContainer().querySelector('#swal-timer');
            timerInterval = setInterval(() => {
                timeLeft -= 1;
                b.textContent = timeLeft + 's';
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    Swal.fire('Hết hạn', 'Mã OTP đã hết hạn, vui lòng gửi lại.', 'warning');
                }
            }, 1000);
        },
        willClose: () => { clearInterval(timerInterval); },
        preConfirm: () => {
            const otp = document.getElementById('otp-input').value;
            if(!otp || otp.length !== 4) {
                Swal.showValidationMessage('Vui lòng nhập đủ 4 số');
                return false;
            }
            return fetch('{{ route("taikhoan.verifyOtpEmail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(r => r.json())
            .then(data => {
                if(!data.success) { throw new Error(data.message); }
                return true;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
                return false;
            });
        }
    }).then(result => {
        if(result.isConfirmed) {
            showNewEmailPopup();
        }
    });
}

function showNewEmailPopup() {
    Swal.fire({
        title: 'Nhập Email mới',
        input: 'email',
        inputPlaceholder: 'vidu@gmail.com',
        confirmButtonText: 'Lưu thay đổi',
        confirmButtonColor: '#198754',
        allowOutsideClick: false,
        preConfirm: (newEmail) => {
            if(!newEmail) {
                Swal.showValidationMessage('Vui lòng nhập email');
                return false;
            }
            return fetch('{{ route("taikhoan.updateNewEmail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ new_email: newEmail })
            })
            .then(r => r.json())
            .then(data => {
                if(!data.success) { throw new Error(data.message); }
                return true;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
                return false;
            });
        }
    }).then(result => {
        if(result.isConfirmed) {
            Swal.fire('Thành công!', 'Email của bạn đã được cập nhật.', 'success')
            .then(() => location.reload());
        }
    });
}

function startAddEmailFlow() {
    Swal.fire({
        title: 'Nhập Email mới',
        input: 'email',
        inputPlaceholder: 'vidu@gmail.com',
        confirmButtonText: 'Gửi mã xác nhận',
        confirmButtonColor: '#0d6efd',
        showCancelButton: true,
        cancelButtonText: 'Hủy',
        allowOutsideClick: false,
        preConfirm: (newEmail) => {
            if(!newEmail) {
                Swal.showValidationMessage('Vui lòng nhập email');
                return false;
            }
            return fetch('{{ route("taikhoan.sendOtpAddEmail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ new_email: newEmail })
            })
            .then(r => r.json())
            .then(data => {
                if(!data.success) { throw new Error(data.message); }
                return true;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
                return false;
            });
        }
    }).then(result => {
        if(result.isConfirmed) {
            showOtpAddPopup();
        }
    });
}

function showOtpAddPopup() {
    let timeLeft = 60;
    let timerInterval;

    Swal.fire({
        title: 'Nhập mã OTP',
        html: `
            Mã OTP 4 số đã được gửi về email bạn vừa nhập.<br>
            Vui lòng kiểm tra hộp thư của bạn.<br><br>
            <b id="swal-timer-add" style="color: #dc3545; font-size: 24px;">60s</b><br><br>
            <input type="text" id="otp-input-add" class="swal2-input" placeholder="Nhập mã 4 số" maxlength="4" style="text-align: center; font-size: 20px; letter-spacing: 5px;">
        `,
        confirmButtonText: 'Xác nhận',
        confirmButtonColor: '#198754',
        allowOutsideClick: false,
        didOpen: () => {
            const b = Swal.getHtmlContainer().querySelector('#swal-timer-add');
            timerInterval = setInterval(() => {
                timeLeft -= 1;
                b.textContent = timeLeft + 's';
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    Swal.fire('Hết hạn', 'Mã OTP đã hết hạn.', 'warning');
                }
            }, 1000);
        },
        willClose: () => { clearInterval(timerInterval); },
        preConfirm: () => {
            const otp = document.getElementById('otp-input-add').value;
            if(!otp || otp.length !== 4) {
                Swal.showValidationMessage('Vui lòng nhập đủ 4 số');
                return false;
            }
            return fetch('{{ route("taikhoan.verifyOtpAddEmail") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(r => r.json())
            .then(data => {
                if(!data.success) { throw new Error(data.message); }
                return true;
            })
            .catch(err => {
                Swal.showValidationMessage(err.message);
                return false;
            });
        }
    }).then(result => {
        if(result.isConfirmed) {
            Swal.fire('Thành công!', 'Email của bạn đã được thêm.', 'success')
            .then(() => location.reload());
        }
    });
}
</script>
@endsection
