<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KhieuNai;
use App\Models\SanPham;
use Illuminate\Support\Facades\Session;

class KhieuNaiController extends Controller
{
    public function taoKhieuNai($idSanPham)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return redirect()->route('taikhoan.dangnhap');

        $sp = SanPham::find($idSanPham);
        if (!$sp) abort(404);

        return view('khieunai.taokhieunai', compact('sp'));
    }

    public function postTaoKhieuNai(Request $request, $idSanPham)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return redirect()->route('taikhoan.dangnhap');

        $moTa = $request->input('MoTa');
        if (empty(trim($moTa))) {
            return redirect()->route('khieunai.taokhieunai', $idSanPham)->with('error', 'Vui lòng nhập nội dung khiếu nại!');
        }

        KhieuNai::create([
            'MaKH' => $currentUser->MaKH,
            'MaSP' => $idSanPham,
            'MoTa' => $moTa,
            'NgayGui' => now(),
            'TrangThai' => 'Chưa xử lý'
        ]);

        return redirect()->route('sanpham.chitiet', $idSanPham)->with('success', '✅ Khiếu nại của bạn đã được gửi, vui lòng chờ phản hồi từ Admin.');
    }
}