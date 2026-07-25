<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = "tin_nhans";
    protected $primaryKey = "MaTN";
    protected $guarded = [];

    public function nguoiGui() { return $this->belongsTo(NguoiDung::class, "NguoiGui", "MaKH"); }
    public function nguoiNhan() { return $this->belongsTo(NguoiDung::class, "NguoiNhan", "MaKH"); }
    
}
