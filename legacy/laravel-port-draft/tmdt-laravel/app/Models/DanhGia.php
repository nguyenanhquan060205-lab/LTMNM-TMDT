<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = "danh_gias";
    protected $primaryKey = "MaDG";
    protected $guarded = [];

    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    
}
