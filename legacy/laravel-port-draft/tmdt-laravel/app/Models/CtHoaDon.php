<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtHoaDon extends Model
{
    use HasFactory;

    protected $table = "ct_hoa_dons";
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];

    public function hoaDon() { return $this->belongsTo(HoaDon::class, "MaHD", "MaHD"); }
    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    
}
