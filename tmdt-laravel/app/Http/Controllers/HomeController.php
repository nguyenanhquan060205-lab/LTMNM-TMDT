<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\NguoiDung;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 3 sản phẩm mới nhất
        $sanPhamMoi = SanPham::where('TrangThai', 'Đã duyệt')
            ->orderBy('NgayDang', 'desc')
            ->take(3)
            ->get();

        $tongSP = SanPham::count();
        $tongUser = NguoiDung::count();
        $tyLeThanhCong = "99%";

        return view('home.index', compact('sanPhamMoi', 'tongSP', 'tongUser', 'tyLeThanhCong'));
    }
}
