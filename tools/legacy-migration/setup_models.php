<?php
$modelsDir = __DIR__ . '/app/Models/';

$models = [
    'LoaiSanPham' => '
    protected $table = "loai_san_phams";
    protected $primaryKey = "MaLoai";
    protected $guarded = [];

    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, "MaLoai", "MaLoai");
    }
    ',
    'NguoiDung' => '
    protected $table = "nguoi_dungs";
    protected $primaryKey = "MaKH";
    protected $guarded = [];

    public function hoaDons() { return $this->hasMany(HoaDon::class, "MaKH", "MaKH"); }
    public function sanPhams() { return $this->hasMany(SanPham::class, "MaKH", "MaKH"); }
    public function gioHang() { return $this->hasOne(GioHang::class, "MaKH", "MaKH"); }
    ',
    'SanPham' => '
    protected $table = "san_phams";
    protected $primaryKey = "MaSP";
    protected $guarded = [];

    public function loaiSanPham() { return $this->belongsTo(LoaiSanPham::class, "MaLoai", "MaLoai"); }
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function hinhAnhs() { return $this->hasMany(HinhAnhSP::class, "MaSP", "MaSP"); }
    public function ctHoaDons() { return $this->hasMany(CtHoaDon::class, "MaSP", "MaSP"); }
    ',
    'HinhAnhSP' => '
    protected $table = "hinh_anh_s_p_s";
    protected $primaryKey = "MaAnh";
    protected $guarded = [];

    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    ',
    'GioHang' => '
    protected $table = "gio_hangs";
    protected $primaryKey = "MaGH";
    protected $guarded = [];

    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function ctGioHangs() { return $this->hasMany(CtGioHang::class, "MaGH", "MaGH"); }
    ',
    'CtGioHang' => '
    protected $table = "ct_gio_hangs";
    // No single primary key, composite key
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];

    public function gioHang() { return $this->belongsTo(GioHang::class, "MaGH", "MaGH"); }
    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    ',
    'HoaDon' => '
    protected $table = "hoa_dons";
    protected $primaryKey = "MaHD";
    protected $guarded = [];

    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    public function ctHoaDons() { return $this->hasMany(CtHoaDon::class, "MaHD", "MaHD"); }
    ',
    'CtHoaDon' => '
    protected $table = "ct_hoa_dons";
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];

    public function hoaDon() { return $this->belongsTo(HoaDon::class, "MaHD", "MaHD"); }
    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    ',
    'DanhGia' => '
    protected $table = "danh_gias";
    protected $primaryKey = "MaDG";
    protected $guarded = [];

    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    ',
    'KhieuNai' => '
    protected $table = "khieu_nais";
    protected $primaryKey = "MaKN";
    protected $guarded = [];

    public function sanPham() { return $this->belongsTo(SanPham::class, "MaSP", "MaSP"); }
    public function nguoiDung() { return $this->belongsTo(NguoiDung::class, "MaKH", "MaKH"); }
    ',
    'TinNhan' => '
    protected $table = "tin_nhans";
    protected $primaryKey = "MaTN";
    protected $guarded = [];

    public function nguoiGui() { return $this->belongsTo(NguoiDung::class, "NguoiGui", "MaKH"); }
    public function nguoiNhan() { return $this->belongsTo(NguoiDung::class, "NguoiNhan", "MaKH"); }
    '
];

foreach ($models as $name => $code) {
    $file = $modelsDir . $name . '.php';
    if (file_exists($file)) {
        $content = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Factories\HasFactory;\nuse Illuminate\Database\Eloquent\Model;\n\nclass $name extends Model\n{\n    use HasFactory;\n$code\n}\n";
        file_put_contents($file, $content);
        echo "Updated model: $name\n";
    }
}
?>
