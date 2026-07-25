<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hinh_anh_s_p_s', function (Blueprint $table) {

            $table->id("MaAnh");
            $table->unsignedBigInteger("MaSP")->nullable();
            $table->string("DuongDan", 255)->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('hinh_anh_s_p_s');
    }
};
