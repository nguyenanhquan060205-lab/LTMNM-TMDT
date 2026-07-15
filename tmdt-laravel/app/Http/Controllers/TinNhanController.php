<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinNhan;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Session;

class TinNhanController extends Controller
{
    public function index($userId = null)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return redirect()->route('taikhoan.dangnhap');

        $users = NguoiDung::where('MaKH', '!=', $currentUser->MaKH)->get();

        $messages = collect();
        $activeUser = null;

        if ($userId) {
            $activeUser = NguoiDung::find($userId);
            if ($activeUser) {
                $messages = TinNhan::where(function ($q) use ($currentUser, $userId) {
                    $q->where('NguoiGui', $currentUser->MaKH)
                      ->where('NguoiNhan', $userId);
                })->orWhere(function ($q) use ($currentUser, $userId) {
                    $q->where('NguoiGui', $userId)
                      ->where('NguoiNhan', $currentUser->MaKH);
                })->orderBy('NgayGui', 'asc')->get();
            }
        }

        return view('tinnhan.index', compact('users', 'messages', 'activeUser'));
    }

    public function send(Request $request)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $nguoiNhan = $request->input('nguoiNhan');
        $noiDung = $request->input('noiDung');

        if ($nguoiNhan && $noiDung) {
            TinNhan::create([
                'NguoiGui' => $currentUser->MaKH,
                'NguoiNhan' => $nguoiNhan,
                'NoiDung' => $noiDung,
                'NgayGui' => now(),
                'TrangThai' => 'Đã gửi'
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}