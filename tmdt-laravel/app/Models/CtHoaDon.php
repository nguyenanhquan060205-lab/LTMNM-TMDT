<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtHoaDon extends Model
{
    protected $table = 'CT_HOADON';
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'ThanhTien' => 'decimal:2',
        'DaDanhGia' => 'boolean',
    ];


    public function hoaDon() { return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD'); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }

}
