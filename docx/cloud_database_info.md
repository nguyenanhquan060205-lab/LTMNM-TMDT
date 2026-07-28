# 🚀 Thông tin Triển khai Đám mây (Cloud Deployment)

Tài liệu này lưu trữ các thông số quan trọng về máy chủ đám mây của dự án TMDT. Mọi thao tác cấu hình đều được thiết lập để tối ưu hóa cho môi trường Linux và Cloud.

## 1. Cơ sở dữ liệu (Aiven Cloud Database)
Dự án đã được chuyển đổi thành công từ cơ sở dữ liệu nội bộ (Laragon MySQL) sang máy chủ cơ sở dữ liệu đám mây của **Aiven** (Singapore / Asia Pacific).

- **Loại Database:** MySQL (TCP/IP)
- **Hostname:** `db-doan-tmdt-nguyenanhquan060205-0664.h.aivencloud.com`
- **Port:** `10427`
- **User:** `avnadmin`
- **Password:** `[ĐÃ ẨN ĐỂ BẢO MẬT - Vui lòng nhắn tin trực tiếp cho tôi để xin Password]`
- **Tên Database:** `defaultdb`

> **Lưu ý:**
> Để xem và chỉnh sửa dữ liệu trực tiếp trên Cloud, bạn có thể sử dụng phần mềm **HeidiSQL** và tạo một kết nối mới với các thông số bên trên. Đừng dùng Localhost nữa nhé!

## 2. Các sửa đổi để tương thích với Cloud
Do môi trường máy chủ Linux phân biệt nghiêm ngặt chữ hoa và chữ thường, mã nguồn đã được điều chỉnh lại để hoạt động trơn tru:
1. Đổi tên toàn bộ bảng thành chữ thường (lowercase) trực tiếp trên database của Aiven.
2. Xóa và tạo lại toàn bộ **6 Database Triggers** để sử dụng tên bảng chữ thường.
3. Cập nhật thuộc tính `$table` trong tất cả các `Models` của Laravel sang chữ thường.
4. Giao diện tin nhắn (Chat) đã được tinh chỉnh lại UI/UX, canh lề trái hoàn hảo và hiển thị thẻ sản phẩm mượt mà (chuẩn Zalo/Messenger).

## 3. Web Hosting (Sắp triển khai)
- **Nền tảng:** Render (Sắp cấu hình)
- **Repo Github:** (Sắp có)
- **URL Website:** (Sắp có)

---
*Tài liệu này được tạo tự động để hỗ trợ báo cáo đồ án của bạn.*
