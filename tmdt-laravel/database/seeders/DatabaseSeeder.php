<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users (NguoiDung)
        DB::table('nguoi_dungs')->insert([
            [
                'MaKH' => 1,
                'HoTen' => 'Admin System',
                'GioiTinh' => 'Nam',
                'NgaySinh' => '1990-01-01',
                'VaiTro' => 'Admin',
                'MatKhau' => '123456',
                'TaiKhoan' => 'admin',
                'Email' => 'admin@techsecond.com',
                'SDT' => '0987654321',
                'DiaChi' => 'TP.HCM',
                'AnhDaiDien' => 'default.jpg',
                'NgayTao' => Carbon::now(),
                'Khoa' => 0,
                'SoTaiKhoan' => '1234567890',
                'TenNganHang' => 'Vietcombank'
            ],
            [
                'MaKH' => 2,
                'HoTen' => 'Nguyễn Anh Quân',
                'GioiTinh' => 'Nam',
                'NgaySinh' => '2000-05-15',
                'VaiTro' => 'User',
                'MatKhau' => '123456',
                'TaiKhoan' => 'anhquan',
                'Email' => 'quan@example.com',
                'SDT' => '0912345678',
                'DiaChi' => 'Hà Nội',
                'AnhDaiDien' => 'default.jpg',
                'NgayTao' => Carbon::now(),
                'Khoa' => 0,
                'SoTaiKhoan' => '0987654321',
                'TenNganHang' => 'Agribank'
            ],
            [
                'MaKH' => 3,
                'HoTen' => 'Trần Thị B',
                'GioiTinh' => 'Nữ',
                'NgaySinh' => '1995-10-20',
                'VaiTro' => 'User',
                'MatKhau' => '123456',
                'TaiKhoan' => 'userb',
                'Email' => 'userb@example.com',
                'SDT' => '0922334455',
                'DiaChi' => 'Đà Nẵng',
                'AnhDaiDien' => 'default.jpg',
                'NgayTao' => Carbon::now(),
                'Khoa' => 0,
                'SoTaiKhoan' => '1122334455',
                'TenNganHang' => 'Techcombank'
            ]
        ]);

        // 2. Categories (LoaiSanPham)
        DB::table('loai_san_phams')->insert([
            ['MaLoai' => 1, 'TenLoai' => 'Điện thoại'],
            ['MaLoai' => 2, 'TenLoai' => 'Laptop'],
            ['MaLoai' => 3, 'TenLoai' => 'Phụ kiện'],
            ['MaLoai' => 4, 'TenLoai' => 'Đồng hồ thông minh']
        ]);

        // 3. Products (SanPham)
        DB::table('san_phams')->insert([
            [
                'MaSP' => 1,
                'MaKH' => 2,
                'MaLoai' => 1,
                'TenSP' => 'iPhone 13 Pro Max 256GB Cũ',
                'MoTa' => 'Máy xài lướt, ngoại hình đẹp 99%, pin còn 90%.',
                'Gia' => 15000000,
                'SoLuong' => 2,
                'DanhGiaTB' => 4.5,
                'TongDanhGia' => 2,
                'TrangThai' => 'Đã duyệt',
                'NgayTao' => Carbon::now()->subDays(10)
            ],
            [
                'MaSP' => 2,
                'MaKH' => 3,
                'MaLoai' => 2,
                'TenSP' => 'MacBook Pro M1 2020',
                'MoTa' => 'RAM 16GB, SSD 512GB. Bàn phím không liệt phím nào, màn hình không ám.',
                'Gia' => 22000000,
                'SoLuong' => 1,
                'DanhGiaTB' => 5.0,
                'TongDanhGia' => 1,
                'TrangThai' => 'Đã duyệt',
                'NgayTao' => Carbon::now()->subDays(5)
            ],
            [
                'MaSP' => 3,
                'MaKH' => 2,
                'MaLoai' => 3,
                'TenSP' => 'Tai nghe AirPods Pro',
                'MoTa' => 'Mất hộp sạc, chỉ còn 2 tai nghe.',
                'Gia' => 1500000,
                'SoLuong' => 5,
                'DanhGiaTB' => 0,
                'TongDanhGia' => 0,
                'TrangThai' => 'Đã duyệt',
                'NgayTao' => Carbon::now()->subDays(2)
            ],
            [
                'MaSP' => 4,
                'MaKH' => 2,
                'MaLoai' => 4,
                'TenSP' => 'Apple Watch Series 7',
                'MoTa' => 'Mới 100% nguyên seal chưa kích hoạt.',
                'Gia' => 7500000,
                'SoLuong' => 3,
                'DanhGiaTB' => 0,
                'TongDanhGia' => 0,
                'TrangThai' => 'Đã duyệt',
                'NgayTao' => Carbon::now()->subDays(1)
            ],
            [
                'MaSP' => 5,
                'MaKH' => 3,
                'MaLoai' => 1,
                'TenSP' => 'Samsung Galaxy S22 Ultra',
                'MoTa' => 'Màu đen, bản Hàn Quốc, dùng giữ gìn.',
                'Gia' => 14000000,
                'SoLuong' => 1,
                'DanhGiaTB' => 0,
                'TongDanhGia' => 0,
                'TrangThai' => 'Chưa duyệt', // Chưa duyệt để Admin có thể test tính năng duyệt
                'NgayTao' => Carbon::now()
            ]
        ]);

        // 4. Product Images (HinhAnhSP)
        DB::table('hinh_anh_s_p_s')->insert([
            ['MaAnh' => 1, 'Masp' => 1, 'URLAnh' => 'default_product.jpg', 'AnhBia' => 1],
            ['MaAnh' => 2, 'Masp' => 2, 'URLAnh' => 'default_product.jpg', 'AnhBia' => 1],
            ['MaAnh' => 3, 'Masp' => 3, 'URLAnh' => 'default_product.jpg', 'AnhBia' => 1],
            ['MaAnh' => 4, 'Masp' => 4, 'URLAnh' => 'default_product.jpg', 'AnhBia' => 1],
            ['MaAnh' => 5, 'Masp' => 5, 'URLAnh' => 'default_product.jpg', 'AnhBia' => 1]
        ]);

        // 5. Carts (GioHang)
        DB::table('gio_hangs')->insert([
            ['MaGH' => 1, 'MaKH' => 2, 'TongSoLuong' => 1]
        ]);

        DB::table('ct_gio_hangs')->insert([
            ['MaGH' => 1, 'MaSP' => 2, 'SoLuong' => 1, 'ThanhTien' => 22000000]
        ]);

        // 6. Orders (HoaDon)
        DB::table('hoa_dons')->insert([
            [
                'MaHD' => 1,
                'MaKH' => 3,
                'TongTien' => 15000000,
                'PhuongThucTT' => 'Chuyển khoản',
                'DiaChiGiaoHang' => 'Đà Nẵng City',
                'NgayTT' => Carbon::now()->subDays(3),
                'NgayDat' => Carbon::now()->subDays(4),
                'TrangThai' => 'Đã giao'
            ]
        ]);

        DB::table('ct_hoa_dons')->insert([
            [
                'MaHD' => 1,
                'MaSP' => 1,
                'SoLuong' => 1,
                'ThanhTien' => 15000000,
                'TrangThaiCT' => 'Đã giao',
                'DaDanhGia' => 1
            ]
        ]);

        // 7. Reviews (DanhGia)
        DB::table('danh_gias')->insert([
            [
                'MaDG' => 1,
                'MaKH' => 3,
                'MaSP' => 1,
                'MaHD' => 1,
                'SoSao' => 4,
                'NoiDung' => 'Máy xài ổn, có trầy xước nhẹ hơn mình nghĩ xíu',
                'NgayDG' => Carbon::now()->subDays(1)
            ]
        ]);

        // 8. Complaints (KhieuNai)
        DB::table('khieu_nais')->insert([
            [
                'MaKN' => 1,
                'MaKH' => 3,
                'MaSP' => 1,
                'MoTa' => 'Dây cáp sạc đi kèm bị đứt ngầm.',
                'PhanHoi' => null,
                'NgayGui' => Carbon::now(),
                'TrangThai' => 'Đang chờ xử lý'
            ]
        ]);

        // 9. Messages (TinNhan)
        DB::table('tin_nhans')->insert([
            [
                'MaTN' => 1,
                'NguoiGui' => 3, // B hỏi A
                'NguoiNhan' => 2,
                'NgayGui' => Carbon::now()->subHours(5),
                'NoiDung' => 'Bạn ơi, MacBook Pro còn bớt được không?',
                'MaSP' => 2,
                'DaDoc' => 1,
                'Anh' => null
            ],
            [
                'MaTN' => 2,
                'NguoiGui' => 2, // A trả lời B
                'NguoiNhan' => 3,
                'NgayGui' => Carbon::now()->subHours(4),
                'NoiDung' => 'Máy đẹp không bớt được nha bạn ơi.',
                'MaSP' => 2,
                'DaDoc' => 0, // B chưa đọc
                'Anh' => null
            ]
        ]);
    }
}
