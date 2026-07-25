<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\KhieuNaiController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\TinNhanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoaiSanPhamController;
use App\Http\Controllers\AiChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/home/index', [HomeController::class, 'index']);

// TAI KHOAN
Route::prefix('taikhoan')->name('taikhoan.')->group(function() {
    Route::get('/dangnhap', [TaiKhoanController::class, 'dangNhap'])->name('dangnhap');
    Route::post('/dangnhap', [TaiKhoanController::class, 'postDangNhap']);
    Route::get('/dangky', [TaiKhoanController::class, 'dangKy'])->name('dangky');
    Route::post('/dangky', [TaiKhoanController::class, 'postDangKy']);
    Route::get('/dangxuat', [TaiKhoanController::class, 'dangXuat'])->name('dangxuat');
    
    Route::get('/lichsu', [TaiKhoanController::class, 'lichSu'])->name('lichsu');
    Route::get('/ctlichsu/{id}', [TaiKhoanController::class, 'ctLichSu'])->name('ct_lichsu');
    Route::get('/suadonhang/{id}', [TaiKhoanController::class, 'suaDonHang'])->name('suadonhang');
    Route::post('/suadonhang/{id}', [TaiKhoanController::class, 'postSuaDonHang']);
    Route::get('/huydonhang/{id}', [TaiKhoanController::class, 'getHuyDonHang'])->name('huydonhang');
    
    Route::get('/thongtinkhachhang', [TaiKhoanController::class, 'thongTinKhachHang'])->name('thongtinkhachhang');
    Route::get('/thongtinadmin', [TaiKhoanController::class, 'thongTinAdmin'])->name('thongtinadmin');
    Route::post('/capnhatthongtin', [TaiKhoanController::class, 'capNhatThongTin'])->name('capnhatthongtin');
    Route::post('/capnhatmatkhau', [TaiKhoanController::class, 'capNhatMatKhau']);
    Route::post('/capnhatchuyenkhoan', [TaiKhoanController::class, 'capNhatChuyenKhoan']);
    
    Route::get('/danhgia/{mahd}/{masp}', [TaiKhoanController::class, 'getDanhGia'])->name('danhgia');
    Route::post('/danhgia/{mahd}/{masp}', [TaiKhoanController::class, 'postDanhGia']);
    
    Route::get('/khieunai', [TaiKhoanController::class, 'khieuNai'])->name('khieunai');
});

// SAN PHAM
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
    Route::get('/hoanthanh', [SanPhamController::class, 'hoanThanh']); // Uses query params maHD, maSP
});

// GIO HANG
Route::prefix('giohang')->name('giohang.')->group(function() {
    Route::get('/', [GioHangController::class, 'index'])->name('index');
    Route::get('/index', [GioHangController::class, 'index']);
    Route::get('/carticon', [GioHangController::class, 'cartIcon']);
    Route::get('/them/{id}', [GioHangController::class, 'them'])->name('them');
    Route::get('/tang/{id}', [GioHangController::class, 'tang'])->name('tang');
    Route::get('/giam/{id}', [GioHangController::class, 'giam'])->name('giam');
    Route::get('/xoa/{id}', [GioHangController::class, 'xoa'])->name('xoa');
});

// HOA DON
Route::prefix('hoadon')->name('hoadon.')->group(function() {
    Route::post('/dathang', [HoaDonController::class, 'datHang'])->name('dathang');
    Route::post('/xacnhansanpham/{mahd}/{masp}', [HoaDonController::class, 'xacNhanSanPham'])->name('xacnhansanpham');
    Route::post('/huydon', [HoaDonController::class, 'huyDon']);
    Route::post('/huydon/{id}', [HoaDonController::class, 'huyDon']);
    Route::get('/chitiet/{id}', [HoaDonController::class, 'chiTiet'])->name('chitiet');
    Route::get('/inhoadon/{id}', [HoaDonController::class, 'inHoaDon'])->name('inhoadon');
});

// KHIEU NAI
Route::prefix('khieunai')->name('khieunai.')->group(function() {
    Route::get('/taokhieunai/{idsanpham}', [KhieuNaiController::class, 'taoKhieuNai'])->name('taokhieunai');
    Route::post('/taokhieunai/{idsanpham}', [KhieuNaiController::class, 'postTaoKhieuNai']);
});

// DANH GIA
Route::post('/danhgia/them', [DanhGiaController::class, 'them']);

// TIN NHAN
Route::prefix('tinnhan')->name('tinnhan.')->group(function() {
    Route::get('/index', [TinNhanController::class, 'index'])->name('index');
    Route::get('/chat', [TinNhanController::class, 'chat'])->name('chat');
    Route::get('/loadtinnhan/{idNguoiGui}/{idNguoiNhan}', [TinNhanController::class, 'loadTinNhan'])->name('loadtinnhan');
    Route::post('/guitinnhan', [TinNhanController::class, 'guiTinNhan'])->name('guitinnhan');
    Route::post('/xoatinnhan/{idTin}', [TinNhanController::class, 'xoaTinNhan'])->name('xoatinnhan');
    Route::post('/danhdaudadoc/{idNguoiGui}/{idNguoiNhan}', [TinNhanController::class, 'danhDauDaDoc'])->name('danhdaudadoc');
});

// ADMIN
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/index', [AdminController::class, 'index']);
    Route::post('/doitrangthai', [AdminController::class, 'doiTrangThai']);
    Route::get('/quanlynguoidung', [AdminController::class, 'quanLyNguoiDung'])->name('quanlynguoidung');
    Route::post('/doitrangthainguoidung', [AdminController::class, 'doiTrangThaiNguoiDung']);
    Route::get('/quanlysanpham', [AdminController::class, 'quanLySanPham'])->name('quanlysanpham');
    Route::post('/xoa', [AdminController::class, 'xoa']);
    Route::get('/quanlydonhang', [AdminController::class, 'quanLyDonHang'])->name('quanlydonhang');
    Route::get('/quanlykhieunai', [AdminController::class, 'quanLyKhieuNai'])->name('quanlykhieunai');
    Route::post('/capnhattrangthaikn', [AdminController::class, 'capNhatTrangThaiKN']);
    Route::post('/xoakhieunai', [AdminController::class, 'xoaKhieuNai']);

    // LOAI SAN PHAM
    Route::post('/loaisanpham/them', [LoaiSanPhamController::class, 'them']);
    Route::post('/loaisanpham/sua/{id}', [LoaiSanPhamController::class, 'sua']);
    Route::get('/loaisanpham/xoa/{id}', [LoaiSanPhamController::class, 'xoa']);
});

// AI CHAT WIDGET
Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

Route::get('/test-upload', function() {
    return '<form method="POST" enctype="multipart/form-data" action="/test-upload-post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="file" name="files[]" required><input type="file" name="files[]" multiple><button type="submit">Submit</button></form>';
});
Route::post('/test-upload-post', function(Illuminate\Http\Request $request) {
    return response()->json($request->allFiles());
});
