<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhieuNai extends Model
{
    protected $table = 'khieunai';
    protected $primaryKey = 'MaKN';
    public $timestamps = false;
    protected $guarded = [];


    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH'); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }

}
