<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSanPham extends Model
{
    protected $table = 'LOAISANPHAM';
    protected $primaryKey = 'MaLoai';
    public $timestamps = false;
    protected $guarded = [];


    public function sanPhams() { return $this->hasMany(SanPham::class, 'MaLoai', 'MaLoai'); }

}
