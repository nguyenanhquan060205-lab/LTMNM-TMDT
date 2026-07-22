# Tài liệu bàn giao dự án TechSecond / Thương mại điện tử C2C

## 1. Tổng quan & Công nghệ sử dụng

### Mục đích chính

Dự án là một sàn thương mại điện tử C2C tên **TechSecond**, tập trung vào mua bán đồ cũ/đồ công nghệ giữa người dùng với nhau. Người dùng có thể đăng bán sản phẩm, tìm kiếm/lọc sản phẩm, thêm vào giỏ hàng, đặt hàng, theo dõi lịch sử mua hàng, xác nhận giao dịch, đánh giá sản phẩm, gửi khiếu nại và nhắn tin với người bán/admin. Admin có khu vực quản trị để quản lý tài khoản, sản phẩm, đơn hàng và khiếu nại.

Repo hiện có hai codebase:

- `ThuongMaiDienTu-DoAn`: ứng dụng ASP.NET MVC 5, có vẻ là bản gốc và đầy đủ nghiệp vụ hơn.
- `tmdt-laravel`: ứng dụng Laravel đang port/chuyển đổi từ bản ASP.NET MVC sang PHP/Laravel. Bản này đã có nhiều controller/view/model nhưng còn lỗi encoding, route, schema và dấu vết Razor.

### Công nghệ trong `ThuongMaiDienTu-DoAn`

Nguồn phiên bản: `ThuongMaiDienTu-DoAn.csproj`, `packages.config`, `Web.config`.

| Thành phần | Công nghệ / thư viện | Phiên bản |
|---|---|---:|
| Runtime | .NET Framework | 4.7.2 |
| Web framework | ASP.NET MVC | 5.2.9 |
| View engine | Razor / `.cshtml` | WebPages/Razor 3.2.9 |
| ORM | Entity Framework DB-first | 6.5.1 |
| Database | SQL Server | DB `TMDT`, connection `TMDTEntities` |
| Frontend CSS | Bootstrap | 5.2.3 |
| JavaScript | jQuery | 3.7.0 |
| Validation | jQuery Validation | 1.19.5 |
| Client validation | Microsoft jQuery Unobtrusive Validation | 3.2.11 |
| PDF | iTextSharp | 5.5.13.4 |
| JSON | Newtonsoft.Json | 13.0.3 |
| Build/compiler helper | Microsoft.CodeDom.Providers.DotNetCompilerPlatform | 2.0.1 |
| Khác | Modernizr, WebGrease, Antlr, BouncyCastle.Cryptography | xem `packages.config` |

Database ASP.NET dùng connection string Entity Framework DB-first:

- `data source=LAPTOP-3F26GI9M`
- `initial catalog=TMDT`
- `integrated security=True`
- model EDMX: `ThuongMaiDienTu-DoAn/Models/TMDT.edmx`

### Công nghệ trong `tmdt-laravel`

Nguồn phiên bản: `composer.json`, `composer.lock`, `package.json`, `config/database.php`.

| Thành phần | Công nghệ / thư viện | Phiên bản |
|---|---|---:|
| Runtime | PHP | `^8.3` |
| Web framework | Laravel | `^13.8`, lock `v13.20.0` |
| ORM | Eloquent | theo Laravel |
| Database | mặc định Laravel: SQLite nếu chưa có `.env`; project có script SQL MySQL/MariaDB `tmdt_laravel.sql` | MySQL/MariaDB/SQLite tùy `.env` |
| PDF | barryvdh/laravel-dompdf | `^3.1`, lock `v3.1.2` |
| PDF engine | dompdf/dompdf | lock `v3.1.5` |
| REPL | laravel/tinker | `^3.0`, lock `v3.0.2` |
| Test | PHPUnit | `^12.5.12`, lock `12.5.31` |
| Build frontend | Vite | `^8.0.0` |
| CSS | Tailwind CSS | `^4.0.0` |
| Laravel Vite | laravel-vite-plugin | `^3.1` |
| Dev orchestration | concurrently | `^9.0.1` |

Lưu ý: `vite.config.js` đang import `bunny` từ `laravel-vite-plugin/fonts`, nhưng `package.json` không khai báo rõ package fonts riêng. Cần kiểm tra lại khi chạy `npm install`.

## 2. Cấu trúc thư mục

### Cây thư mục tối giản

