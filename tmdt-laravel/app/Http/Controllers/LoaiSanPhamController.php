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
        return view('admin.loaisanpham.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $request->validate(['TenLoai' => 'required|string|max:100']);
        
        LoaiSanPham::create(['TenLoai' => $request->TenLoai]);
        return redirect()->route('admin.quanlysanpham')->with('success', 'Thêm loại sản phẩm thành công!');
    }

    // 1. Mở trang Form sửa
    public function edit($id)
    {
        $this->checkAdmin();
        $loai = LoaiSanPham::findOrFail($id);
        return view('admin.loaisanpham.edit', compact('loai')); // Đảm bảo đúng đường dẫn View của bạn
    }

    // 2. Xử lý Cập nhật
    public function update(Request $request, $id) // Thêm biến $id vào đây
    {
        $this->checkAdmin();
        
        $loai = LoaiSanPham::findOrFail($id); // Tìm theo $id từ URL

        $request->validate(['TenLoaiMoi' => 'required|string|max:100']);
        $loai->TenLoai = $request->TenLoaiMoi;
        $loai->save();

        return redirect()->route('admin.quanlysanpham')->with('success', 'Cập nhật danh mục thành công!');
    }

    // 3. Xử lý Xóa
    public function delete($id) // Thay Request $request bằng $id
    {
        $this->checkAdmin();
        
        $loai = LoaiSanPham::find($id);
        if ($loai) {
            $loai->delete();
            return redirect()->route('admin.quanlysanpham')->with('success', 'Đã xoá danh mục sản phẩm!');
        }
        
        return redirect()->route('admin.quanlysanpham')->with('error', 'Không tìm thấy danh mục!');
    }
}
