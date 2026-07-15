<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = "san_phams";
    protected $primaryKey = "MaSP";
    protected $guarded = [];

    public function loaiSanPham() { return $this->belongsTo(LoaiSanPham::class, "MaLoai", "MaLoai"); }
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function hinhAnhs() { return $this->hasMany(HinhAnhSP::class, "MaSP", "MaSP"); }
    public function ctHoaDons() { return $this->hasMany(CtHoaDon::class, "MaSP", "MaSP"); }
    
}
