<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\CtGioHang;
use App\Models\SanPham;
use Illuminate\Support\Facades\Session;

class GioHangController extends Controller
{
    private function updateCartCount($maKH)
    {
        $gio = GioHang::where('MaKH', $maKH)->first();
        if ($gio) {
            $tong = CtGioHang::where('MaGH', $gio->MaGH)->sum('SoLuong');
            $gio->TongSoLuong = $tong;
            $gio->save();
            Session::put('CartCount', $tong);
        } else {
            Session::put('CartCount', 0);
        }
    }

    public function cartIcon()
    {
        // This is not standard Laravel to call a ChildAction like this, usually we use a ViewComposer or just session variable.
        // But we provide it in case they call it via AJAX.
        $user = Session::get('user');
        $tong = 0;
        if ($user) {
            $gio = GioHang::where('MaKH', $user->MaKH)->first();
            if ($gio) $tong = $gio->TongSoLuong ?? 0;
        }
        return response()->json(['TongSoLuong' => $tong]);
    }

    public function index()
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $gio = GioHang::firstOrCreate(['MaKH' => $user->MaKH]);

        $model = CtGioHang::with(['sanPham.hinhAnhs'])
            ->where('MaGH', $gio->MaGH)
            ->get();

        return view('giohang.index', compact('model'));
    }

    public function them($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return redirect()->route('taikhoan.dangnhap')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }

        $sp = SanPham::find($id);
        if (!$sp || $sp->TrangThai != 'Đã duyệt') {
            return redirect()->route('sanpham.index')->with('error', 'Sản phẩm không tồn tại hoặc không còn được bán.');
        }

        if ($sp->MaKH == $user->MaKH) {
            return redirect()->route('sanpham.chitiet', $id)->with('error', 'Bạn không thể mua sản phẩm của chính mình!');
        }

        $gio = GioHang::firstOrCreate(['MaKH' => $user->MaKH]);

        $ctGioHang = CtGioHang::where('MaGH', $gio->MaGH)->where('MaSP', $id)->first();
        $soLuongHienTai = $ctGioHang ? $ctGioHang->SoLuong : 0;
        $soLuongThem = 1;
        $soLuongMoi = $soLuongHienTai + $soLuongThem;

        if ($sp->SoLuong <= 0) {
            return redirect()->route('sanpham.chitiet', $id)->with('error', 'Sản phẩm này vừa hết hàng! Ai nhanh tay thì còn.');
        }

        if ($soLuongMoi > $sp->SoLuong) {
            return redirect()->route('sanpham.chitiet', $id)->with('error', "Sản phẩm '{$sp->TenSP}' chỉ còn {$sp->SoLuong} sản phẩm. Không thể thêm thêm.");
        }

        if (!$ctGioHang) {
            CtGioHang::create([
                'MaGH' => $gio->MaGH,
                'MaSP' => $id,
                'SoLuong' => $soLuongThem,
                'ThanhTien' => $sp->Gia * $soLuongThem
            ]);
        } else {
            $ctGioHang->SoLuong = $soLuongMoi;
            $ctGioHang->ThanhTien = $sp->Gia * $soLuongMoi;
            $ctGioHang->save();
        }

        $this->updateCartCount($user->MaKH);

        return redirect()->route('sanpham.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function tang($id)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $gio = GioHang::where('MaKH', $user->MaKH)->first();
        if (!$gio) return redirect()->route('giohang.index');

        $ct = CtGioHang::where('MaGH', $gio->MaGH)->where('MaSP', $id)->first();
        $sp = SanPham::find($id);

        if ($ct && $sp) {
            if ($ct->SoLuong < $sp->SoLuong) {
                $ct->SoLuong++;
                $ct->ThanhTien = $ct->SoLuong * $sp->Gia;
                $ct->save();
                $this->updateCartCount($user->MaKH);
            } else {
                Session::flash('CartWarning', "⚠️ Sản phẩm '{$sp->TenSP}' còn {$sp->SoLuong} sản phẩm!");
            }
        }

        return redirect()->route('giohang.index');
    }

    public function giam($id)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $gio = GioHang::where('MaKH', $user->MaKH)->first();
        if (!$gio) return redirect()->route('giohang.index');

        $ct = CtGioHang::where('MaGH', $gio->MaGH)->where('MaSP', $id)->first();
        if ($ct) {
            $sp = SanPham::find($id);
            if ($ct->SoLuong > 1) {
                $ct->SoLuong--;
                $ct->ThanhTien = $ct->SoLuong * $sp->Gia;
                $ct->save();
            } else {
                $ct->delete();
            }
            $this->updateCartCount($user->MaKH);
        }

        return redirect()->route('giohang.index');
    }

    public function xoa($id)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $gio = GioHang::where('MaKH', $user->MaKH)->first();
        if (!$gio) return redirect()->route('giohang.index');

        $ct = CtGioHang::where('MaGH', $gio->MaGH)->where('MaSP', $id)->first();
        if ($ct) {
            $ct->delete();
            $this->updateCartCount($user->MaKH);
        }

        return redirect()->route('giohang.index')->with('CartOK', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}