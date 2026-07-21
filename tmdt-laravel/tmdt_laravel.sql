CREATE DATABASE IF NOT EXISTS tmdt_laravel;
USE tmdt_laravel;

-- LOAISANPHAM
CREATE TABLE IF NOT EXISTS `loai_san_phams` (
  `MaLoai` int(11) NOT NULL AUTO_INCREMENT,
  `TenLoai` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NGUOIDUNG
CREATE TABLE IF NOT EXISTS `nguoi_dungs` (
  `MaKH` int(11) NOT NULL AUTO_INCREMENT,
  `HoTen` varchar(100) NOT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `VaiTro` varchar(10) NOT NULL,
  `MatKhau` varchar(100) NOT NULL,
  `TaiKhoan` varchar(50) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `SDT` varchar(20) DEFAULT NULL,
  `DiaChi` varchar(200) DEFAULT NULL,
  `AnhDaiDien` varchar(255) DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL,
  `Khoa` tinyint(1) NOT NULL DEFAULT '0',
  `SoTaiKhoan` varchar(50) DEFAULT NULL,
  `TenNganHang` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaKH`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SANPHAM
CREATE TABLE IF NOT EXISTS `san_phams` (
  `MaSP` int(11) NOT NULL AUTO_INCREMENT,
  `MaKH` int(11) DEFAULT NULL,
  `MaLoai` int(11) DEFAULT NULL,
  `TenSP` varchar(200) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `Gia` decimal(18,2) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DanhGiaTB` float DEFAULT NULL,
  `TongDanhGia` int(11) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaSP`),
  KEY `FK_SanPham_NguoiDung` (`MaKH`),
  KEY `FK_SanPham_Loai` (`MaLoai`),
  CONSTRAINT `FK_SanPham_NguoiDung` FOREIGN KEY (`MaKH`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `FK_SanPham_Loai` FOREIGN KEY (`MaLoai`) REFERENCES `loai_san_phams` (`MaLoai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- GIOHANG
CREATE TABLE IF NOT EXISTS `gio_hangs` (
  `MaGH` int(11) NOT NULL AUTO_INCREMENT,
  `MaKH` int(11) DEFAULT NULL,
  `TongSoLuong` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaGH`),
  KEY `FK_GioHang_NguoiDung` (`MaKH`),
  CONSTRAINT `FK_GioHang_NguoiDung` FOREIGN KEY (`MaKH`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CT_GIOHANG
CREATE TABLE IF NOT EXISTS `ct_gio_hangs` (
  `MaGH` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `ThanhTien` decimal(18,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaGH`,`MaSP`),
  KEY `FK_CtGioHang_SanPham` (`MaSP`),
  CONSTRAINT `FK_CtGioHang_GioHang` FOREIGN KEY (`MaGH`) REFERENCES `gio_hangs` (`MaGH`) ON DELETE CASCADE,
  CONSTRAINT `FK_CtGioHang_SanPham` FOREIGN KEY (`MaSP`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- HOADON
CREATE TABLE IF NOT EXISTS `hoa_dons` (
  `MaHD` int(11) NOT NULL AUTO_INCREMENT,
  `MaKH` int(11) DEFAULT NULL,
  `TongTien` decimal(18,2) DEFAULT NULL,
  `PhuongThucTT` varchar(50) DEFAULT NULL,
  `DiaChiGiaoHang` varchar(200) DEFAULT NULL,
  `NgayTT` datetime DEFAULT NULL,
  `NgayDat` datetime DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaHD`),
  KEY `FK_HoaDon_NguoiDung` (`MaKH`),
  CONSTRAINT `FK_HoaDon_NguoiDung` FOREIGN KEY (`MaKH`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CT_HOADON
CREATE TABLE IF NOT EXISTS `ct_hoa_dons` (
  `MaHD` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `ThanhTien` decimal(18,2) NOT NULL,
  `TrangThaiCT` varchar(50) NOT NULL,
  `DaDanhGia` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaHD`,`MaSP`),
  KEY `FK_CtHoaDon_SanPham` (`MaSP`),
  CONSTRAINT `FK_CtHoaDon_HoaDon` FOREIGN KEY (`MaHD`) REFERENCES `hoa_dons` (`MaHD`) ON DELETE CASCADE,
  CONSTRAINT `FK_CtHoaDon_SanPham` FOREIGN KEY (`MaSP`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DANHGIA
CREATE TABLE IF NOT EXISTS `danh_gias` (
  `MaDG` int(11) NOT NULL AUTO_INCREMENT,
  `MaKH` int(11) NOT NULL,
  `MaSP` int(11) NOT NULL,
  `MaHD` int(11) NOT NULL,
  `SoSao` int(11) NOT NULL,
  `NoiDung` text DEFAULT NULL,
  `NgayDG` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaDG`),
  KEY `FK_DanhGia_NguoiDung` (`MaKH`),
  KEY `FK_DanhGia_SanPham` (`MaSP`),
  KEY `FK_DanhGia_HoaDon` (`MaHD`),
  CONSTRAINT `FK_DanhGia_NguoiDung` FOREIGN KEY (`MaKH`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `FK_DanhGia_SanPham` FOREIGN KEY (`MaSP`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE,
  CONSTRAINT `FK_DanhGia_HoaDon` FOREIGN KEY (`MaHD`) REFERENCES `hoa_dons` (`MaHD`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- HINHANHSP
CREATE TABLE IF NOT EXISTS `hinh_anh_s_p_s` (
  `MaAnh` int(11) NOT NULL AUTO_INCREMENT,
  `Masp` int(11) DEFAULT NULL,
  `URLAnh` varchar(255) NOT NULL,
  `AnhBia` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaAnh`),
  KEY `FK_HinhAnhSP_SanPham` (`Masp`),
  CONSTRAINT `FK_HinhAnhSP_SanPham` FOREIGN KEY (`Masp`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- KHIEUNAI
CREATE TABLE IF NOT EXISTS `khieu_nais` (
  `MaKN` int(11) NOT NULL AUTO_INCREMENT,
  `MaKH` int(11) DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `PhanHoi` text DEFAULT NULL,
  `NgayGui` datetime DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaKN`),
  KEY `FK_KhieuNai_NguoiDung` (`MaKH`),
  KEY `FK_KhieuNai_SanPham` (`MaSP`),
  CONSTRAINT `FK_KhieuNai_NguoiDung` FOREIGN KEY (`MaKH`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `FK_KhieuNai_SanPham` FOREIGN KEY (`MaSP`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TINNHAN
CREATE TABLE IF NOT EXISTS `tin_nhans` (
  `MaTN` int(11) NOT NULL AUTO_INCREMENT,
  `NguoiGui` int(11) DEFAULT NULL,
  `NguoiNhan` int(11) DEFAULT NULL,
  `NgayGui` datetime DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `DaDoc` tinyint(1) DEFAULT '0',
  `Anh` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MaTN`),
  KEY `FK_TinNhan_NguoiGui` (`NguoiGui`),
  KEY `FK_TinNhan_NguoiNhan` (`NguoiNhan`),
  KEY `FK_TinNhan_SanPham` (`MaSP`),
  CONSTRAINT `FK_TinNhan_NguoiGui` FOREIGN KEY (`NguoiGui`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `FK_TinNhan_NguoiNhan` FOREIGN KEY (`NguoiNhan`) REFERENCES `nguoi_dungs` (`MaKH`) ON DELETE CASCADE,
  CONSTRAINT `FK_TinNhan_SanPham` FOREIGN KEY (`MaSP`) REFERENCES `san_phams` (`MaSP`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SESSIONS TABLE (For Laravel)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
