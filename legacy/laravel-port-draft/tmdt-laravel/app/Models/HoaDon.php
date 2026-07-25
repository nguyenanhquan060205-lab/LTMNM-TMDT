<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    protected $table = "hoa_dons";
    protected $primaryKey = "MaHD";
    protected $guarded = [];

    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function ctHoaDons() { return $this->hasMany(CtHoaDon::class, "MaHD", "MaHD"); }
    
}
