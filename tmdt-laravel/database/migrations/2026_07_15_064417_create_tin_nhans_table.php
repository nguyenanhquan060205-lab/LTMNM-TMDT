<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_nhans', function (Blueprint $table) {

            $table->id("MaTN");
            $table->unsignedBigInteger("NguoiGui")->nullable();
            $table->unsignedBigInteger("NguoiNhan")->nullable();
            $table->text("NoiDung")->nullable();
            $table->string("HinhAnh", 255)->nullable();
            $table->dateTime("ThoiGian")->nullable();
            $table->string("TrangThai", 50)->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_nhans');
    }
};
