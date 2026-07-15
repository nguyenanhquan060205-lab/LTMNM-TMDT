<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KhieuNaiController;
use App\Http\Controllers\LoaiSanPhamController;
use App\Http\Controllers\TinNhanController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// SanPham
Route::prefix('sanpham')->name('sanpham.')->group(function() {
    Route::get('/', [SanPhamController::class, 'index'])->name('index');
    Route::get('/chitiet/{id}', [SanPhamController::class, 'chiTiet'])->name('chitiet');
    Route::get('/thongtinnguoiban/{id}', [SanPhamController::class, 'thongTinNguoiBan'])->name('thongtinnguoiban');
    Route::get('/taomoi', [SanPhamController::class, 'taoMoi'])->name('taomoi');
    Route::post('/taomoi', [SanPhamController::class, 'postTaoMoi']);
    Route::get('/cuatoi', [SanPhamController::class, 'cuaToi'])->name('cuatoi');
    Route::get('/sua/{id}', [SanPhamController::class, 'sua'])->name('sua');
    Route::post('/sua/{id}', [SanPhamController::class, 'postSua']);
    Route::get('/xoa/{id}', [SanPhamController::class, 'xoa'])->name('xoa');
    Route::get('/sanphamdaban', [SanPhamController::class, 'sanPhamDaBan'])->name('daban');
    Route::get('/ct_sanphamdaban/{id}', [SanPhamController::class, 'ctSanPhamDaBan'])->name('ctsanphamdaban');
    Route::post('/hoanthanhhoadon/{id}', [SanPhamController::class, 'hoanThanhHoaDon'])->name('hoanthanhhoadon');
    Route::post('/huyhoadonban/{id}', [SanPhamController::class, 'huyHoaDonBan'])->name('huyhoadonban');
    Route::get('/hoanthanh/{maHD}/{maSP}', [SanPhamController::class, 'hoanThanh'])->name('hoanthanh');
});

// TaiKhoan
Route::prefix('taikhoan')->name('taikhoan.')->group(function() {
    Route::get('/dangky', [TaiKhoanController::class, 'dangKy'])->name('dangky');
    Route::post('/dangky', [TaiKhoanController::class, 'postDangKy']);
    Route::get('/dangnhap', [TaiKhoanController::class, 'dangNhap'])->name('dangnhap');
    Route::post('/dangnhap', [TaiKhoanController::class, 'postDangNhap']);
    Route::get('/dangxuat', [TaiKhoanController::class, 'dangXuat'])->name('dangxuat');
    Route::get('/thongtin', [TaiKhoanController::class, 'thongTin'])->name('thongtin');
    Route::post('/capnhatthongtin', [TaiKhoanController::class, 'capNhatThongTin'])->name('capnhatthongtin');
    Route::get('/doimatkhau', [TaiKhoanController::class, 'doiMatKhau'])->name('doimatkhau');
    Route::post('/doimatkhau', [TaiKhoanController::class, 'postDoiMatKhau']);
    Route::get('/lichsu', [TaiKhoanController::class, 'lichSu'])->name('lichsu');
    Route::get('/chitiethoadon/{id}', [TaiKhoanController::class, 'chiTietHoaDon'])->name('chitiethoadon');
    Route::post('/huydonhang/{id}', [TaiKhoanController::class, 'huyDonHang'])->name('huydonhang');
    Route::post('/danhgiasanpham', [TaiKhoanController::class, 'danhGiaSanPham'])->name('danhgiasanpham');
});

// GioHang
Route::prefix('giohang')->name('giohang.')->group(function() {
    Route::get('/', [GioHangController::class, 'index'])->name('index');
    Route::get('/them/{id}', [GioHangController::class, 'them'])->name('them');
    Route::get('/tang/{id}', [GioHangController::class, 'tang'])->name('tang');
    Route::get('/giam/{id}', [GioHangController::class, 'giam'])->name('giam');
    Route::get('/xoa/{id}', [GioHangController::class, 'xoa'])->name('xoa');
    Route::get('/carticon', [GioHangController::class, 'cartIcon'])->name('carticon');
});

// HoaDon
Route::prefix('hoadon')->name('hoadon.')->group(function() {
    Route::get('/dathang', [HoaDonController::class, 'datHang'])->name('dathang');
    Route::post('/xacnhansanpham/{mahd}/{masp}', [HoaDonController::class, 'xacNhanSanPham'])->name('xacnhansanpham');
    Route::post('/huydon/{id}', [HoaDonController::class, 'huyDon'])->name('huydon');
    Route::get('/chitiet/{id}', [HoaDonController::class, 'chiTiet'])->name('chitiet');
    Route::get('/inhoadon/{id}', [HoaDonController::class, 'inHoaDon'])->name('inhoadon');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/doitrangthai/{id}', [AdminController::class, 'doiTrangThai'])->name('doitrangthai');
    Route::get('/quanlynguoidung', [AdminController::class, 'quanLyNguoiDung'])->name('quanlynguoidung');
    Route::post('/doitrangthainguoidung/{id}', [AdminController::class, 'doiTrangThaiNguoiDung'])->name('doitrangthainguoidung');
    Route::get('/quanlysanpham', [AdminController::class, 'quanLySanPham'])->name('quanlysanpham');
    Route::get('/quanlydonhang', [AdminController::class, 'quanLyDonHang'])->name('quanlydonhang');
    Route::get('/quanlykhieunai', [AdminController::class, 'quanLyKhieuNai'])->name('quanlykhieunai');
    Route::post('/capnhattrangthaikn/{id}', [AdminController::class, 'capNhatTrangThaiKN'])->name('capnhattrangthaikn');
    Route::post('/xoakhieunai/{id}', [AdminController::class, 'xoaKhieuNai'])->name('xoakhieunai');
    Route::post('/xoa/{id}', [AdminController::class, 'xoa'])->name('xoa');
});

// KhieuNai
Route::prefix('khieunai')->name('khieunai.')->group(function() {
    Route::get('/taokhieunai/{id}', [KhieuNaiController::class, 'taoKhieuNai'])->name('taokhieunai');
    Route::post('/taokhieunai/{id}', [KhieuNaiController::class, 'postTaoKhieuNai']);
});

// LoaiSanPham
Route::prefix('loaisanpham')->name('loaisanpham.')->group(function() {
    Route::get('/', [LoaiSanPhamController::class, 'index'])->name('index');
    Route::get('/create', [LoaiSanPhamController::class, 'create'])->name('create');
    Route::post('/store', [LoaiSanPhamController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [LoaiSanPhamController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [LoaiSanPhamController::class, 'update'])->name('update');
    Route::post('/delete/{id}', [LoaiSanPhamController::class, 'delete'])->name('delete');
});

// TinNhan
Route::prefix('tinnhan')->name('tinnhan.')->group(function() {
    Route::get('/{userId?}', [TinNhanController::class, 'index'])->name('index');
    Route::post('/send', [TinNhanController::class, 'send'])->name('send');
});