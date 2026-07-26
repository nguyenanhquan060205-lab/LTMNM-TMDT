# 🛒 Hệ Thống Thương Mại Điện Tử Mini (Laravel)

Đây là dự án hệ thống thương mại điện tử được xây dựng bằng framework **Laravel**, hỗ trợ các chức năng mua bán, giỏ hàng, thanh toán, quản lý đơn hàng và đánh giá sản phẩm.

## 📋 Yêu cầu hệ thống

Để khởi chạy dự án, máy tính của bạn cần cài đặt sẵn các phần mềm sau:
- **PHP** (>= 8.2)
- **Composer**
- **Node.js** & **npm**
- **MySQL** (có thể dùng XAMPP, WAMP, hoặc Laragon)

---

## 🚀 Hướng dẫn cài đặt và khởi chạy

Thực hiện lần lượt các bước dưới đây để thiết lập và chạy dự án trên môi trường Local:

### 1. Khởi tạo Cơ sở dữ liệu (Database)
1. Khởi động **MySQL** (bật dịch vụ MySQL trên XAMPP/Laragon).
2. Mở trình quản lý database (như phpMyAdmin, DBeaver, hoặc MySQL Workbench).
3. Tạo một database mới với tên: `tmdt_new` (hoặc tên tuỳ chỉnh, sau đó cập nhật trong file `.env`).
4. Import file SQL chứa dữ liệu của hệ thống vào database vừa tạo.

### 2. Cài đặt các thư viện (Dependencies)
Mở Terminal/Command Prompt, trỏ đường dẫn vào thư mục gốc của project (thư mục `tmdt-laravel`) và chạy các lệnh sau:

Cài đặt các gói PHP thông qua Composer:
```bash
composer install
```

Cài đặt các thư viện Frontend (CSS/JS) thông qua npm:
```bash
npm install
```

### 3. Cấu hình môi trường (.env)
Nếu project chưa có file `.env`, bạn hãy copy từ file `.env.example`:
```bash
copy .env.example .env
```
Mở file `.env` và kiểm tra lại cấu hình kết nối database sao cho khớp với thiết lập ở Bước 1:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tmdt_new
DB_USERNAME=root
DB_PASSWORD=
```
Sau đó, tạo khoá bảo mật cho ứng dụng:
```bash
php artisan key:generate
```

### 4. Khởi chạy Ứng dụng (Backend & Frontend)

Để project hoạt động đầy đủ cả giao diện và xử lý logic, bạn cần mở **2 cửa sổ Terminal** và chạy song song 2 lệnh sau:

**Terminal 1 (Chạy server Backend - PHP Laravel):**
```bash
php artisan serve --port=8001
```
*(Lưu ý: Port 8001 được sử dụng theo cấu hình hiện tại của dự án. Nếu muốn dùng port mặc định 8000, bạn có thể bỏ `--port=8001`)*

**Terminal 2 (Chạy trình biên dịch Frontend - Vite):**
```bash
npm run dev
```

### 5. Truy cập Giao diện
Sau khi cả 2 server đều báo chạy thành công, hãy mở trình duyệt web và truy cập vào địa chỉ:
👉 **[http://127.0.0.1:8001](http://127.0.0.1:8001)** hoặc **[http://localhost:8001](http://localhost:8001)**

---

## 🛠 Cấu trúc dự án cơ bản

- `app/Http/Controllers/`: Nơi chứa các file xử lý logic (VD: `SanPhamController`, `GioHangController`, `HoaDonController`...)
- `app/Models/`: Nơi định nghĩa các Model tương tác với Database (VD: `SanPham`, `NguoiDung`, `HoaDon`...)
- `resources/views/`: Chứa các giao diện (blade template) của website.
  - `/sanpham`: Giao diện danh sách & chi tiết sản phẩm.
  - `/giohang`: Giao diện giỏ hàng.
  - `/taikhoan`: Giao diện đăng nhập, đăng ký, lịch sử mua hàng.
- `public/Content/` & `public/Scripts/`: Nơi chứa các file CSS (như `site.css`) và Javascript tĩnh.
