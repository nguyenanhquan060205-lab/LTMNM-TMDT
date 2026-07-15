<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    use HasFactory;

    protected $table = "gio_hangs";
    protected $primaryKey = "MaGH";
    protected $guarded = [];

    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function ctGioHangs() { return $this->hasMany(CtGioHang::class, "MaGH", "MaGH"); }
    
}
