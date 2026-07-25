<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaiSanPham;
use Illuminate\Support\Facades\Session;

class LoaiSanPhamController extends Controller
{
    private function checkAdmin()
    {
        $user = Session::get('user');
        if (!$user || $user->VaiTro != 'Admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $dsLoai = LoaiSanPham::orderBy('TenLoai')->get();
        return view('loaisanpham.index', compact('dsLoai'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('loaisanpham.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate(['TenLoai' => 'required|string|max:100']);
        
        LoaiSanPham::create(['TenLoai' => $request->TenLoai]);
        return redirect()->route('loaisanpham.index')->with('success', 'Thêm loại sản phẩm thành công!');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $loai = LoaiSanPham::find($id);
        if (!$loai) abort(404);
        return view('loaisanpham.edit', compact('loai'));
    }

    public function update(Request $request)
    {
        $this->checkAdmin();
        $id = $request->input('id');
        $loai = LoaiSanPham::find($id);
        if (!$loai) abort(404);

        $request->validate(['TenLoaiMoi' => 'required|string|max:100']);
        $loai->TenLoai = $request->TenLoaiMoi;
        $loai->save();

        return redirect()->route('admin.quanlysanpham')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function delete(Request $request)
    {
        $this->checkAdmin();
        $id = $request->input('id');
        $loai = LoaiSanPham::find($id);
        if ($loai) {
            $loai->delete();
            return redirect()->route('admin.quanlysanpham')->with('success', 'Đã xoá danh mục sản phẩm!');
        }
        return redirect()->route('admin.quanlysanpham');
    }
}