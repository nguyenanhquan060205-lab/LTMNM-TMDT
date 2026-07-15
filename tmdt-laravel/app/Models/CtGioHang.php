<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtGioHang extends Model
{
    use HasFactory;

    protected $table = "ct_gio_hangs";
    // No single primary key, composite key
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];

    public function gioHang() { return $this->belongsTo(GioHang::class, "MaGH", "MaGH"); }
    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    
}
