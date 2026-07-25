<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    public function them(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('taikhoan.dangnhap');
        }

        DanhGia::create([
            'MaSP'    => $request->input('maSP'),
            'MaKH'    => $user->MaKH,
            'SoSao'   => $request->input('soSao'),
            'NoiDung' => $request->input('noiDung'),
            'NgayDG'  => now(),
        ]);

        return redirect()->route('home.chitiet', ['id' => $request->input('maSP')]);
    }
}