```text
.
├── ThuongMaiDienTu-DoAn.sln
├── ThuongMaiDienTu-DoAn/
│   ├── App_Start/
│   │   ├── BundleConfig.cs
│   │   ├── FilterConfig.cs
│   │   └── RouteConfig.cs
│   ├── Controllers/
│   │   ├── AdminController.cs
│   │   ├── DanhGiaController.cs
│   │   ├── GioHangController.cs
│   │   ├── HoaDonController.cs
│   │   ├── HomeController.cs
│   │   ├── KhieuNaiController.cs
│   │   ├── LoaiSanPhamController.cs
│   │   ├── SanPhamController.cs
│   │   ├── TaiKhoanController.cs
│   │   └── TinNhanController.cs
│   ├── Filters/
│   │   └── AuthorizeAdmin.cs
│   ├── Models/
│   │   ├── TMDT.edmx
│   │   ├── TMDT.Context.cs
│   │   ├── * entity classes
│   │   └── * ViewModel.cs
│   ├── Views/
│   │   ├── Admin/
│   │   ├── GioHang/
│   │   ├── HoaDon/
│   │   ├── Home/
│   │   ├── KhieuNai/
│   │   ├── LoaiSanPham/
│   │   ├── SanPham/
│   │   ├── Shared/
│   │   ├── TaiKhoan/
│   │   └── TinNhan/
│   ├── Content/
│   │   ├── Avatars/
│   │   ├── BankLogos/
│   │   ├── Images/
│   │   ├── chat_images/
│   │   └── Site.css
│   ├── Scripts/
│   ├── Global.asax.cs
│   ├── Web.config
│   ├── packages.config
│   └── ThuongMaiDienTu-DoAn.csproj
└── tmdt-laravel/
    ├── app/
    │   ├── Http/Controllers/
    │   ├── Models/
    │   └── Providers/
    ├── bootstrap/
    ├── config/
    ├── database/
    │   ├── migrations/
    │   ├── factories/
    │   └── seeders/
    ├── public/
    │   ├── Content/
    │   ├── Scripts/
    │   └── index.php
    ├── resources/
    │   ├── css/
    │   ├── js/
    │   └── views/
    ├── routes/
    │   ├── web.php
    │   └── console.php
    ├── tests/
    ├── storage/
    ├── artisan
    ├── composer.json
    ├── package.json
    ├── tmdt_laravel.sql
    ├── vite.config.js
    └── setup_*.php / fix_*.php
```

### Chức năng các thư mục lớn

| Thư mục | Vai trò |
|---|---|
| `ThuongMaiDienTu-DoAn/App_Start` | Khởi tạo route, filter, bundle cho ASP.NET MVC. |
| `ThuongMaiDienTu-DoAn/Controllers` | Controller ASP.NET MVC, chứa hầu hết nghiệp vụ: sản phẩm, tài khoản, giỏ hàng, hóa đơn, admin, chat, khiếu nại. |
| `ThuongMaiDienTu-DoAn/Models` | Entity Framework DB-first entities, `TMDTEntities` DbContext, EDMX, các ViewModel cho màn hình nghiệp vụ. |
| `ThuongMaiDienTu-DoAn/Views` | Razor views theo từng controller. |
| `ThuongMaiDienTu-DoAn/Content` | CSS, ảnh sản phẩm, avatar, ảnh chat, logo ngân hàng, font PDF. |
| `ThuongMaiDienTu-DoAn/Scripts` | Bootstrap, jQuery, validation scripts. |
| `tmdt-laravel/app/Http/Controllers` | Controller Laravel tương ứng các module từ ASP.NET. |
| `tmdt-laravel/app/Models` | Eloquent models ánh xạ các bảng nghiệp vụ. |
| `tmdt-laravel/resources/views` | Blade templates, nhiều file được convert từ `.cshtml`. |
| `tmdt-laravel/database/migrations` | Migration Laravel, nhưng hiện không khớp hoàn toàn với `tmdt_laravel.sql` và controller. |
| `tmdt-laravel/public/Content`, `public/Scripts` | Bản copy asset từ ASP.NET để Laravel phục vụ trực tiếp. |
| `tmdt-laravel/setup_*.php`, `fix_*.php`, `convert_views.php` | Script hỗ trợ chuyển đổi/setup theo phase. Đây là công cụ nội bộ tạm thời, không phải runtime chính. |

## 3. Bản đồ chức năng của file

### File cấu hình / điểm vào

