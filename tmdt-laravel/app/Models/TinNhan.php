<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    protected $table = 'tinnhan';
    protected $primaryKey = 'MaTN';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'DaDoc' => 'boolean',
    ];


    public function nguoiGui() { return $this->belongsTo(NguoiDung::class, 'NguoiGui', 'MaKH'); }
    public function nguoiNhan() { return $this->belongsTo(NguoiDung::class, 'NguoiNhan', 'MaKH'); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }

}
