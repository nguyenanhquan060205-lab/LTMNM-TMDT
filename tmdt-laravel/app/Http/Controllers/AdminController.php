<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\SanPham;
use App\Models\LoaiSanPham;
use App\Models\HoaDon;
use App\Models\KhieuNai;
use App\Models\HinhAnhSP;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        $user = Session::get('user');
        if (!$user || $user->VaiTro != 'Admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $TongTaiKhoan = NguoiDung::count();
        $TongSanPham = SanPham::count();
        $DaBan = SanPham::where('TrangThai', 'Đã bán')->count();
        $An = SanPham::where('TrangThai', 'Ẩn')->count();
        $TinDaDuyet = SanPham::where('TrangThai', 'Đã duyệt')->count();

        return view('admin.index', compact('TongTaiKhoan', 'TongSanPham', 'DaBan', 'An', 'TinDaDuyet'));
    }

    public function doiTrangThai(Request $request)
    {
        $this->checkAdmin();
        $id = $request->input('id');
        $sp = SanPham::find($id);
        if (!$sp) abort(404);

        $sp->TrangThai = $request->input('tt');
        $sp->save();

        return redirect()->route('admin.quanlysanpham')->with('success', "✅ Đã cập nhật trạng thái sản phẩm **{$sp->TenSP}** thành '{$request->input('tt')}'");
    }

    public function quanLyNguoiDung()
    {
        $this->checkAdmin();
        $dsNguoiDung = NguoiDung::latest()->get();
        return view('admin.quanlynguoidung', compact('dsNguoiDung'));
    }

    public function quanLySanPham()
    {
        $this->checkAdmin();
        $dsSanPham = SanPham::with('nguoiDung')->orderBy('MaSP', 'desc')->get();
        $dsLoaiSanPham = LoaiSanPham::with('sanPhams')->get();

        return view('admin.quanlysanpham', compact('dsSanPham', 'dsLoaiSanPham'));
    }

    public function quanLyDonHang()
    {
        $this->checkAdmin();
        $donhangs = HoaDon::with(['nguoiDung', 'ctHoaDons.sanPham.nguoiDung'])
            ->get()
            ->map(function ($hd) {
                $nguoiBan = "(Chưa có sản phẩm)";
                if ($hd->ctHoaDons->isNotEmpty() && $hd->ctHoaDons->first()->sanPham && $hd->ctHoaDons->first()->sanPham->nguoiDung) {
                    $nguoiBan = $hd->ctHoaDons->first()->sanPham->nguoiDung->HoTen;
                }
                return (object)[
                    'MaHD' => $hd->MaHD,
                    'NguoiMua' => $hd->nguoiDung->HoTen ?? '',
                    'NguoiBan' => $nguoiBan,
                    'NgayDat' => $hd->NgayDat,
                    'TongTien' => $hd->TongTien,
                    'TrangThai' => $hd->TrangThai
                ];
            });

        return view('admin.quanlydonhang', compact('donhangs'));
    }

    public function quanLyKhieuNai()
    {
        $this->checkAdmin();
        $dsKhieuNai = KhieuNai::with(['nguoiDung', 'sanPham'])->orderBy('NgayGui', 'desc')->get();
        return view('admin.quanlykhieunai', compact('dsKhieuNai'));
    }

    public function capNhatTrangThaiKN(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user || $user->VaiTro != 'Admin') abort(403);

        $kn = KhieuNai::find($id);
        if (!$kn) abort(404);

        $kn->TrangThai = "Đã giải quyết";
        // There is no PhanHoi column in KhieuNai migration we made, so skip or update if added later.
        $kn->save();

        return redirect()->route('admin.quanlykhieunai');
    }

    public function xoaKhieuNai(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user || $user->VaiTro != 'Admin') abort(403);

        $kn = KhieuNai::find($id);
        if (!$kn) abort(404);

        $kn->delete();
        return redirect()->route('admin.quanlykhieunai')->with('success', 'Đã xoá khiếu nại thành công!');
    }

    public function xoa(Request $request, $id)
    {
        $this->checkAdmin();
        $sp = SanPham::find($id);
        if (!$sp) return redirect()->route('admin.quanlysanpham')->with('error', 'Sản phẩm không tồn tại!');

        try {
            $hinhAnhs = HinhAnhSP::where('MaSP', $id)->get();
            $path = public_path('Content/Images/');
            foreach ($hinhAnhs as $item) {
                if ($item->DuongDan != 'noimage.jpg') {
                    @unlink($path . $item->DuongDan);
                }
            }
            HinhAnhSP::where('MaSP', $id)->delete();
            $sp->delete();
            return redirect()->route('admin.quanlysanpham')->with('success', '🗑️ Đã xóa sản phẩm vĩnh viễn!');
        } catch (\Exception $e) {
            $sp->TrangThai = 'Ẩn';
            $sp->save();
            return redirect()->route('admin.quanlysanpham')->with('success', '⚠️ Sản phẩm đã có đơn hàng, không thể xóa hẳn. Hệ thống đã chuyển sang trạng thái "Ẩn".');
        }
    }

    public function doiTrangThaiNguoiDung(Request $request, $id)
    {
        $this->checkAdmin();
        $user = NguoiDung::find($id);
        if (!$user) return redirect()->route('admin.quanlynguoidung')->with('error', 'Không tìm thấy tài khoản!');

        if ($user->VaiTro == 'Admin') {
            return redirect()->route('admin.quanlynguoidung')->with('error', 'Không thể khóa tài khoản Admin!');
        }

        $user->Khoa = !$user->Khoa;
        $user->save();

        $msg = $user->Khoa ? 'Tài khoản đã bị khóa!' : 'Tài khoản đã được mở khóa!';
        return redirect()->route('admin.quanlynguoidung')->with('success', $msg);
    }
}