| File | Chức năng chính | Thành phần cần lưu ý |
|---|---|---|
| `ThuongMaiDienTu-DoAn.sln` | Solution Visual Studio cho project ASP.NET MVC. | Chứa project `ThuongMaiDienTu-DoAn`. |
| `ThuongMaiDienTu-DoAn/Global.asax.cs` | Điểm khởi động ASP.NET MVC. | `Application_Start()`: đăng ký Area, Filter, Route, Bundle. |
| `ThuongMaiDienTu-DoAn/App_Start/RouteConfig.cs` | Route mặc định MVC. | `{controller}/{action}/{id}`, default `Home/Index`. |
| `ThuongMaiDienTu-DoAn/App_Start/BundleConfig.cs` | Bundle script/css. | jQuery, validation, Modernizr, Bootstrap, `Content/site.css`. |
| `ThuongMaiDienTu-DoAn/App_Start/FilterConfig.cs` | Đăng ký global filter. | `HandleErrorAttribute`. |
| `ThuongMaiDienTu-DoAn/Web.config` | Cấu hình ASP.NET/EF/database. | `.NET 4.7.2`, connection string `TMDTEntities` tới SQL Server. |
| `ThuongMaiDienTu-DoAn/packages.config` | Danh sách NuGet package. | ASP.NET MVC, EF, Bootstrap, jQuery, iTextSharp. |
| `tmdt-laravel/public/index.php` | Front controller Laravel. | Nạp autoload, bootstrap app, handle request. |
| `tmdt-laravel/bootstrap/app.php` | Bootstrap Laravel 13 style. | `withRouting(web: routes/web.php)`, middleware rỗng. |
| `tmdt-laravel/routes/web.php` | Toàn bộ route web Laravel. | Prefix: `sanpham`, `taikhoan`, `giohang`, `hoadon`, `admin`, `khieunai`, `loaisanpham`, `tinnhan`. |
| `tmdt-laravel/composer.json` | Dependency PHP/Laravel. | PHP 8.3, Laravel 13, dompdf. |
| `tmdt-laravel/package.json` | Dependency build frontend. | Vite 8, Tailwind 4, Laravel Vite plugin. |
| `tmdt-laravel/config/database.php` | Cấu hình DB Laravel. | Default là `sqlite` nếu `.env` không override; có config mysql/mariadb/sqlsrv. |
| `tmdt-laravel/tmdt_laravel.sql` | Script schema MySQL/MariaDB thủ công. | Gần với schema ASP.NET hơn migrations, có foreign key đầy đủ hơn. |
| `tmdt-laravel/vite.config.js` | Cấu hình Vite/Tailwind. | Input `resources/css/app.css`, `resources/js/app.js`. |

### ASP.NET MVC controllers

| File | Chức năng chính | Hàm/class quan trọng |
|---|---|---|
| `Controllers/HomeController.cs` | Trang chủ, thống kê số sản phẩm/user, sản phẩm mới. | `Index()` |
| `Controllers/SanPhamController.cs` | Module sản phẩm: listing, chi tiết, đăng bán, sửa/xóa, sản phẩm đã bán, xác nhận/hủy hóa đơn phía người bán. | `Index`, `ChiTiet`, `ThongTinNguoiBan`, `TaoMoi`, `CuaToi`, `Sua`, `Xoa`, `SanPhamDaBan`, `CT_SanPhamDaBan`, `HoanThanhHoaDon`, `HuyHoaDonBan`, `HoanThanh` |
| `Controllers/TaiKhoanController.cs` | Đăng nhập/đăng ký, profile, cập nhật avatar/chuyển khoản/mật khẩu, lịch sử đơn hàng, sửa/hủy đơn, đánh giá, xem khiếu nại sản phẩm của mình. | `DangNhap`, `DangKy`, `ThongTinKhachHang`, `ThongTinAdmin`, `CapNhatThongTin`, `CapNhatChuyenKhoan`, `CapNhatMatKhau`, `ThongTinChuyenKhoan`, `DangXuat`, `LichSu`, `CT_LichSu`, `HuyDonHang`, `SuaDonHang`, `DanhGia`, `KhieuNai` |
| `Controllers/GioHangController.cs` | Giỏ hàng, số lượng icon, thêm/tăng/giảm/xóa sản phẩm. | `CartIcon`, `Index`, `Them`, `Tang`, `Giam`, `Xoa` |
| `Controllers/HoaDonController.cs` | Đặt hàng, tạo hóa đơn theo từng người bán, trừ/hoàn kho, xác nhận, hủy, xem và in PDF hóa đơn. | `DatHang`, `XacNhanSanPham`, `HuyDon`, `ChiTiet`, `InHoaDon` |
| `Controllers/AdminController.cs` | Dashboard admin, quản lý người dùng/sản phẩm/đơn hàng/khiếu nại. | `Index`, `DoiTrangThai`, `QuanLyNguoiDung`, `QuanLySanPham`, `QuanLyDonHang`, `QuanLyKhieuNai`, `CapNhatTrangThaiKN`, `XoaKhieuNai`, `Xoa`, `DoiTrangThaiNguoiDung` |
| `Controllers/LoaiSanPhamController.cs` | CRUD loại sản phẩm phía admin. | `Them`, `Sua`, `Xoa` |
| `Controllers/KhieuNaiController.cs` | Tạo khiếu nại cho sản phẩm. | `TaoKhieuNai` GET/POST |
| `Controllers/TinNhanController.cs` | Chat giữa user/admin/người bán, load tin nhắn AJAX, gửi ảnh, đánh dấu đã đọc, xóa tin. | `Chat`, `LoadTinNhan`, `GuiTinNhan`, `DanhDauDaDoc`, `XoaTinNhan` |
| `Controllers/DanhGiaController.cs` | Thêm đánh giá sản phẩm độc lập. | `Them` |

