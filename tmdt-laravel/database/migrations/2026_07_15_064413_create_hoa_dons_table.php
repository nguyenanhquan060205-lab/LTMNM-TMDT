<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {

            $table->id("MaHD");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->dateTime("NgayDat")->nullable();
            $table->decimal("TongTien", 18, 0)->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->string("PhuongThucTT", 50)->nullable();
            $table->dateTime("NgayTT")->nullable();
            $table->text("DiaChiGiaoHang")->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
