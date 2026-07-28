<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhSP extends Model
{
    protected $table = 'hinhanhsp';
    protected $primaryKey = 'MaHA';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'AnhBia' => 'boolean',
    ];


    public function sanPham() { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }

}
