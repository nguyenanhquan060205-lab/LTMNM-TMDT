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
        return redirect()->route('tinnhan.chat');
    }

    public function chat(Request $request)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return redirect()->route('taikhoan.dangnhap');

        $dsNguoiDung = NguoiDung::where('MaKH', '!=', $currentUser->MaKH)->get();
        $idNguoiNhan = $request->query('idNguoiNhan');
        $mode = $request->query('mode');
        
        $activeUser = null;
        $NguoiNhanID = 0;
        if ($idNguoiNhan) {
            $activeUser = NguoiDung::find($idNguoiNhan);
            $NguoiNhanID = $activeUser ? $activeUser->MaKH : 0;
        }

        $UserChuaDoc = TinNhan::where('NguoiNhan', $currentUser->MaKH)
            ->where('DaDoc', false)
            ->pluck('NguoiGui')
            ->toArray();

        return view('tinnhan.chat', compact('dsNguoiDung', 'activeUser', 'mode', 'NguoiNhanID', 'UserChuaDoc'));
    }

    public function loadTinNhan(Request $request)
    {
        $idNguoiGui = $request->query('idNguoiGui');
        $idNguoiNhan = $request->query('idNguoiNhan');

        if (!$idNguoiGui || !$idNguoiNhan) {
            return response()->json([]);
        }

        $messages = TinNhan::with(['nguoiGui', 'nguoiNhan'])
            ->where(function ($q) use ($idNguoiGui, $idNguoiNhan) {
                $q->where('NguoiGui', $idNguoiGui)
                  ->where('NguoiNhan', $idNguoiNhan);
            })->orWhere(function ($q) use ($idNguoiGui, $idNguoiNhan) {
                $q->where('NguoiGui', $idNguoiNhan)
                  ->where('NguoiNhan', $idNguoiGui);
            })->orderBy('NgayGui', 'asc')->get()
            ->map(function ($tn) {
                $avatar = $tn->nguoiGui->AnhDaiDien ?? 'Default.jpg';
                if (!file_exists(public_path('Content/avatars/' . $avatar))) {
                    $avatar = 'Default.jpg';
                }
                
                return [
                    'MaTN' => $tn->MaTN,
                    'NguoiGui' => $tn->NguoiGui,
                    'NguoiNhan' => $tn->NguoiNhan,
                    'NoiDung' => $tn->NoiDung,
                    'Anh' => $tn->Anh,
                    'Gio' => $tn->NgayGui ? \Carbon\Carbon::parse($tn->NgayGui)->format('H:i d/m') : '',
                    'AvatarGui' => $avatar,
                    'DaDoc' => $tn->DaDoc ? true : false,
                ];
            });

        return response()->json($messages);
    }

    public function guiTinNhan(Request $request)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $nguoiNhan = $request->input('nguoiNhan');
        $noiDung = $request->input('noiDung');

        if ($nguoiNhan && ($noiDung || $request->hasFile('anh'))) {
            $fileName = null;
            if ($request->hasFile('anh')) {
                $file = $request->file('anh');
                $ext = strtolower($file->getClientOriginalExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $fileName = 'chat_' . time() . '_' . $currentUser->MaKH . '.' . $ext;
                    $file->move(public_path('Content/chat_images'), $fileName);
                }
            }

            $tn = TinNhan::create([
                'NguoiGui' => $currentUser->MaKH,
                'NguoiNhan' => $nguoiNhan,
                'NoiDung' => $noiDung,
                'Anh' => $fileName,
                'NgayGui' => now(),
                'DaDoc' => false
            ]);
            
            return response()->json(['success' => true, 'message' => clone $tn]);
        }

        return response()->json(['success' => false]);
    }

    public function xoaTinNhan(Request $request)
    {
        $id = $request->input('MaTN');
        if (!$id) return back()->with('error', 'Không tìm thấy tin nhắn');
        
        $tn = TinNhan::find($id);
        if ($tn) {
            $tn->delete();
        }
        
        return back()->with('success', 'Đã xoá tin nhắn');
    }

    public function danhDauDaDoc(Request $request)
    {
        $idNguoiGui = $request->input('idNguoiGui');
        $idNguoiNhan = $request->input('idNguoiNhan');

        TinNhan::where('NguoiGui', $idNguoiGui)
               ->where('NguoiNhan', $idNguoiNhan)
               ->update(['DaDoc' => true]);

        return response()->json(['success' => true]);
    }
}