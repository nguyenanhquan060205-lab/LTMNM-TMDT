# Quy trình tích hợp Cloudinary lưu trữ ảnh trên Cloud (Laravel)

Tài liệu này ghi chú lại quá trình chuyển đổi kiến trúc lưu trữ ảnh của đồ án từ Local (lưu trên máy chủ) sang nền tảng lưu trữ đám mây Cloudinary. Việc này nhằm mục đích giữ lại hình ảnh không bị mất khi Deploy ứng dụng lên các nền tảng như Render.

## Bước 1: Đăng ký và cấu hình Cloudinary
1. Đăng ký tài khoản tại [Cloudinary.com](https://cloudinary.com).
2. Lấy 3 thông số cấu hình: **Cloud Name**, **API Key**, **API Secret**.
3. Cấu hình vào file `.env` của Laravel:
```env
CLOUDINARY_URL=cloudinary://[API_KEY]:[API_SECRET]@[CLOUD_NAME]
```

## Bước 2: Cài đặt SDK
Mở Terminal tại thư mục gốc của project và chạy lệnh cài đặt thư viện chính thức của Cloudinary dành cho PHP:
```bash
composer require cloudinary/cloudinary_php
```

## Bước 3: Xây dựng Service xử lý Cloud
Tạo một lớp trung gian `app/Services/CloudinaryService.php` để đóng gói toàn bộ logic gọi API. Điều này giúp code gọn gàng, tái sử dụng dễ dàng ở nhiều nơi.
```php
<?php
namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    protected static $configured = false;

    public static function upload($fileRealPath, $folder = 'images')
    {
        if (!self::$configured) {
            Configuration::instance(env('CLOUDINARY_URL'));
            self::$configured = true;
        }

        $upload = new UploadApi();
        $response = $upload->upload($fileRealPath, ['folder' => $folder]);
        return $response['secure_url']; // Trả về link trực tiếp (https) của ảnh trên mây
    }
}
```

## Bước 4: Chỉnh sửa các Controllers
Chuyển đổi toàn bộ logic upload file trong hệ thống từ hàm `move()` của Laravel (lưu ổ cứng) sang sử dụng CloudinaryService.

**Các file bị tác động:**
- `app/Http/Controllers/SanPhamController.php`: Sửa logic lúc người dùng đăng bán sản phẩm và cập nhật sản phẩm.
- `app/Http/Controllers/TaiKhoanController.php`: Sửa logic lúc người dùng thay đổi Avatar.
- `app/Http/Controllers/TinNhanController.php`: Sửa logic gửi hình ảnh trong khung Chat.
- `app/Http/Controllers/AdminController.php`: Ngăn chặn việc báo lỗi khi hệ thống cố gắng `unlink` (xoá file ổ cứng) với một bức ảnh đang nằm trên cloud.

**Ví dụ sửa code (SanPhamController):**
*Code cũ (Lưu ổ cứng):*
```php
$fileName = (string)Str::uuid() . '.' . $ext;
$file->move(public_path('Content/Images'), $fileName);
```
*Code mới (Lưu Cloudinary):*
```php
$fileName = \App\Services\CloudinaryService::upload($file->getRealPath(), 'Content/Images');
```

## Bước 5: Đồng bộ giao diện (Blade Views)
Sửa đồng loạt hơn 16 file giao diện (`.blade.php`) ở cả phần quản trị, trang chủ và chat. Code hiển thị sẽ được thêm một lớp logic kiểm tra để đảm bảo tương thích ngược với các ảnh mẫu cũ (đang lưu ở ổ cứng):

- Dùng hàm `str_starts_with($url, 'http')` để kiểm tra.
- **Nếu là ảnh cũ (Không có chữ http):** Hệ thống sẽ nối tên file với hàm `url()` hoặc `asset()` như cũ để lấy ảnh từ ổ cứng.
- **Nếu là ảnh mới (Có URL http từ Cloudinary):** In thẳng đường link đó ra trình duyệt.

**Ví dụ sửa mã Blade:**
*Code cũ:*
```html
<img src="{{ url('/Content/Images/' . $anh) }}" />
```
*Code mới:*
```html
<img src="{{ str_starts_with($anh, 'http') ? $anh : url('/Content/Images/' . $anh) }}" />
```

## Kết quả
- Ứng dụng giờ đây không phụ thuộc vào hệ thống ổ cứng (File System).
- Sẵn sàng 100% cho việc Deploy lên các nền tảng ảo hóa (Docker, Render, Heroku) mà không sợ bị reset/mất dữ liệu ảnh sau khi hệ thống khởi động lại.
