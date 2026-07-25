<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_gias', function (Blueprint $table) {

            $table->id("MaDG");
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->integer("DiemDG")->nullable();
            $table->text("BinhLuan")->nullable();
            $table->dateTime("NgayDG")->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_gias');
    }
};
