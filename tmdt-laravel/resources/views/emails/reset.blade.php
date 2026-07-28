<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - TechSecond</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #007bff; margin: 0; }
        .content { font-size: 16px; color: #333; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 25px; margin-top: 20px; background-color: #007bff; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TechSecond</h1>
        </div>
        <div class="content">
            <p>Chào <strong>{{ $name }}</strong>,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản TechSecond của bạn. Nếu bạn thực sự yêu cầu, hãy click vào nút bên dưới để thiết lập mật khẩu mới:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">Đặt Lại Mật Khẩu</a>
            </div>

            <p style="margin-top: 20px;">Nếu nút trên không hoạt động, bạn có thể copy và dán đường link sau vào trình duyệt:</p>
            <p style="word-break: break-all; color: #007bff;"><a href="{{ $url }}">{{ $url }}</a></p>

            <p>Nếu bạn không gửi yêu cầu này, vui lòng bỏ qua email và mật khẩu của bạn sẽ được giữ nguyên.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} TechSecond. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
