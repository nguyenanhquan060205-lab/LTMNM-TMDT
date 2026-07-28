<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'danhgia';
    protected $primaryKey = 'MaDG';
    public $timestamps = false;
    protected $guarded = [];


    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH'); }
    public function ctHoaDon() { return $this->belongsTo(CtHoaDon::class, 'MaHD', 'MaHD')->where('MaSP', $this->MaSP); }

}