### ASP.NET MVC models / ViewModels

| File | Chức năng chính | Thành phần cần lưu ý |
|---|---|---|
| `Models/TMDT.edmx` | Entity Framework DB-first model. | Mapping DB SQL Server. |
| `Models/TMDT.Context.cs` | DbContext EF. | `TMDTEntities`; DbSet: `SANPHAMs`, `NGUOIDUNGs`, `HOADONs`, `CT_HOADON`, `GIOHANGs`, `CT_GIOHANG`, `DANHGIAs`, `KHIEUNAIs`, `TINNHANs`, `HINHANHSPs`, `LOAISANPHAMs`. |
| `Models/SANPHAM.cs` | Entity sản phẩm. | `MaSP`, `MaKH`, `MaLoai`, `TenSP`, `MoTa`, `Gia`, `SoLuong`, `TrangThai`, `NgayTao`, navigation tới người dùng, loại, ảnh, đánh giá, hóa đơn. |
| `Models/NGUOIDUNG.cs` | Entity tài khoản. | `MaKH`, `HoTen`, `VaiTro`, `MatKhau`, `TaiKhoan`, `Email`, `SDT`, `DiaChi`, `AnhDaiDien`, `Khoa`, `SoTaiKhoan`, `TenNganHang`. |
| `Models/HOADON.cs` | Entity hóa đơn. | `MaHD`, `MaKH`, `TongTien`, `PhuongThucTT`, `DiaChiGiaoHang`, `NgayDat`, `NgayTT`, `TrangThai`. |
| `Models/CT_HOADON.cs` | Chi tiết hóa đơn, khóa kép. | `MaHD`, `MaSP`, `SoLuong`, `ThanhTien`, `TrangThaiCT`, `DaDanhGia`. |
| `Models/GIOHANG.cs` | Giỏ hàng theo người dùng. | `MaGH`, `MaKH`, `TongSoLuong`. |
| `Models/CT_GIOHANG.cs` | Chi tiết giỏ hàng, khóa kép. | `MaGH`, `MaSP`, `SoLuong`, `ThanhTien`. |
| `Models/DANHGIA.cs` | Đánh giá sản phẩm theo đơn hàng. | `MaDG`, `MaKH`, `MaSP`, `MaHD`, `SoSao`, `NoiDung`, `NgayDG`. |
| `Models/KHIEUNAI.cs` | Khiếu nại sản phẩm. | `MaKN`, `MaKH`, `MaSP`, `MoTa`, `PhanHoi`, `NgayGui`, `TrangThai`. |
| `Models/TINNHAN.cs` | Tin nhắn. | `MaTN`, `NguoiGui`, `NguoiNhan`, `NgayGui`, `NoiDung`, `MaSP`, `DaDoc`, `Anh`. |
| `Models/HINHANHSP.cs` | Ảnh sản phẩm. | `MaHA`, `Masp`, `URLAnh`, `AnhBia`. |
| `Models/LOAISANPHAM.cs` | Loại sản phẩm. | `MaLoai`, `TenLoai`. |
| `Models/*ViewModel.cs` | DTO cho view. | `AdminProductViewModel`, `ChiTietHoaDonViewModel`, `DanhGiaViewModel`, `DonHangC2CViewModel`, `HoaDonDaBanViewModel`, `KhieuNaiViewModel`, `LichSuViewModel`, `SanPhamDaBanViewModel`. |

