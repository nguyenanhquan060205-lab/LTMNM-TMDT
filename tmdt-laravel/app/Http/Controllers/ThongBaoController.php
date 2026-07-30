<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Session;

class ThongBaoController extends Controller
{
    public function layDanhSach()
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $thongBaos = ThongBao::where('MaKH', $user->MaKH)
            ->orderBy('ThoiGian', 'desc')
            ->take(20)
            ->get();
            
        $chuaDoc = ThongBao::where('MaKH', $user->MaKH)
            ->where('DaXem', false)
            ->count();

        return response()->json([
            'thongbaos' => $thongBaos,
            'chuadoc' => $chuaDoc
        ]);
    }

    public function danhDauDaDoc($id)
    {
        $user = Session::get('user');
        if (!$user) {
            return response()->json(['error' => 'Chưa đăng nhập'], 401);
        }

        $tb = ThongBao::where('MaTB', $id)->where('MaKH', $user->MaKH)->first();
        if ($tb) {
            $tb->DaXem = true;
            $tb->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Không tìm thấy'], 404);
    }
}
