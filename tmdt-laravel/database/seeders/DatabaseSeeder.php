<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use App\Models\HinhAnhSP;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo người dùng (Admin)
        NguoiDung::create([
            'TaiKhoan' => 'admin',
            'MatKhau' => '123', 
            'HoTen' => 'Quản trị viên',
            'Email' => 'admin@gmail.com',
            'SDT' => '0123456789',
            'NgayTao' => Carbon::now(),
            'VaiTro' => 'Admin',
            'Khoa' => false,
        ]);

        // 2. Tạo người dùng (User)
        $user1 = NguoiDung::create([
            'TaiKhoan' => 'user1',
            'MatKhau' => '123',
            'HoTen' => 'Người Dùng 1',
            'Email' => 'user1@gmail.com',
            'SDT' => '0987654321',
            'NgayTao' => Carbon::now(),
            'VaiTro' => 'User',
            'Khoa' => false,
        ]);

        $user2 = NguoiDung::create([
            'TaiKhoan' => 'user2',
            'MatKhau' => '123',
            'HoTen' => 'Người Dùng 2',
            'Email' => 'user2@gmail.com',
            'SDT' => '0987111222',
            'NgayTao' => Carbon::now(),
            'VaiTro' => 'User',
            'Khoa' => false,
        ]);

        // 3. Tạo Loại Sản Phẩm
        $loai1 = LoaiSanPham::create(['TenLoai' => 'Điện thoại']);
        $loai2 = LoaiSanPham::create(['TenLoai' => 'Laptop']);
        $loai3 = LoaiSanPham::create(['TenLoai' => 'Đồ điện tử']);
        $loai4 = LoaiSanPham::create(['TenLoai' => 'Đồ gia dụng']);
        $loai5 = LoaiSanPham::create(['TenLoai' => 'Xe cộ']);
        $loai6 = LoaiSanPham::create(['TenLoai' => 'Khác']);

        // 4. Tạo Sản phẩm
        $sp1 = SanPham::create([
            'MaKH' => $user1->MaKH,
            'MaLoai' => $loai1->MaLoai,
            'TenSP' => 'iPhone 13 Pro Max 256GB Cũ',
            'Gia' => 18000000,
            'MoTa' => 'Máy hình thức đẹp 99%, pin 90%. Chưa qua sửa chữa. Kèm hộp và cáp sạc.',
            'NgayTao' => Carbon::now(),
            'TrangThai' => 'Đã duyệt'
        ]);

        HinhAnhSP::create([
            'MaSP' => $sp1->MaSP,
            'URLAnh' => 'noimage.jpg',
            'AnhBia' => true
        ]);

        $sp2 = SanPham::create([
            'MaKH' => $user2->MaKH,
            'MaLoai' => $loai2->MaLoai,
            'TenSP' => 'Laptop Dell XPS 13 9310',
            'Gia' => 22000000,
            'MoTa' => 'Core i7, RAM 16GB, SSD 512GB. Bàn phím êm, màn hình đẹp xước nhẹ góc dưới.',
            'NgayTao' => Carbon::now(),
            'TrangThai' => 'Đã duyệt'
        ]);

        HinhAnhSP::create([
            'MaSP' => $sp2->MaSP,
            'URLAnh' => 'noimage.jpg',
            'AnhBia' => true
        ]);

        $sp3 = SanPham::create([
            'MaKH' => $user1->MaKH,
            'MaLoai' => $loai4->MaLoai,
            'TenSP' => 'Nồi chiên không dầu Philips',
            'Gia' => 1500000,
            'MoTa' => 'Dùng lướt, mới 95%. Bán rẻ do không có nhu cầu.',
            'NgayTao' => Carbon::now(),
            'TrangThai' => 'Đã duyệt'
        ]);

        HinhAnhSP::create([
            'MaSP' => $sp3->MaSP,
            'URLAnh' => 'noimage.jpg',
            'AnhBia' => true
        ]);

        $sp4 = SanPham::create([
            'MaKH' => $user2->MaKH,
            'MaLoai' => $loai1->MaLoai,
            'TenSP' => 'Samsung S23 Ultra',
            'Gia' => 21000000,
            'MoTa' => 'Mới nguyên seal, được tặng dư không xài.',
            'NgayTao' => Carbon::now(),
            'TrangThai' => 'Chờ duyệt'
        ]);

        HinhAnhSP::create([
            'MaSP' => $sp4->MaSP,
            'URLAnh' => 'noimage.jpg',
            'AnhBia' => true
        ]);
        
        echo "Database seeded successfully!";
    }
}
