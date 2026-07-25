<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ct_hoa_dons', function (Blueprint $table) {

            $table->unsignedBigInteger("MaHD");
            $table->unsignedBigInteger("MaSP");
            $table->integer("SoLuong")->nullable();
            $table->decimal("ThanhTien", 18, 0)->nullable();
            $table->string("TrangThaiCT", 50)->nullable();
            $table->boolean("DaDanhGia")->default(false);
            $table->primary(["MaHD", "MaSP"]);
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('ct_hoa_dons');
    }
};
