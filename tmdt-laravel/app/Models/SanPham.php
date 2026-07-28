<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'sanpham';
    protected $primaryKey = 'MaSP';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'Gia' => 'decimal:2',
        'DanhGiaTB' => 'float',
    ];


    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH'); }
    public function loaiSanPham() { return $this->belongsTo(LoaiSanPham::class, 'MaLoai', 'MaLoai'); }
    public function hinhAnhSPs() { return $this->hasMany(HinhAnhSP::class, 'MaSP', 'MaSP'); }
    public function hinhAnhs() { return $this->hasMany(HinhAnhSP::class, 'MaSP', 'MaSP'); }

}
