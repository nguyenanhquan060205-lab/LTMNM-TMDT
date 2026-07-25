<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtGioHang extends Model
{
    protected $table = 'CT_GIOHANG';
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'ThanhTien' => 'decimal:2',
    ];


    public function gioHang() { return $this->belongsTo(GioHang::class, 'MaGH', 'MaGH'); }
    public function sanPham() { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }

    public function delete()
    {
        return static::where('MaGH', $this->MaGH)
            ->where('MaSP', $this->MaSP)
            ->delete();
    }
}
