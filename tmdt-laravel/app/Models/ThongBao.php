<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    protected $table = 'thongbao';
    protected $primaryKey = 'MaTB';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'DaXem' => 'boolean',
        'ThoiGian' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'MaKH', 'MaKH');
    }
}
