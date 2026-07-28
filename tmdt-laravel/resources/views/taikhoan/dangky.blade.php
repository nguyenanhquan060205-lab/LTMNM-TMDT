@extends('shared._layout')
@section('title', 'Đăng ký')

@section('content')
<style>
    /* Nền chung */
    .auth-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Thẻ Glassmorphism */
    .glass-card {
        background: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0,0,0,0.05) !important;
        border-radius: 24px !important;
        overflow: hidden;
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 650px; /* Lớn hơn form đăng nhập vì có nhiều field */
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 60%);
        transform: rotate(-30deg);
        pointer-events: none;
    }

    /* Input hiện đại */
    .modern-input {
        background: rgba(255, 255, 255, 0.6) !important;
        border: 2px solid transparent !important;
        border-radius: 14px !important;
        padding: 0.8rem 1.2rem !important;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    
    .modern-input:focus {
        background: white !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15), inset 0 2px 4px rgba(0,0,0,0.02) !important;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.9rem;
        margin-left: 0.4rem;
        margin-bottom: 0.3rem;
    }

    /* Nút gradient hiện đại */
    .btn-gradient {
        background-color: #0d6efd;
        color: white;
        border: none;
        border-radius: 14px;
        padding: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        background-color: #0b5ed7;
        color: white;
    }

    /* Trang trí icon */
    .auth-icon {
        color: #0d6efd;
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .auth-link {
        color: #0d6efd;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .auth-link:hover {
        color: #0b5ed7;
        text-decoration: underline;
    }
</style>

<div class="auth-wrapper">
    <div class="glass-card">
        <div class="p-4 p-sm-5">
            <div class="text-center">
                <i class="fa-solid fa-user-plus auth-icon"></i>
                <h3 class="fw-bold text-dark mb-4" style="letter-spacing: -0.5px;">Tạo tài khoản mới</h3>
            </div>

            @if (session('error') || session('Error'))
                <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') ?? session('Error') }}
                </div>
            @endif
            @if (session('success') || session('Success'))
                <div class="alert alert-success" style="border-radius: 12px; font-size: 0.9rem;">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') ?? session('Success') }}
                </div>
            @endif

            <form method="post" action="{{ url('/taikhoan/dangky') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-regular fa-id-card me-2 text-muted"></i>Họ tên</label>
                        <input class="form-control modern-input" name="HoTen" placeholder="Nguyễn Văn A" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-solid fa-user me-2 text-muted"></i>Tài khoản</label>
                        <input class="form-control modern-input" name="TaiKhoan" placeholder="Tên đăng nhập" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-solid fa-lock me-2 text-muted"></i>Mật khẩu</label>
                        <div class="position-relative">
                            <input class="form-control modern-input pe-5" name="MatKhau" id="matkhau_reg" type="password" placeholder="Mật khẩu (≥10 ký tự, có ký tự đặc biệt)" minlength="10" pattern=".*[\W_].*" title="Mật khẩu phải có ít nhất 10 ký tự và chứa ít nhất 1 ký tự đặc biệt" required />
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted" onclick="togglePassword('matkhau_reg', this)" tabindex="-1" style="border: none; background: transparent; padding-right: 15px;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-solid fa-lock me-2 text-muted"></i>Xác nhận mật khẩu</label>
                        <div class="position-relative">
                            <input class="form-control modern-input pe-5" name="XacNhanMatKhau" id="matkhau_conf" type="password" placeholder="Nhập lại mật khẩu" minlength="10" pattern=".*[\W_].*" required />
                            <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted" onclick="togglePassword('matkhau_conf', this)" tabindex="-1" style="border: none; background: transparent; padding-right: 15px;">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-solid fa-envelope me-2 text-muted"></i>Email</label>
                        <input class="form-control modern-input" name="Email" type="email" placeholder="example@gmail.com" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fa-solid fa-phone me-2 text-muted"></i>Số điện thoại</label>
                        <input class="form-control modern-input" name="SDT" placeholder="090xxxxxxx" />
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="fa-solid fa-location-dot me-2 text-muted"></i>Địa chỉ</label>
                    <textarea class="form-control modern-input" name="DiaChi" rows="2" placeholder="Nhập địa chỉ của bạn..."></textarea>
                </div>

                <button type="submit" class="btn btn-gradient w-100 mt-2">
                    Tạo tài khoản <i class="fa-solid fa-check ms-2"></i>
                </button>
            </form>

            <div class="mt-4 mb-4 position-relative text-center">
                <hr style="border-color: #cbd5e1;">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted" style="font-size: 0.85rem; border-radius: 4px;">Hoặc đăng ký nhanh bằng</span>
            </div>

            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('social.redirect', 'google') }}" class="btn w-50 d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; border: 1px solid #e2e8f0; background-color: #fff; color: #ea4335; font-weight: 500; transition: all 0.2s;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google" style="width: 18px; height: 18px;"> Google
                </a>
                <a href="{{ route('social.redirect', 'facebook') }}" class="btn w-50 d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; border: 1px solid #e2e8f0; background-color: #fff; color: #1877f2; font-weight: 500; transition: all 0.2s;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" alt="Facebook" style="width: 18px; height: 18px;"> Facebook
                </a>
            </div>

            <p class="text-center mt-4 mb-0" style="color: #718096; font-size: 0.95rem;">
                Đã có tài khoản? <a href="{{ url('/taikhoan/dangnhap') }}" class="auth-link">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
