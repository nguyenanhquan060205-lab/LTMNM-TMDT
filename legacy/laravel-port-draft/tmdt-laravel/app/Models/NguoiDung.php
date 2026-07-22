<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    use HasFactory;

    protected $table = "nguoi_dungs";
    protected $primaryKey = "MaKH";
    protected $guarded = [];

    public function hoaDons() { return $this->hasMany(HoaDon::class, "MaKH", "MaKH"); }
    public function sanPhams() { return $this->hasMany(SanPham::class, "MaKH", "MaKH"); }
    public function gioHang() { return $this->hasOne(GioHang::class, "MaKH", "MaKH"); }
    
}
