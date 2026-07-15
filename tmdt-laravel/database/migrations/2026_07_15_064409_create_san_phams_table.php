<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_phams', function (Blueprint $table) {

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
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
