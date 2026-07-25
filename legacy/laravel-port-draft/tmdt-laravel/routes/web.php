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
    Route::post('/xoa', [SanPhamController::class, 'xoa'])->name('xoa');
    Route::get('/sanphamdaban', [SanPhamController::class, 'sanPhamDaBan'])->name('daban');
    Route::get('/ct_sanphamdaban/{id}', [SanPhamController::class, 'ctSanPhamDaBan'])->name('ctsanphamdaban');
    Route::post('/hoanthanhhoadon', [SanPhamController::class, 'hoanThanhHoaDon'])->name('hoanthanhhoadon');
    Route::post('/huyhoadonban', [SanPhamController::class, 'huyHoaDonBan'])->name('huyhoadonban');
});

// TaiKhoan
Route::prefix('taikhoan')->name('taikhoan.')->group(function() {
    Route::get('/dangky', [TaiKhoanController::class, 'dangKy'])->name('dangky');
    Route::post('/dangky', [TaiKhoanController::class, 'postDangKy']);
    Route::get('/dangnhap', [TaiKhoanController::class, 'dangNhap'])->name('dangnhap');
    Route::post('/dangnhap', [TaiKhoanController::class, 'postDangNhap']);
    Route::get('/dangxuat', [TaiKhoanController::class, 'dangXuat'])->name('dangxuat');
    Route::get('/thongtin', [TaiKhoanController::class, 'thongTinKhachHang'])->name('thongtin');
    Route::post('/capnhatthongtin', [TaiKhoanController::class, 'capNhatThongTin'])->name('capnhatthongtin');
    Route::post('/doimatkhau', [TaiKhoanController::class, 'capNhatMatKhau'])->name('doimatkhau');
    Route::get('/lichsu', [TaiKhoanController::class, 'lichSu'])->name('lichsu');
    Route::get('/chitiethoadon/{id}', [TaiKhoanController::class, 'ctLichSu'])->name('ct_lichsu');
    Route::post('/huydonhang/{id}', [HoaDonController::class, 'huyDon'])->name('huydonhang');
    Route::post('/danhgiasanpham/{mahd}/{masp}', [TaiKhoanController::class, 'postDanhGia'])->name('danhgiasanpham');
    Route::get('/suadonhang/{id}', [TaiKhoanController::class, 'suaDonHang'])->name('suadonhang');
    Route::post('/suadonhang/{id}', [TaiKhoanController::class, 'postSuaDonHang']);
    Route::post('/capnhatchuyenkhoan', [TaiKhoanController::class, 'capNhatChuyenKhoan'])->name('capnhatchuyenkhoan');
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
    Route::post('/dathang', [HoaDonController::class, 'datHang'])->name('dathang');
    Route::get('/chitiet/{id}', [HoaDonController::class, 'chiTiet'])->name('chitiet');
    Route::post('/xacnhanthanhtoan/{mahd}/{masp}', [HoaDonController::class, 'xacNhanSanPham'])->name('xacnhanthanhtoan');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/doitrangthai', [AdminController::class, 'doiTrangThai'])->name('doitrangthai');
    Route::get('/quanlynguoidung', [AdminController::class, 'quanLyNguoiDung'])->name('quanlynguoidung');
    Route::post('/doitrangthainguoidung', [AdminController::class, 'doiTrangThaiNguoiDung'])->name('doitrangthainguoidung');
    Route::get('/quanlysanpham', [AdminController::class, 'quanLySanPham'])->name('quanlysanpham');
    Route::post('/doitrangthaisanpham', [AdminController::class, 'doiTrangThai'])->name('doitrangthaisanpham');
    Route::post('/xoasanpham', [AdminController::class, 'xoa'])->name('xoasanpham');
    Route::get('/quanlydonhang', [AdminController::class, 'quanLyDonHang'])->name('quanlydonhang');
    Route::get('/quanlykhieunai', [AdminController::class, 'quanLyKhieuNai'])->name('quanlykhieunai');
    Route::post('/capnhattrangthaikn', [AdminController::class, 'capNhatTrangThaiKN'])->name('capnhattrangthaikn');
    Route::post('/xoakhieunai', [AdminController::class, 'xoaKhieuNai'])->name('xoakhieunai');
});

// KhieuNai
Route::prefix('khieunai')->name('khieunai.')->group(function() {
    Route::get('/taokhieunai/{id}', [KhieuNaiController::class, 'taoKhieuNai'])->name('tao');
    Route::post('/taokhieunai', [KhieuNaiController::class, 'postTaoKhieuNai'])->name('taokhieunai');
});

// LoaiSanPham
Route::prefix('loaisanpham')->name('loaisanpham.')->group(function() {
    Route::post('/them', [LoaiSanPhamController::class, 'store'])->name('them');
    Route::post('/sua', [LoaiSanPhamController::class, 'update'])->name('sua');
    Route::post('/xoa', [LoaiSanPhamController::class, 'delete'])->name('xoa');
});

// TinNhan
Route::prefix('tinnhan')->name('tinnhan.')->group(function() {
    Route::get('/index', [TinNhanController::class, 'index'])->name('index');
    Route::get('/chat', [TinNhanController::class, 'chat'])->name('chat');
    Route::get('/loadtinnhan', [TinNhanController::class, 'loadTinNhan'])->name('loadtinnhan');
    Route::post('/guitinnhan', [TinNhanController::class, 'guiTinNhan'])->name('guitinnhan');
    Route::post('/xoatinnhan', [TinNhanController::class, 'xoaTinNhan'])->name('xoatinnhan');
    Route::post('/danhdaudadoc', [TinNhanController::class, 'danhDauDaDoc'])->name('danhdaudadoc');
});