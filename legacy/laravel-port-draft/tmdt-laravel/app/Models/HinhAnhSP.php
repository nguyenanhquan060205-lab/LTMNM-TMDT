<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhSP extends Model
{
    use HasFactory;

    protected $table = "hinh_anh_s_p_s";
    protected $primaryKey = "MaAnh";
    protected $guarded = [];

    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    
}
