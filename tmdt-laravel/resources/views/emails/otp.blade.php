<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mã OTP xác thực thay đổi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color: #0d6efd; color: #ffffff; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Xác thực thay đổi Email</h1>
        </div>

        <!-- Content -->
        <div style="padding: 30px; color: #333333; line-height: 1.6;">
            <p style="font-size: 16px;">Xin chào <strong>{{ $name }}</strong>,</p>
            <p style="font-size: 16px;">Chúng tôi nhận được yêu cầu thay đổi địa chỉ Email cho tài khoản của bạn tại <strong>TechSecond</strong>. Đây là mã OTP bảo mật của bạn (có hiệu lực trong 60 giây):</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <span style="display: inline-block; padding: 15px 30px; font-size: 32px; font-weight: bold; color: #0d6efd; background-color: #f0f7ff; border: 2px dashed #0d6efd; border-radius: 8px; letter-spacing: 4px;">
                    {{ $otp }}
                </span>
            </div>

            <p style="font-size: 14px; color: #dc3545;"><strong>Cảnh báo:</strong> Nếu bạn không yêu cầu thay đổi Email, vui lòng bỏ qua thư này và kiểm tra lại bảo mật tài khoản ngay lập tức.</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 15px; text-align: center; color: #6c757d; font-size: 12px;">
            <p style="margin: 0;">&copy; {{ date('Y') }} TechSecond. Mọi bản quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