### Laravel controllers

| File | Chức năng chính | Hàm/class quan trọng |
|---|---|---|
| `app/Http/Controllers/HomeController.php` | Trang chủ Laravel. | `index()` lấy 3 sản phẩm mới, thống kê. |
| `app/Http/Controllers/SanPhamController.php` | Port module sản phẩm. | `index`, `chiTiet`, `thongTinNguoiBan`, `taoMoi`, `postTaoMoi`, `cuaToi`, `sua`, `postSua`, `xoa`, `sanPhamDaBan`, `ctSanPhamDaBan`, `hoanThanhHoaDon`, `huyHoaDonBan`, `hoanThanh`. |
| `app/Http/Controllers/TaiKhoanController.php` | Port module tài khoản. | `dangNhap`, `postDangNhap`, `dangKy`, `postDangKy`, `thongTinKhachHang`, `capNhatThongTin`, `capNhatChuyenKhoan`, `capNhatMatKhau`, `lichSu`, `ctLichSu`, `suaDonHang`, `postSuaDonHang`, `getDanhGia`, `postDanhGia`, `khieuNai`. |
| `app/Http/Controllers/GioHangController.php` | Port giỏ hàng. | `updateCartCount`, `cartIcon`, `index`, `them`, `tang`, `giam`, `xoa`. |
| `app/Http/Controllers/HoaDonController.php` | Port đặt hàng/hóa đơn/PDF. | `datHang`, `xacNhanSanPham`, `huyDon`, `chiTiet`, `inHoaDon`. |
| `app/Http/Controllers/AdminController.php` | Port khu admin. | `checkAdmin`, `index`, `doiTrangThai`, `quanLyNguoiDung`, `quanLySanPham`, `quanLyDonHang`, `quanLyKhieuNai`, `capNhatTrangThaiKN`, `xoaKhieuNai`, `xoa`, `doiTrangThaiNguoiDung`. |
| `app/Http/Controllers/LoaiSanPhamController.php` | Port quản lý loại sản phẩm. | `checkAdmin`, `index`, `create`, `store`, `edit`, `update`, `delete`. |
| `app/Http/Controllers/KhieuNaiController.php` | Port tạo khiếu nại. | `taoKhieuNai`, `postTaoKhieuNai`. |
| `app/Http/Controllers/TinNhanController.php` | Port chat/tin nhắn AJAX. | `index`, `chat`, `loadTinNhan`, `guiTinNhan`, `xoaTinNhan`, `danhDauDaDoc`. |
| `app/Http/Controllers/Controller.php` | Base controller Laravel. | Abstract class rỗng. |

### Laravel models

| File | Chức năng chính | Relationship quan trọng |
|---|---|---|
| `app/Models/SanPham.php` | Eloquent model `san_phams`. | `loaiSanPham`, `nguoiDung`, `hinhAnhs`, `ctHoaDons`. |
| `app/Models/NguoiDung.php` | Eloquent model `nguoi_dungs`. | `hoaDons`, `sanPhams`, `gioHang`. |
| `app/Models/HoaDon.php` | Eloquent model `hoa_dons`. | `nguoiDung`, `ctHoaDons`. |
| `app/Models/CtHoaDon.php` | Eloquent model `ct_hoa_dons`, khóa kép. | `hoaDon`, `sanPham`; `primaryKey = null`, `incrementing = false`. |
| `app/Models/GioHang.php` | Eloquent model `gio_hangs`. | `nguoiDung`, `ctGioHangs`. |
| `app/Models/CtGioHang.php` | Eloquent model `ct_gio_hangs`, khóa kép. | `gioHang`, `sanPham`; `primaryKey = null`, `incrementing = false`. |
| `app/Models/DanhGia.php` | Eloquent model `danh_gias`. | `sanPham`, `nguoiDung`. |
| `app/Models/KhieuNai.php` | Eloquent model `khieu_nais`. | `sanPham`, `nguoiDung`. |
| `app/Models/HinhAnhSP.php` | Eloquent model `hinh_anh_s_p_s`. | `sanPham`. |
| `app/Models/LoaiSanPham.php` | Eloquent model `loai_san_phams`. | `sanPhams`. |
| `app/Models/TinNhan.php` | Eloquent model `tin_nhans`. | `nguoiGui`, `nguoiNhan`. |
| `app/Models/User.php` | Model auth mặc định Laravel. | Chưa tích hợp với nghiệp vụ vì hệ thống đang dùng `NguoiDung` + session thủ công. |

