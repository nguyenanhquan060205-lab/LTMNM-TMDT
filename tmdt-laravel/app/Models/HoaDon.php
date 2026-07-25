<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'HOADON';
    protected $primaryKey = 'MaHD';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'TongTien' => 'decimal:2',
    ];


    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH'); }
    public function ctHoaDons() { return $this->hasMany(CtHoaDon::class, 'MaHD', 'MaHD'); }

}
