<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dungs', function (Blueprint $table) {

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
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dungs');
    }
};
