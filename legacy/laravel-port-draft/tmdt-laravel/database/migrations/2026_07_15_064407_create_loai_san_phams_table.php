<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loai_san_phams', function (Blueprint $table) {

            $table->id("MaLoai");
            $table->string("TenLoai", 255)->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_san_phams');
    }
};
