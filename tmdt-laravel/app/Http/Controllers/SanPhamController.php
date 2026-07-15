<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\NguoiDung;
use App\Models\DanhGia;
use App\Models\HinhAnhSP;
use App\Models\LoaiSanPham;
use App\Models\HoaDon;
use App\Models\CtHoaDon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $maloai = $request->query('maloai');

        $u = Session::get('user');

        $query = SanPham::where('TrangThai', 'Đã duyệt');

        if ($u) {
            $query->where('MaKH', '!=', $u->MaKH);
        }

        if (!empty($q)) {
            $query->where('TenSP', 'like', "%$q%");
        }

        if (!empty($maloai)) {
            $query->where('MaLoai', $maloai);
        }

        $dsSanPham = $query->orderBy('NgayDang', 'desc')->paginate(12);

        $loai = LoaiSanPham::all();

        return view('sanpham.index', [
            'dsSanPham' => $dsSanPham,
            'q' => $q,
            'maloai' => $maloai,
            'loai' => $loai
        ]);
    }

    public function chiTiet(Request $request, $id)
    {
        $pageDG = $request->query('pageDG', 1);
        $pageSP = $request->query('pageSP', 1);

        $sp = SanPham::with(['nguoiDung', 'hinhAnhs'])->find($id);

        if (!$sp) abort(404);

        $u = Session::get('user');

        if ($sp->TrangThai == 'Ẩn') {
            if (!$u || $sp->MaKH != $u->MaKH) abort(404);
        }

        $pageSizeDG = 5;
        $danhGiaQuery = DanhGia::where('MaSP', $id)->orderBy('NgayDG', 'desc');
        $tongDanhGia = $danhGiaQuery->count();
        $trungBinhDanhGia = $tongDanhGia > 0 ? round($danhGiaQuery->avg('DiemDG') ?? 0, 1) : 0; // Note: 'DiemDG' in migration, 'SoSao' in C#. We mapped DiemDG in migration.

        $totalPageDG = (int)ceil($tongDanhGia / $pageSizeDG);
        $listDanhGia = $danhGiaQuery->skip(($pageDG - 1) * $pageSizeDG)->take($pageSizeDG)->get();

        $anhChiTiet = HinhAnhSP::where('MaSP', $id)->where('AnhBia', false)->get();

        $pageSizeSP = 4;
        $spLienQuanQuery = SanPham::with(['hinhAnhs', 'nguoiDung'])
            ->where('MaLoai', $sp->MaLoai)
            ->where('MaSP', '!=', $sp->MaSP)
            ->where('TrangThai', 'Đã duyệt')
            ->orderBy('NgayDang', 'desc');

        $totalSPLienQuan = $spLienQuanQuery->count();
        $totalPageSP = (int)ceil($totalSPLienQuan / $pageSizeSP);
        $spLienQuan = $spLienQuanQuery->skip(($pageSP - 1) * $pageSizeSP)->take($pageSizeSP)->get();

        $viewData = [
            'sp' => $sp,
            'TongDanhGia' => $tongDanhGia,
            'TrungBinhDanhGia' => $trungBinhDanhGia,
            'ListDanhGia' => $listDanhGia,
            'PageDG' => $pageDG,
            'TotalPageDG' => $totalPageDG,
            'AnhChiTiet' => $anhChiTiet,
            'SPLienQuan' => $spLienQuan,
            'PageSP' => $pageSP,
            'TotalPageSP' => $totalPageSP
        ];

        if ($u && $u->MaKH == $sp->MaKH) {
            return view('sanpham.chitietcuanguoiban', $viewData);
        }

        return view('sanpham.chitiet', $viewData);
    }

    public function thongTinNguoiBan($idNguoiBan)
    {
        $nguoiBan = NguoiDung::find($idNguoiBan);
        if (!$nguoiBan) abort(404);

        $sanPhamCuaNguoiBan = SanPham::where('MaKH', $idNguoiBan)
            ->where('TrangThai', 'Đã duyệt')
            ->get();

        return view('sanpham.thongtinnguoiban', [
            'nguoiBan' => $nguoiBan,
            'SanPham' => $sanPhamCuaNguoiBan
        ]);
    }

    public function taoMoi()
    {
        if (!Session::has('user')) return redirect()->route('taikhoan.dangnhap');
        $loaiSP = LoaiSanPham::all();
        return view('sanpham.taomoi', compact('loaiSP'));
    }

    public function postTaoMoi(Request $request)
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        $sp = new SanPham();
        $sp->TenSP = $request->TenSP;
        $sp->Gia = $request->Gia;
        $sp->MoTa = $request->MoTa;
        $sp->SoLuong = $request->SoLuong;
        $sp->MaLoai = $request->MaLoai;
        $sp->MaKH = $u->MaKH;
        $sp->NgayDang = now();
        $sp->TrangThai = 'Đã duyệt';
        $sp->save();

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $firstImage = true;
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $fileName = (string)Str::uuid() . '.' . $ext;
                        $file->move(public_path('Content/Images'), $fileName);

                        HinhAnhSP::create([
                            'MaSP' => $sp->MaSP,
                            'DuongDan' => $fileName,
                            'AnhBia' => $firstImage
                        ]);
                        if($firstImage) {
                            $sp->AnhBia = $fileName;
                            $sp->save();
                        }
                        $firstImage = false;
                    }
                }
            }
        } else {
            HinhAnhSP::create([
                'MaSP' => $sp->MaSP,
                'DuongDan' => 'noimage.jpg',
                'AnhBia' => true
            ]);
            $sp->AnhBia = 'noimage.jpg';
            $sp->save();
        }

        return redirect()->route('sanpham.cuatoi')->with('success', '🎉 Đăng tin thành công! Sản phẩm đã được hiển thị.');
    }

    public function cuaToi()
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        $list = SanPham::where('MaKH', $u->MaKH)->orderBy('NgayDang', 'desc')->get();
        return view('sanpham.cuatoi', compact('list'));
    }

    public function sua($id)
    {
        $sanPham = SanPham::find($id);
        $u = Session::get('user');
        if (!$u || !$sanPham || $sanPham->MaKH != $u->MaKH) {
            return redirect()->route('sanpham.index');
        }

        $loaiSP = LoaiSanPham::all();
        return view('sanpham.sua', compact('sanPham', 'loaiSP'));
    }

    public function postSua(Request $request, $id)
    {
        $sp = SanPham::find($id);
        if (!$sp) abort(404);

        $u = Session::get('user');
        if (!$u || $sp->MaKH != $u->MaKH) {
            return redirect()->route('sanpham.index')->with('error', 'Bạn không có quyền sửa sản phẩm này.');
        }

        $sp->TenSP = $request->TenSP;
        $sp->Gia = $request->Gia;
        $sp->MoTa = $request->MoTa;
        $sp->SoLuong = $request->SoLuong;
        $sp->MaLoai = $request->MaLoai;

        if ($sp->TrangThai != 'Ẩn') $sp->TrangThai = 'Đã duyệt';

        $allow = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $imgPath = public_path('Content/Images/');

        if ($request->hasFile('anhBiaMoi')) {
            $anhBiaMoi = $request->file('anhBiaMoi');
            if ($anhBiaMoi->isValid()) {
                $ext = strtolower($anhBiaMoi->getClientOriginalExtension());
                if (in_array($ext, $allow)) {
                    $anhBiaCu = HinhAnhSP::where('MaSP', $sp->MaSP)->where('AnhBia', true)->first();
                    if ($anhBiaCu && $anhBiaCu->DuongDan != 'noimage.jpg') {
                        @unlink($imgPath . $anhBiaCu->DuongDan);
                        $anhBiaCu->delete();
                    }

                    $fileName = (string)Str::uuid() . '.' . $ext;
                    $anhBiaMoi->move($imgPath, $fileName);

                    HinhAnhSP::create([
                        'MaSP' => $sp->MaSP,
                        'DuongDan' => $fileName,
                        'AnhBia' => true
                    ]);
                    $sp->AnhBia = $fileName;
                }
            }
        }

        if ($request->hasFile('files')) {
            $validFiles = $request->file('files');
            if (count($validFiles) > 0 && $validFiles[0] != null) {
                $anhChiTietCu = HinhAnhSP::where('MaSP', $sp->MaSP)->where('AnhBia', false)->get();
                foreach ($anhChiTietCu as $anhCu) {
                    @unlink($imgPath . $anhCu->DuongDan);
                }
                HinhAnhSP::where('MaSP', $sp->MaSP)->where('AnhBia', false)->delete();

                foreach ($validFiles as $file) {
                    if ($file && $file->isValid()) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (in_array($ext, $allow)) {
                            $fileName = (string)Str::uuid() . '.' . $ext;
                            $file->move($imgPath, $fileName);

                            HinhAnhSP::create([
                                'MaSP' => $sp->MaSP,
                                'DuongDan' => $fileName,
                                'AnhBia' => false
                            ]);
                        }
                    }
                }
            }
        }

        $sp->save();

        return redirect()->route('sanpham.chitiet', ['id' => $sp->MaSP])->with('success', '✔ Cập nhật sản phẩm thành công!');
    }

    public function xoa($id)
    {
        $u = Session::get('user');
        $sanPham = SanPham::find($id);

        if (!$u || !$sanPham || $sanPham->MaKH != $u->MaKH) {
            return redirect()->route('home.index')->with('error', 'Bạn không có quyền xóa sản phẩm này.');
        }

        try {
            $hinhAnh = HinhAnhSP::where('MaSP', $id)->get();
            $path = public_path('Content/Images/');
            foreach ($hinhAnh as $anh) {
                if ($anh->DuongDan != 'noimage.jpg') {
                    @unlink($path . $anh->DuongDan);
                }
            }
            HinhAnhSP::where('MaSP', $id)->delete();
            $sanPham->delete();
            return redirect()->route('sanpham.cuatoi')->with('success', '🗑️ Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            $sanPham->TrangThai = 'Ẩn';
            $sanPham->save();
            return redirect()->route('sanpham.cuatoi')->with('success', 'Sản phẩm đã được ẩn (do đã có lịch sử giao dịch).');
        }
    }

    public function sanPhamDaBan()
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        $dsHoaDonBan = HoaDon::with(['nguoiDung', 'ctHoaDons.sanPham'])
            ->whereHas('ctHoaDons.sanPham', function ($q) use ($u) {
                $q->where('MaKH', $u->MaKH);
            })
            ->orderBy('NgayDat', 'desc')
            ->get()
            ->map(function ($hd) use ($u) {
                $tongTien = $hd->ctHoaDons->where('sanPham.MaKH', $u->MaKH)->sum('ThanhTien');
                return (object)[
                    'MaHD' => $hd->MaHD,
                    'NgayDat' => $hd->NgayDat,
                    'NgayTT' => $hd->NgayTT,
                    'NguoiMua' => $hd->nguoiDung->HoTen ?? '',
                    'TongTien' => $tongTien,
                    'TrangThai' => $hd->TrangThai
                ];
            });

        return view('sanpham.sanphamdaban', compact('dsHoaDonBan'));
    }

    public function ctSanPhamDaBan($id)
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        $hd = HoaDon::with(['ctHoaDons.sanPham'])->find($id);
        if (!$hd) abort(404);

        $hasMyProduct = false;
        foreach ($hd->ctHoaDons as $ct) {
            if ($ct->sanPham && $ct->sanPham->MaKH == $u->MaKH) {
                $hasMyProduct = true;
                break;
            }
        }

        if (!$hasMyProduct && $u->VaiTro != 'Admin') abort(403);

        $chiTiet = $hd->ctHoaDons->filter(function ($ct) use ($u) {
            return $ct->sanPham && $ct->sanPham->MaKH == $u->MaKH;
        })->map(function ($ct) {
            $ct->DaDanhGia = DanhGia::where('MaHD', $ct->MaHD)->where('MaSP', $ct->MaSP)->exists();
            return $ct;
        });

        return view('sanpham.ct_sanphamdaban', ['chiTiet' => $chiTiet, 'HoaDon' => $hd]);
    }

    public function hoanThanhHoaDon(Request $request, $id)
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        DB::beginTransaction();
        try {
            $hd = HoaDon::with(['ctHoaDons.sanPham'])->find($id);
            if (!$hd) abort(404);

            $dsCT = $hd->ctHoaDons->filter(function ($ct) use ($u) {
                return $ct->sanPham && $ct->sanPham->MaKH == $u->MaKH && $ct->TrangThaiCT == 'Chờ xác nhận';
            });

            if ($dsCT->isEmpty()) {
                return redirect()->route('sanpham.ctsanphamdaban', $id)->with('error', 'Không có sản phẩm nào cần xác nhận.');
            }

            foreach ($dsCT as $ct) {
                $ct->TrangThaiCT = 'Đã xác nhận';
                $ct->save();
            }

            $allDone = true;
            $allCT = CtHoaDon::where('MaHD', $id)->get();
            foreach ($allCT as $ct) {
                if ($ct->TrangThaiCT != 'Đã xác nhận') {
                    $allDone = false; break;
                }
            }

            if ($allDone) {
                $hd->TrangThai = 'Đã thanh toán';
                $hd->NgayTT = now();
                $hd->save();
            }

            DB::commit();
            return redirect()->route('sanpham.daban')->with('success', 'Xác nhận thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sanpham.ctsanphamdaban', $id)->with('error', 'Có lỗi xảy ra khi hoàn thành hóa đơn.');
        }
    }

    public function huyHoaDonBan(Request $request, $id)
    {
        $u = Session::get('user');
        if (!$u) return redirect()->route('taikhoan.dangnhap');

        DB::beginTransaction();
        try {
            $hd = HoaDon::with(['ctHoaDons.sanPham'])->find($id);
            if (!$hd) abort(404);

            $dsCT = $hd->ctHoaDons->filter(function ($ct) use ($u) {
                return $ct->TrangThaiCT == 'Chờ xác nhận' && ($ct->sanPham->MaKH == $u->MaKH || $u->VaiTro == 'Admin');
            });

            if ($dsCT->isEmpty()) {
                return redirect()->route('sanpham.ctsanphamdaban', $id)->with('error', 'Không có sản phẩm nào có thể huỷ.');
            }

            foreach ($dsCT as $ct) {
                if ($ct->sanPham) {
                    $ct->sanPham->SoLuong += $ct->SoLuong;
                    $ct->sanPham->save();
                }
                $ct->TrangThaiCT = 'Đã Huỷ';
                $ct->save();
            }

            $allCanceled = true;
            $allCT = CtHoaDon::where('MaHD', $id)->get();
            foreach ($allCT as $ct) {
                if ($ct->TrangThaiCT != 'Đã Huỷ') {
                    $allCanceled = false; break;
                }
            }

            if ($allCanceled) {
                $hd->TrangThai = 'Đã Huỷ';
                $hd->save();
            }

            DB::commit();
            return redirect()->route('sanpham.daban')->with('success', 'Hủy thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sanpham.daban')->with('error', 'Có lỗi xảy ra khi huỷ hóa đơn.');
        }
    }

    public function hoanThanh($maHD, $maSP)
    {
        $ct = CtHoaDon::where('MaHD', $maHD)->where('MaSP', $maSP)->first();
        if (!$ct) abort(404);

        $ct->TrangThaiCT = 'Đã xác nhận';
        $ct->save();

        $allCT = CtHoaDon::where('MaHD', $maHD)->get();
        $tatCa = true;
        foreach ($allCT as $c) {
            if ($c->TrangThaiCT != 'Đã xác nhận') {
                $tatCa = false; break;
            }
        }

        $hoaDon = HoaDon::find($maHD);
        if ($tatCa) {
            $hoaDon->TrangThai = 'Đã thanh toán';
            $hoaDon->NgayTT = now();
        } else {
            $hoaDon->TrangThai = 'Đang chờ xử lý';
        }
        $hoaDon->save();

        return redirect()->route('sanpham.daban')->with('success', 'Xác nhận thành công!');
    }
}