### Views và tài sản giao diện

| Nhóm file | Chức năng |
|---|---|
| `ThuongMaiDienTu-DoAn/Views/Shared/_Layout.cshtml` | Layout chính user-facing của ASP.NET. |
| `ThuongMaiDienTu-DoAn/Views/Shared/_LayoutAdmin.cshtml` | Layout admin ASP.NET. |
| `ThuongMaiDienTu-DoAn/Views/<Module>/*.cshtml` | View Razor theo controller/module. |
| `tmdt-laravel/resources/views/layouts/app.blade.php` | Layout chính Laravel. |
| `tmdt-laravel/resources/views/layouts/admin.blade.php` | Layout admin Laravel. |
| `tmdt-laravel/resources/views/<Module>/*.blade.php` | Blade views đã convert theo từng module. |
| `Content/Images`, `Content/Avatars`, `Content/chat_images`, `Content/BankLogos` | Asset ảnh nghiệp vụ. |
| `Content/fonts/DejaVuSans.ttf` | Font Unicode dùng khi xuất PDF ở ASP.NET. |

## 4. Luồng hoạt động chính

### Luồng ASP.NET MVC

```text
IIS/IIS Express
→ Global.asax.cs / Application_Start
→ RouteConfig: {controller}/{action}/{id}
→ Controller action
→ TMDTEntities DbContext
→ SQL Server database TMDT
→ Razor View (.cshtml)
→ Layout + CSS/JS/assets trong Content/Scripts
```

Các luồng nghiệp vụ chính:

1. Đăng nhập:
   `TaiKhoan/DangNhap` POST → `TaiKhoanController.DangNhap` → query `NGUOIDUNGs` theo `TaiKhoan` + `MatKhau` → kiểm tra `Khoa` → lưu `Session["user"]` → admin vào `Admin/Index`, user vào `Home/Index`.

2. Xem/mua sản phẩm:
   `SanPham/Index` → lọc `SANPHAMs` có `TrangThai == "Đã duyệt"` → `SanPham/ChiTiet/{id}` → `GioHang/Them/{id}` → tạo/cập nhật `GIOHANG` + `CT_GIOHANG`.

3. Đặt hàng:
   `GioHang/Index` POST tới `HoaDon/DatHang` → kiểm tra tồn kho → group item theo người bán → mỗi người bán tạo một `HOADON` → tạo `CT_HOADON` → trừ `SANPHAM.SoLuong` → xóa chi tiết giỏ hàng → chuyển sang `TaiKhoan/LichSu`.

4. Người bán xử lý đơn:
   `SanPham/SanPhamDaBan` → `SanPham/CT_SanPhamDaBan/{MaHD}` → `HoanThanhHoaDon` hoặc `HuyHoaDonBan` → cập nhật `CT_HOADON.TrangThaiCT`; nếu tất cả chi tiết hoàn tất/hủy thì cập nhật `HOADON.TrangThai`.

5. Đánh giá/khiếu nại:
   `TaiKhoan/CT_LichSu/{MaHD}` → `TaiKhoan/DanhGia` hoặc `KhieuNai/TaoKhieuNai` → ghi `DANHGIA` hoặc `KHIEUNAI`.

6. Chat:
   `TinNhan/Chat` → AJAX `LoadTinNhan` → POST `GuiTinNhan` → ghi `TINNHAN` kèm ảnh tùy chọn trong `Content/chat_images` → `DanhDauDaDoc`.

### Luồng Laravel hiện tại

```text
Web server / php artisan serve
→ public/index.php
→ bootstrap/app.php
→ routes/web.php
→ Controller action
→ Eloquent Model
→ Database theo .env (mặc định sqlite; có SQL MySQL tmdt_laravel.sql)
→ Blade view
→ public/Content + public/Scripts + CDN
```

Về ý tưởng, luồng nghiệp vụ Laravel đang mirror bản ASP.NET. Tuy nhiên trước khi coi đây là bản chạy chính, cần sửa các lỗi sau:

- Tên view trong controller là chữ thường như `view('sanpham.index')`, nhưng thư mục thực tế là `resources/views/SanPham`. Trên Linux sẽ lỗi không tìm thấy view.
- Nhiều route không khai báo `{id}` nhưng controller yêu cầu `$id`.
- Một số view đang gọi route chưa tồn tại.
- Một số Blade còn cú pháp Razor/C#.
- Migration không khớp với controller và `tmdt_laravel.sql`.
- Chuỗi tiếng Việt trong nhiều file Laravel bị lỗi encoding.

