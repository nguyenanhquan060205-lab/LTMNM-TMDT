<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiSanPham extends Model
{
    use HasFactory;

    protected $table = "loai_san_phams";
    protected $primaryKey = "MaLoai";
    protected $guarded = [];

    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, "MaLoai", "MaLoai");
    }
    
}
