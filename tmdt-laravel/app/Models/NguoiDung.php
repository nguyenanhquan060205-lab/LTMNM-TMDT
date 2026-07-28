<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class NguoiDung extends Authenticatable
{
    protected $table = 'nguoidung';
    protected $primaryKey = 'MaKH';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'Khoa' => 'boolean',
    ];

    public function getAuthPasswordName()
    {
        return 'MatKhau';
    }

    public function getAuthPassword()
    {
        return $this->MatKhau;
    }

    public function sanPhams() { return $this->hasMany(SanPham::class, 'MaKH', 'MaKH'); }
    public function gioHang() { return $this->hasOne(GioHang::class, 'MaKH', 'MaKH'); }
    public function hoaDons() { return $this->hasMany(HoaDon::class, 'MaKH', 'MaKH'); }
    public function danhGias() { return $this->hasMany(DanhGia::class, 'MaKH', 'MaKH'); }
    public function khieuNais() { return $this->hasMany(KhieuNai::class, 'MaKH', 'MaKH'); }
}
