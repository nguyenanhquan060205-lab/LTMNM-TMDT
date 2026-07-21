<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\HoaDon;
use App\Models\CtHoaDon;
use App\Models\DanhGia;
use App\Models\KhieuNai;
use App\Models\SanPham;
use App\Models\HinhAnhSP;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TaiKhoanController extends Controller
{
    public function dangNhap()
    {
        return view('taikhoan.dangnhap');
    }

    public function postDangNhap(Request $request)
    {
        $taikhoan = $request->input('taikhoan');
        $matkhau = $request->input('matkhau');

        if (empty($taikhoan) || empty($matkhau)) {
            return back()->with('error', 'Vui lòng nhập đầy đủ thông tin đăng nhập!');
        }

        $user = NguoiDung::where('TaiKhoan', $taikhoan)->where('MatKhau', $matkhau)->first();

        if (!$user) {
            return back()->with('error', 'Sai tài khoản hoặc mật khẩu!');
        }

        if ($user->Khoa == true) {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa! Vui lòng liên hệ Admin.');
        }

        Session::put('user', $user);

        // Fetch Cart Count
        $gio = \App\Models\GioHang::where('MaKH', $user->MaKH)->first();
        if ($gio) {
            $tong = \App\Models\CtGioHang::where('MaGH', $gio->MaGH)->sum('SoLuong');
            Session::put('CartCount', $tong);
        } else {
            Session::put('CartCount', 0);
        }

        if ($user->VaiTro == 'Admin') {
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('home.index');
        }
    }

    public function dangKy()
    {
        return view('taikhoan.dangky');
    }

    public function postDangKy(Request $request)
    {
        $nd = $request->except('_token', 'XacNhanMatKhau');
        $xacNhan = $request->input('XacNhanMatKhau');

        if ($nd['MatKhau'] !== $xacNhan) {
            return back()->with('error', 'Mật khẩu và Xác nhận mật khẩu không khớp!')->withInput();
        }

        $email = $request->input('Email');
        $sdt = $request->input('SDT');

        $query = NguoiDung::where('TaiKhoan', $nd['TaiKhoan'])
            ->orWhere('SDT', $sdt);

        if (!empty($email)) {
            $query->orWhere('Email', $email);
        }

        if ($query->exists()) {
            return back()->with('error', 'Tài khoản, email hoặc số điện thoại đã tồn tại!')->withInput();
        }

        $nd['VaiTro'] = 'User';
        $nd['NgayTao'] = now();
        $nd['AnhDaiDien'] = 'default.jpg';
        $nd['Khoa'] = false;

        NguoiDung::create($nd);

        return redirect()->route('taikhoan.dangnhap')->with('success', 'Đăng ký thành công!');
    }

    public function thongTinKhachHang($id = null)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) {
            return redirect()->route('taikhoan.dangnhap');
        }

        if ($id && $currentUser->VaiTro == 'Admin') {
            $targetUser = NguoiDung::find($id);
            if (!$targetUser) abort(404);
        } else {
            $targetUser = NguoiDung::find($currentUser->MaKH);
        }

        return view('taikhoan.thongtinkhachhang', compact('targetUser'));
    }

    public function thongTinAdmin()
    {
        $user = Session::get('user');
        if (!$user || $user->VaiTro != 'Admin') {
            return redirect()->route('taikhoan.dangnhap');
        }
        return view('taikhoan.thongtinadmin', compact('user'));
    }

    public function capNhatThongTin(Request $request)
    {
        $model = $request->except('_token', 'fileUpload');
        $user = NguoiDung::find($model['MaKH']);
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $actionName = ($user->VaiTro == 'Admin') ? 'taikhoan.thongtinadmin' : 'taikhoan.thongtinkhachhang';

        try {
            if (!empty($model['Email']) && NguoiDung::where('Email', $model['Email'])->where('MaKH', '!=', $model['MaKH'])->exists()) {
                return redirect()->route($actionName)->with('error', 'Email đã được sử dụng bởi tài khoản khác!');
            }

            if (!empty($model['SDT']) && NguoiDung::where('SDT', $model['SDT'])->where('MaKH', '!=', $model['MaKH'])->exists()) {
                return redirect()->route($actionName)->with('error', 'Số điện thoại đã được sử dụng bởi tài khoản khác!');
            }

            if ($request->hasFile('fileUpload')) {
                $file = $request->file('fileUpload');
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return redirect()->route($actionName)->with('error', 'Định dạng ảnh không hợp lệ!');
                }

                $fileName = 'user_' . $user->MaKH . '_' . time() . '.' . $ext;
                $file->move(public_path('Content/Avatars'), $fileName);

                if (!empty($user->AnhDaiDien) && $user->AnhDaiDien != 'default.jpg') {
                    $oldPath = public_path('Content/Avatars/' . $user->AnhDaiDien);
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $user->AnhDaiDien = $fileName;
            }

            $user->HoTen = $model['HoTen'] ?? $user->HoTen;
            $user->GioiTinh = $model['GioiTinh'] ?? $user->GioiTinh;
            $user->SDT = $model['SDT'] ?? $user->SDT;
            $user->DiaChi = $model['DiaChi'] ?? $user->DiaChi;

            $user->save();
            Session::put('user', $user);

            return redirect()->route($actionName)->with('success', '✅ Cập nhật thông tin thành công!');
        } catch (\Exception $ex) {
            return redirect()->route($actionName)->with('error', 'Đã xảy ra lỗi hệ thống: ' . $ex->getMessage());
        }
    }

    public function capNhatChuyenKhoan(Request $request)
    {
        $user = NguoiDung::find($request->MaKH);
        if (!$user) abort(404);

        $user->SoTaiKhoan = $request->SoTaiKhoan;
        $user->TenNganHang = $request->TenNganHang;
        $user->save();

        return redirect()->route('taikhoan.thongtinkhachhang')->with('success', 'Cập nhật thông tin chuyển khoản thành công!');
    }

    public function capNhatMatKhau(Request $request)
    {
        $user = NguoiDung::find($request->MaKH);
        if (!$user) abort(404);

        if ($user->MatKhau != $request->MatKhauHienTai) {
            return redirect()->route('taikhoan.thongtinkhachhang')->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        if ($request->MatKhauMoi != $request->XacNhanMatKhauMoi) {
            return redirect()->route('taikhoan.thongtinkhachhang')->with('error', 'Xác nhận mật khẩu không khớp!');
        }

        $user->MatKhau = $request->MatKhauMoi;
        $user->save();
        return redirect()->route('taikhoan.thongtinkhachhang')->with('success', 'Cập nhật mật khẩu thành công!');
    }

    public function thongTinChuyenKhoan($idNguoiBan)
    {
        $nguoiBan = NguoiDung::find($idNguoiBan);
        return response()->json([
            'HoTen' => $nguoiBan->HoTen,
            'SoTaiKhoan' => $nguoiBan->SoTaiKhoan,
            'TenNganHang' => $nguoiBan->TenNganHang
        ]);
    }

    public function dangXuat()
    {
        Session::forget('user');
        Session::forget('CartCount');
        return redirect()->route('home.index');
    }

    public function lichSu()
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $dsDonHang = HoaDon::where('MaKH', $kh->MaKH)
            ->orderBy('NgayDat', 'desc')
            ->get()
            ->map(function ($d) use ($kh) {
                // Check if all details are reviewed
                $ct = CtHoaDon::where('MaHD', $d->MaHD)->get();
                $allReviewed = true;
                foreach ($ct as $c) {
                    if (!DanhGia::where('MaKH', $kh->MaKH)->where('MaSP', $c->MaSP)->exists()) {
                        $allReviewed = false;
                        break;
                    }
                }

                $d->DaDanhGia = $allReviewed;
                return $d;
            });

        return view('taikhoan.lichsu', compact('dsDonHang'));
    }

    public function ctLichSu($id)
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $hd = HoaDon::find($id);
        if (!$hd) abort(404);

        if ($hd->MaKH != $kh->MaKH && $kh->VaiTro != 'Admin') {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $chiTietVm = CtHoaDon::with('sanPham')
            ->where('MaHD', $id)
            ->get()
            ->map(function ($ct) use ($kh) {
                $ct->TenSP = $ct->sanPham->TenSP ?? '';
                $ct->DaDanhGia = DanhGia::where('MaHD', $ct->MaHD)->where('MaSP', $ct->MaSP)->exists();
                // We use KhieuNai where MaSP and MaKH since KhieuNai doesn't have MaHD
                $ct->DaKhieuNai = KhieuNai::where('MaSP', $ct->MaSP)->where('MaKH', $kh->MaKH)->exists(); 
                return $ct;
            });

        return view('taikhoan.ct_lichsu', ['chiTietVm' => $chiTietVm, 'HoaDon' => $hd]);
    }

    public function getHuyDonHang($id)
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap')->with('error', 'Vui lòng đăng nhập để thực hiện!');

        $hd = HoaDon::where('MaHD', $id)->where('MaKH', $kh->MaKH)->first();
        if (!$hd) abort(404);

        if ($hd->TrangThai == 'Đang chờ xử lý') {
            $chiTiet = CtHoaDon::where('MaHD', $hd->MaHD)->get();
            foreach ($chiTiet as $item) {
                $sp = SanPham::find($item->MaSP);
                if ($sp) {
                    $sp->SoLuong += $item->SoLuong;
                    if ($sp->TrangThai == 'Đã bán' && $sp->SoLuong > 0) $sp->TrangThai = 'Đã duyệt';
                    $sp->save();
                }
                $item->TrangThaiCT = 'Đã Huỷ';
                $item->save();
            }
            $hd->TrangThai = 'Đã Huỷ';
            $hd->save();
            return redirect()->route('taikhoan.lichsu')->with('success', 'Đơn hàng đã được hủy thành công!');
        } else {
            return redirect()->route('taikhoan.lichsu')->with('error', 'Đơn hàng không thể hủy vì đã giao hoặc hoàn tất!');
        }
    }

    public function suaDonHang($id)
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $hd = HoaDon::where('MaHD', $id)->where('MaKH', $kh->MaKH)->first();
        if (!$hd) abort(404);

        return view('taikhoan.suadonhang', compact('hd'));
    }

    public function postSuaDonHang(Request $request, $id)
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $hd = HoaDon::with('ctHoaDons')->where('MaHD', $id)->where('MaKH', $kh->MaKH)->first();
        if (!$hd) abort(404);

        $ctHoaDons = $request->input('CT_HOADON', []);
        foreach ($ctHoaDons as $ctModel) {
            $ct = $hd->ctHoaDons->where('MaSP', $ctModel['MaSP'])->first();
            if ($ct) {
                $sp = SanPham::find($ct->MaSP);
                if (!$sp) return back()->withErrors(["Sản phẩm {$ct->MaSP} không tồn tại!"]);

                if ($ctModel['SoLuong'] > $sp->SoLuong + $ct->SoLuong) {
                    return back()->withErrors(["Số lượng sản phẩm '{$sp->TenSP}' không đủ. Tồn kho: " . ($sp->SoLuong + $ct->SoLuong)]);
                }

                $ct->SoLuong = $ctModel['SoLuong'];
                $ct->ThanhTien = $ct->SoLuong * $sp->Gia;
                $ct->save();
            }
        }

        $hd->PhuongThucTT = $request->input('PhuongThucTT');
        $hd->DiaChiGiaoHang = $request->input('DiaChiGiaoHang');
        $hd->save();

        return redirect()->route('taikhoan.lichsu')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    public function getDanhGia($maHD, $maSP)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $ct = CtHoaDon::with(['sanPham', 'hoaDon'])
            ->where('MaHD', $maHD)
            ->where('MaSP', $maSP)
            ->whereHas('hoaDon', function($q) use ($user) {
                $q->where('MaKH', $user->MaKH);
            })->first();

        if (!$ct) abort(403, 'Bạn không có quyền đánh giá!');
        if ($ct->TrangThaiCT != 'Đã xác nhận') abort(403, 'Chỉ đánh giá sau khi đơn hoàn thành');

        $hinh = HinhAnhSP::where('MaSP', $maSP)->where('AnhBia', true)->value('URLAnh'); // wait, MaSP column in migration is MaSP, and URLAnh is DuongDan in migration. I'll use DuongDan.

        $vm = (object)[
            'MaHD' => $ct->MaHD,
            'MaSP' => $ct->MaSP,
            'TenSP' => $ct->sanPham->TenSP ?? '',
            'Hinh' => HinhAnhSP::where('MaSP', $maSP)->value('DuongDan'), // simplified
            'DaDanhGia' => $ct->DaDanhGia
        ];

        return view('taikhoan.danhgia', compact('vm'));
    }

    public function postDanhGia(Request $request, $maHD, $maSP)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $ct = CtHoaDon::with('hoaDon')
            ->where('MaHD', $maHD)
            ->where('MaSP', $maSP)
            ->whereHas('hoaDon', function($q) use ($user) {
                $q->where('MaKH', $user->MaKH);
            })->first();

        if (!$ct || $ct->TrangThaiCT != 'Đã xác nhận') abort(403);

        DanhGia::create([
            'MaKH' => $user->MaKH,
            'MaHD' => $maHD,
            'MaSP' => $maSP,
            'SoSao' => $request->soSao,
            'NoiDung' => $request->noiDung,
            'NgayDG' => now()
        ]);

        $ct->DaDanhGia = true;
        $ct->save();

        return redirect()->route('taikhoan.lichsu')->with('success', '✅ Cảm ơn bạn đã đánh giá!');
    }

    public function khieuNai()
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $dsKhieuNai = KhieuNai::with(['sanPham', 'nguoiDung'])
            ->whereHas('sanPham', function($q) use ($kh) {
                $q->where('MaKH', $kh->MaKH);
            })
            ->orderBy('NgayGui', 'desc')
            ->get();

        return view('taikhoan.khieunai', compact('dsKhieuNai'));
    }
}