## 5. Đánh giá hiện trạng & Gợi ý bước tiếp theo

### Tính năng đã tương đối hoàn thiện

Trong `ThuongMaiDienTu-DoAn`:

- Trang chủ, listing sản phẩm, lọc/tìm kiếm/phân trang.
- Chi tiết sản phẩm, ảnh bìa/ảnh chi tiết, sản phẩm liên quan, đánh giá.
- Đăng ký/đăng nhập/session thủ công.
- Hồ sơ người dùng/admin, cập nhật avatar, thông tin chuyển khoản, mật khẩu.
- Đăng bán, sửa, xóa/ẩn sản phẩm.
- Giỏ hàng: thêm/tăng/giảm/xóa.
- Đặt hàng theo từng người bán, trừ kho, hoàn kho khi hủy.
- Lịch sử mua hàng, chi tiết hóa đơn, sửa/hủy đơn.
- Người bán xem sản phẩm/đơn đã bán, xác nhận hoặc hủy chi tiết đơn.
- Admin dashboard, quản lý người dùng, sản phẩm, đơn hàng, khiếu nại.
- Chat user/admin/người bán bằng AJAX, có gửi ảnh và đánh dấu đã đọc.
- Xuất PDF hóa đơn bằng iTextSharp.

Trong `tmdt-laravel`:

- Đã có controller, model, route và Blade tương ứng phần lớn module nghiệp vụ.
- Đã có schema SQL MySQL `tmdt_laravel.sql`.
- Đã copy asset từ bản ASP.NET sang `public/Content` và `public/Scripts`.
- Đã thêm `barryvdh/laravel-dompdf` để thay iTextSharp.

### Tính năng còn dang dở / boilerplate / rủi ro cao

1. **Laravel chưa ổn định để deploy.**
   Các lỗi route/signature nổi bật:

   - `sanpham.xoa`: route `POST /sanpham/xoa`, controller `xoa($id)`, view truyền `id` dạng query.
   - `sanpham.hoanthanhhoadon`, `sanpham.huyhoadonban`: route không có `{id}`, controller cần `$id`.
   - `admin.capnhattrangthaikn`, `admin.xoakhieunai`, `admin.xoasanpham`: route không có `{id}`, controller cần `$id`.
   - `khieunai.taokhieunai`: POST route không có `{idSanPham}`, controller cần `$idSanPham`.

2. **Route được view gọi nhưng chưa định nghĩa trong Laravel.**

   Ví dụ:

   - `taikhoan.danhgia`
   - `taikhoan.postdanhgia`
   - `hoadon.inhoadon`
   - `taikhoan.thongtinchuyenkhoan`
   - `loaisanpham.index/create/edit/update/delete/store`
   - `login`, `register` trong `welcome.blade.php`

3. **View Blade còn dấu vết Razor.**

   Các file như `resources/views/TaiKhoan/thongtinadmin.blade.php`, `resources/views/LoaiSanPham/them.blade.php`, `xoa.blade.php`, `sua.blade.php` vẫn chứa `@using`, `Html.BeginForm`, `@Html.*`, `Model.*`.

4. **Encoding tiếng Việt trong bản Laravel bị lỗi.**

   Nhiều chuỗi hiển thị/trạng thái bị mojibake như `ÄÃ£ duyá»‡t`, `Sáº£n pháº©m`, `Quáº£n lÃ½`. Điều này ảnh hưởng cả logic vì trạng thái trong DB phải so sánh đúng chuỗi.

5. **Schema Laravel không thống nhất.**

   `tmdt_laravel.sql` gần với nghiệp vụ hiện tại hơn, nhưng migrations lại lệch:

   - `danh_gias` migration dùng `DiemDG`, `BinhLuan`, thiếu `MaHD`, `SoSao`, `NoiDung` mà controller dùng.
   - `hinh_anh_s_p_s` migration dùng `MaSP`, `DuongDan`, nhưng controller/view đa số dùng `URLAnh`, `AnhBia`; SQL dùng `Masp`, `URLAnh`, `AnhBia`.
   - `tin_nhans` migration dùng `ThoiGian`, `HinhAnh`, `TrangThai`, nhưng controller dùng `NgayGui`, `Anh`, `DaDoc`.
   - Migration chủ yếu thiếu foreign key so với `tmdt_laravel.sql`.

