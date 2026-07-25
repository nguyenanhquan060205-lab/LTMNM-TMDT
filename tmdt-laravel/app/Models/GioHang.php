<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    protected $table = 'GIOHANG';
    protected $primaryKey = 'MaGH';
    public $timestamps = false;
    protected $guarded = [];


    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH'); }
    public function ctGioHang() { return $this->hasMany(CtGioHang::class, 'MaGH', 'MaGH'); }
    public function ctGioHangs() { return $this->hasMany(CtGioHang::class, 'MaGH', 'MaGH'); }

}
