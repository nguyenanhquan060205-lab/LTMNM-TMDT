<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khieu_nais', function (Blueprint $table) {

            $table->id("MaKN");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->text("MoTa")->nullable();
            $table->text("PhanHoi")->nullable();
            $table->dateTime("NgayGui")->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('khieu_nais');
    }
};
