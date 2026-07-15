<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_hangs', function (Blueprint $table) {

            $table->id("MaGH");
            $table->unsignedBigInteger("MaKH")->nullable();
            $table->integer("TongSoLuong")->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_hangs');
    }
};
