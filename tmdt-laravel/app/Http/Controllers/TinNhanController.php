<?php

namespace App\Http\Controllers;
use App\Models\SanPham;
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

        $idNguoiNhan = $request->query('idNguoiNhan');
        $maSP = $request->query('maSP');
        $mode = $request->query('mode');

        $product = null;

        $defaultMessage = '';
        $MaSP = '';
        $TenSP = '';
        $AnhSP = '';

        if ($maSP) {
            $product = SanPham::with('hinhAnhs')->find($maSP);

            if ($product) {
                $defaultMessage = "Tôi muốn có thêm thông tin về sản phẩm \"{$product->TenSP}\".";
                $MaSP = $product->MaSP;
                $TenSP = $product->TenSP;
                $anhBiaObj = collect($product->hinhAnhs)->firstWhere('AnhBia', true);
                $AnhSP = $anhBiaObj ? $anhBiaObj->URLAnh : ($product->AnhBia ?? "noimage.jpg");
            }
        }

        $activeUser = null;
        $NguoiNhanID = 0;
        $NguoiNhanTen = '';
        if ($idNguoiNhan) {
            $activeUser = NguoiDung::find($idNguoiNhan);
            if ($activeUser) {
                $NguoiNhanID = $activeUser->MaKH;
                $NguoiNhanTen = $activeUser->HoTen;
            }
        }

        $admin = NguoiDung::where('VaiTro', 'Admin')->first();

        if ($currentUser->VaiTro == 'Admin') {
            // Admin thấy toàn bộ user (trừ chính mình)
            $dsNguoiDung = NguoiDung::where('MaKH', '!=', $currentUser->MaKH)->get();
        } else {
            // User thường: chỉ thấy những ai đã từng nhắn tin 2 chiều
            $daChatIds = TinNhan::where('NguoiGui', $currentUser->MaKH)
                ->pluck('NguoiNhan')
                ->merge(
                    TinNhan::where('NguoiNhan', $currentUser->MaKH)->pluck('NguoiGui')
                )
                ->unique()
                ->values();

            // Nếu bấm "Liên hệ" từ trang sản phẩm/khiếu nại -> thêm người đó vào list
            if ($idNguoiNhan && !$daChatIds->contains($idNguoiNhan)) {
                $daChatIds->push((int)$idNguoiNhan);
            }

            // Luôn ghim Admin ở đầu (dù chưa từng chat)
            if ($admin && !$daChatIds->contains($admin->MaKH)) {
                $daChatIds->prepend($admin->MaKH);
            }

            $dsNguoiDung = NguoiDung::whereIn('MaKH', $daChatIds)->get()
                // Sắp xếp: Admin lên đầu
                ->sortBy(fn($u) => $u->VaiTro == 'Admin' ? 0 : 1)
                ->values();
        }

        $UserChuaDoc = TinNhan::where('NguoiNhan', $currentUser->MaKH)
            ->where('DaDoc', false)
            ->pluck('NguoiGui')
            ->toArray();
            
        $NguoiGuiID = $currentUser->MaKH;

        return view(
            'tinnhan.chat',
            compact(
                'dsNguoiDung',
                'admin',
                'activeUser',
                'mode',
                'NguoiNhanID',
                'NguoiNhanTen',
                'NguoiGuiID',
                'UserChuaDoc',
                'product',
                'MaSP',
                'TenSP',
                'AnhSP',
                'defaultMessage'
            )
        );
    }

    public function loadTinNhan($idNguoiGui, $idNguoiNhan)
    {
        if (!$idNguoiGui || !$idNguoiNhan) {
            return response()->json([]);
        }

        $messages = TinNhan::with(['nguoiGui', 'sanPham.hinhAnhs'])
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
                
                $anhSP = '';
                if ($tn->sanPham) {
                    $anhBiaObj = collect($tn->sanPham->hinhAnhs)->firstWhere('AnhBia', true);
                    $anhSP = $anhBiaObj ? $anhBiaObj->URLAnh : ($tn->sanPham->AnhBia ?? "noimage.jpg");
                }
                
                return [
                    'MaTN' => $tn->MaTN,
                    'NguoiGui' => $tn->NguoiGui,
                    'NguoiNhan' => $tn->NguoiNhan,
                    'NoiDung' => $tn->NoiDung,
                    'Anh' => $tn->Anh,
                    'MaSP' => $tn->MaSP,
                    'TenSP' => $tn->sanPham ? $tn->sanPham->TenSP : '',
                    'AnhSP' => $anhSP,
                    'Gio' => $tn->NgayGui ? \Carbon\Carbon::parse($tn->NgayGui)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m') : '',
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
        $maSP = $request->input('maSP');

        if ($nguoiNhan && ($noiDung || $request->hasFile('anh'))) {
            $fileName = null;
            if ($request->hasFile('anh')) {
                $file = $request->file('anh');
                $ext = strtolower($file->getClientOriginalExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $fileName = \App\Services\CloudinaryService::upload($file->getRealPath(), 'Content/chat_images');
                }
            }

            $tn = TinNhan::create([
                'NguoiGui' => $currentUser->MaKH,
                'NguoiNhan' => $nguoiNhan,
                'MaSP' => $maSP,
                'NoiDung' => $noiDung,
                'Anh' => $fileName,
                'NgayGui' => now(),
                'DaDoc' => false
            ]);
            
            return response()->json(['success' => true, 'message' => clone $tn]);
        }

        return response()->json(['success' => false]);
    }

    public function xoaTinNhan($idTin)
    {
        $currentUser = Session::get('user');
        if (!$currentUser) return response()->json(['error' => 'Unauthorized'], 401);
        
        $tn = TinNhan::find($idTin);
        if (!$tn) return response()->json(['error' => 'Not found'], 404);

        // Chỉ người gửi hoặc admin mới được xoá
        if ($tn->NguoiGui != $currentUser->MaKH && $currentUser->VaiTro != 'Admin') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Soft-delete: thay nội dung
        $tn->NoiDung = 'Tin nhắn này đã được xóa';
        $tn->Anh = null;
        $tn->save();

        return response()->json(['success' => true]);
    }

    public function danhDauDaDoc($idNguoiGui, $idNguoiNhan)
    {
        TinNhan::where('NguoiGui', $idNguoiNhan)
               ->where('NguoiNhan', $idNguoiGui)
               ->update(['DaDoc' => true]);

        return response()->json(['success' => true]);
    }
}