6. **Bảo mật còn yếu ở cả hai bản.**

   - Mật khẩu đang lưu và so sánh plain text.
   - Auth dùng session thủ công, chưa dùng ASP.NET Identity hoặc Laravel Auth/Gate/Middleware.
   - Laravel models dùng `$guarded = []`, dễ mass assignment ngoài ý muốn.
   - Upload file mới kiểm tra extension, chưa kiểm tra MIME/size hoặc sanitize sâu.
   - Một số thao tác xóa/sửa dùng GET ở ASP.NET hoặc link GET trong view, chưa nhất quán CSRF.

7. **Test gần như chỉ là boilerplate.**

   Laravel chỉ có `ExampleTest`; ASP.NET không thấy test project. Chưa có test nghiệp vụ cho cart/order/status.

### Đề xuất 3-5 đầu việc kỹ thuật tiếp theo

1. **Quyết định codebase chính.**
   Nếu mục tiêu môn học là PHP/Laravel, hãy coi `tmdt-laravel` là bản chính và dùng `ThuongMaiDienTu-DoAn` làm reference nghiệp vụ. Nếu cần demo ổn định ngay, bản ASP.NET MVC có mức hoàn thiện cao hơn.

2. **Sửa nền tảng Laravel trước khi phát triển thêm tính năng.**
   Chuẩn hóa route/controller/view:

   - Thêm `{id}` vào route cần ID hoặc đổi controller lấy `$request->input('id')`.
   - Bổ sung route còn thiếu: đánh giá, in hóa đơn, thông tin chuyển khoản, CRUD loại sản phẩm đầy đủ.
   - Đổi tên thư mục view hoặc đổi `view(...)` cho khớp case.
   - Xóa/convert hết Razor còn sót trong Blade.

3. **Chuẩn hóa schema Laravel.**
   Chọn một nguồn chân lý: nên dùng migrations. Sửa migrations để khớp controller hoặc sửa controller để khớp schema. Với code hiện tại, schema nên gồm các cột nghiệp vụ như trong `tmdt_laravel.sql`: `SoSao`, `NoiDung`, `MaHD`, `URLAnh`, `AnhBia`, `NgayGui`, `DaDoc`, `Anh`. Sau đó thêm foreign key/index và chạy migrate từ đầu.

4. **Sửa encoding và chuẩn hóa trạng thái nghiệp vụ.**
   Chuyển toàn bộ file Laravel về UTF-8 đúng. Sau đó gom các trạng thái như `Đã duyệt`, `Ẩn`, `Đã bán`, `Đang chờ xử lý`, `Chờ xác nhận`, `Đã xác nhận`, `Đã Huỷ`, `Đã thanh toán` vào enum/constant để tránh so sánh chuỗi rải rác và lỗi dấu.

5. **Nâng cấp auth/security và thêm test nghiệp vụ.**
   Dùng `Hash::make` / `Hash::check` cho mật khẩu, middleware cho login/admin, validation request cho form, kiểm tra file upload bằng MIME/size, thêm feature tests cho:

   - đăng nhập/đăng ký;
   - thêm giỏ hàng và giới hạn tồn kho;
   - đặt hàng theo nhiều người bán;
   - hủy đơn hoàn kho;
   - người bán xác nhận đơn;
   - đánh giá sau khi đơn hoàn tất;
   - quyền admin/user.

## Ghi chú kiến trúc cho lập trình viên mới

- Hệ thống hiện không có service layer riêng; controller gọi ORM trực tiếp. Khi tiếp tục phát triển Laravel, nên tách dần các nghiệp vụ lớn như đặt hàng, hoàn kho, xác nhận đơn, đánh giá sang service để dễ test.
- `Session::get('user')` trong Laravel đang lưu nguyên model `NguoiDung`, tương tự `Session["user"]` ở ASP.NET. Đây là cách nhanh nhưng không tận dụng hệ sinh thái auth của Laravel.
- Các bảng chi tiết `ct_gio_hangs` và `ct_hoa_dons` dùng khóa kép. Eloquent không hỗ trợ composite primary key tốt mặc định, nên cần cẩn thận khi update/delete; hiện code thường query bằng cả `MaGH/MaSP` hoặc `MaHD/MaSP`, đây là hướng đúng.
- Nếu deploy Laravel lên Linux, ưu tiên sửa case path/view/asset trước, vì Windows không bộc lộ lỗi phân biệt hoa thường.
- Các file `setup_*.php`, `fix_*.php`, `convert_views.php` nên được coi là script chuyển đổi tạm thời. Khi Laravel ổn định, nên di chuyển chúng vào `tools/` hoặc xóa khỏi runtime path để giảm nhiễu.
