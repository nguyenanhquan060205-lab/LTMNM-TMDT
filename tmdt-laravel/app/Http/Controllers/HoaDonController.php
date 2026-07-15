<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\CtHoaDon;
use App\Models\GioHang;
use App\Models\CtGioHang;
use App\Models\SanPham;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HoaDonController extends Controller
{
    public function datHang()
    {
        $kh = Session::get('user');
        if (!$kh) return redirect()->route('taikhoan.dangnhap');

        $gio = GioHang::with('ctGioHangs.sanPham')->where('MaKH', $kh->MaKH)->first();

        if (!$gio || $gio->ctGioHangs->isEmpty()) {
            return redirect()->route('giohang.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::beginTransaction();
        try {
            foreach ($gio->ctGioHangs as $item) {
                $sp = SanPham::find($item->MaSP);
                if (!$sp || $sp->SoLuong < $item->SoLuong) {
                    DB::rollBack();
                    return redirect()->route('giohang.index')->with('CartError', "Sản phẩm \"{$sp->TenSP}\" đã hết hàng hoặc không đủ số lượng.");
                }
            }

            $groupBySeller = $gio->ctGioHangs->groupBy(function($item) {
                return $item->sanPham->MaKH;
            });

            foreach ($groupBySeller as $sellerId => $sellerGroup) {
                $tongTien = $sellerGroup->sum('ThanhTien');

                $hd = HoaDon::create([
                    'MaKH' => $kh->MaKH,
                    'NgayDat' => now(),
                    'PhuongThucTT' => 'Thanh toán khi nhận hàng',
                    'TrangThai' => 'Đang chờ xử lý',
                    'TongTien' => $tongTien,
                    'DiaChiGiaoHang' => $kh->DiaChi
                ]);

                foreach ($sellerGroup as $item) {
                    $sp = SanPham::find($item->MaSP);
                    $sp->SoLuong -= $item->SoLuong;
                    if ($sp->SoLuong == 0) {
                        $sp->TrangThai = 'Đã bán';
                    }
                    $sp->save();

                    CtHoaDon::create([
                        'MaHD' => $hd->MaHD,
                        'MaSP' => $item->MaSP,
                        'SoLuong' => $item->SoLuong,
                        'ThanhTien' => $item->ThanhTien,
                        'TrangThaiCT' => 'Chờ xác nhận',
                        'DaDanhGia' => false
                    ]);
                }
            }

            CtGioHang::where('MaGH', $gio->MaGH)->delete();
            $gio->TongSoLuong = 0;
            $gio->save();
            Session::put('CartCount', 0);

            DB::commit();
            return redirect()->route('taikhoan.lichsu')->with('CartOK', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('giohang.index')->with('CartError', 'Có lỗi xảy ra khi đặt hàng.');
        }
    }

    public function xacNhanSanPham(Request $request, $mahd, $masp)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $ct = CtHoaDon::with('sanPham')->where('MaHD', $mahd)->where('MaSP', $masp)->first();

        if (!$ct) {
            return redirect()->route('hoadon.chitiet', $mahd)->with('error', 'Không tìm thấy sản phẩm.');
        }

        if ($ct->sanPham->MaKH != $user->MaKH) {
            return redirect()->route('hoadon.chitiet', $mahd)->with('error', 'Bạn không có quyền xác nhận sản phẩm này.');
        }

        if ($ct->TrangThaiCT == 'Đã xác nhận') {
            return redirect()->route('hoadon.chitiet', $mahd)->with('error', 'Sản phẩm này đã được xác nhận.');
        }

        $ct->TrangThaiCT = 'Đã xác nhận';
        $ct->save();

        $conCho = CtHoaDon::where('MaHD', $mahd)->where('TrangThaiCT', 'Chờ xác nhận')->exists();
        $hd = HoaDon::find($mahd);

        if (!$conCho) {
            $hd->TrangThai = 'Đã thanh toán';
            $hd->NgayTT = now();
            $hd->save();
            Session::flash('OK', 'Tất cả sản phẩm đã xác nhận. Đơn hàng hoàn tất!');
        } else {
            Session::flash('OK', 'Xác nhận thành công! Vẫn còn sản phẩm chưa xác nhận.');
        }

        return redirect()->route('hoadon.chitiet', $mahd);
    }

    public function huyDon(Request $request, $id)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('taikhoan.dangnhap');

        $hd = HoaDon::with('ctHoaDons.sanPham')->where('MaHD', $id)->where('MaKH', $user->MaKH)->first();

        if (!$hd) {
            return redirect()->route('taikhoan.lichsu')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($hd->TrangThai != 'Đang chờ xử lý' && $hd->TrangThai != 'Chờ người bán xác nhận đủ') {
            return redirect()->route('taikhoan.lichsu')->with('error', 'Đơn này không thể hủy.');
        }

        foreach ($hd->ctHoaDons as $ct) {
            $sp = $ct->sanPham;
            if ($sp) {
                $sp->SoLuong += $ct->SoLuong;
                if ($sp->SoLuong > 0) {
                    $sp->TrangThai = 'Đã duyệt'; // "Đang bán" is not used in previous logics, but "Đã duyệt" is used.
                }
                $sp->save();
            }
            $ct->TrangThaiCT = 'Đã Huỷ';
            $ct->save();
        }

        $hd->TrangThai = 'Đã Huỷ'; // Use Đã Huỷ instead of Đã hủy to match DB
        $hd->save();

        return redirect()->route('taikhoan.lichsu')->with('success', 'Đơn hàng đã được hủy thành công!');
    }

    public function chiTiet($id)
    {
        $hd = HoaDon::with('ctHoaDons.sanPham')->find($id);
        if (!$hd) return redirect()->route('taikhoan.lichsu');
        return view('hoadon.chitiet', compact('hd'));
    }

    public function inHoaDon($id)
    {
        $hd = HoaDon::with(['nguoiDung', 'ctHoaDons.sanPham.nguoiDung'])->find($id);
        if (!$hd) abort(404);

        if ($hd->TrangThai != 'Đã thanh toán') {
            return redirect()->route('hoadon.chitiet', $id)->with('error', 'Hoá đơn chưa thể in do chưa hoàn tất xác nhận!');
        }

        $allConfirmed = true;
        foreach ($hd->ctHoaDons as $ct) {
            if ($ct->TrangThaiCT != 'Đã xác nhận') {
                $allConfirmed = false; break;
            }
        }

        if (!$allConfirmed) {
            return redirect()->route('hoadon.chitiet', $id)->with('error', 'Hoá đơn chưa thể in do chưa hoàn tất xác nhận!');
        }

        $pdf = Pdf::loadView('hoadon.pdf', compact('hd'));
        return $pdf->stream('HoaDon_' . $hd->MaHD . '.pdf');
    }
}