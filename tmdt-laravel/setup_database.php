<?php
$migrationsDir = __DIR__ . '/database/migrations/';
$modelsDir = __DIR__ . '/app/Models/';

$migrations = [
    'loai_san_phams' => '
            $table->id("MaLoai");
            $table->string("TenLoai", 255)->nullable();
            $table->timestamps();
    ',
    'nguoi_dungs' => '
            $table->id("MaKH");
            $table->string("HoTen", 255)->nullable();
            $table->string("GioiTinh", 10)->nullable();
            $table->date("NgaySinh")->nullable();
            $table->string("VaiTro", 50)->nullable();
            $table->string("MatKhau", 255)->nullable();
            $table->string("TaiKhoan", 255)->nullable();
            $table->string("Email", 255)->nullable();
            $table->string("SDT", 20)->nullable();
            $table->text("DiaChi")->nullable();
            $table->string("AnhDaiDien", 255)->nullable();
            $table->dateTime("NgayTao")->nullable();
            $table->boolean("Khoa")->default(false);
            $table->string("SoTaiKhoan", 50)->nullable();
            $table->string("TenNganHang", 255)->nullable();
            $table->timestamps();
    ',
    'san_phams' => '
            $table->id("MaSP");
            $table->string("TenSP", 255)->nullable();
            $table->unsignedBigInteger("MaLoai")->nullable();
            $table->text("MoTa")->nullable();
            $table->decimal("Gia", 18, 0)->nullable();
            $table->integer("SoLuong")->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->string("AnhBia", 255)->nullable();
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->dateTime("NgayDang")->nullable();
            $table->timestamps();
    ',
    'hinh_anh_s_p_s' => '
            $table->id("MaAnh");
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->string("DuongDan", 255)->nullable();
            $table->timestamps();
    ',
    'gio_hangs' => '
            $table->id("MaGH");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->integer("TongSoLuong")->nullable();
            $table->timestamps();
    ',
    'ct_gio_hangs' => '
            $table->unsignedBigInteger("MaGH");
            $table->unsignedBigInteger("MaSP");
            $table->integer("SoLuong")->nullable();
            $table->decimal("ThanhTien", 18, 0)->nullable();
            $table->primary(["MaGH", "MaSP"]);
            $table->timestamps();
    ',
    'hoa_dons' => '
            $table->id("MaHD");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->dateTime("NgayDat")->nullable();
            $table->decimal("TongTien", 18, 0)->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->string("PhuongThucTT", 50)->nullable();
            $table->dateTime("NgayTT")->nullable();
            $table->text("DiaChiGiaoHang")->nullable();
            $table->timestamps();
    ',
    'ct_hoa_dons' => '
            $table->unsignedBigInteger("MaHD");
            $table->unsignedBigInteger("MaSP");
            $table->integer("SoLuong")->nullable();
            $table->decimal("ThanhTien", 18, 0)->nullable();
            $table->string("TrangThaiCT", 50)->nullable();
            $table->boolean("DaDanhGia")->default(false);
            $table->primary(["MaHD", "MaSP"]);
            $table->timestamps();
    ',
    'danh_gias' => '
            $table->id("MaDG");
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->integer("DiemDG")->nullable();
            $table->text("BinhLuan")->nullable();
            $table->dateTime("NgayDG")->nullable();
            $table->timestamps();
    ',
    'khieu_nais' => '
            $table->id("MaKN");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->text("MoTa")->nullable();
            $table->text("PhanHoi")->nullable();
            $table->dateTime("NgayGui")->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->timestamps();
    ',
    'tin_nhans' => '
            $table->id("MaTN");
            $table->unsignedBigInteger("NguoiGui")->nullable();
            $table->unsignedBigInteger("NguoiNhan")->nullable();
            $table->text("NoiDung")->nullable();
            $table->string("HinhAnh", 255)->nullable();
            $table->dateTime("ThoiGian")->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->timestamps();
    '
];

$files = scandir($migrationsDir);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
        foreach ($migrations as $tableName => $schema) {
            if (strpos($file, "create_{$tableName}_table") !== false) {
                $content = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::create('$tableName', function (Blueprint \$table) {\n$schema        });\n    }\n\n    public function down(): void\n    {\n        Schema::dropIfExists('$tableName');\n    }\n};\n";
                file_put_contents($migrationsDir . $file, $content);
                echo "Updated migration: $file\n";
            }
        }
    }
}
?>
