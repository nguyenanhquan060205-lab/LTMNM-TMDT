<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thongbao', function (Blueprint $table) {
            $table->id('MaTB');
            $table->unsignedBigInteger('MaKH');
            $table->string('TieuDe');
            $table->text('NoiDung');
            $table->string('Loai');
            $table->string('DuongDan');
            $table->boolean('DaXem')->default(false);
            $table->timestamp('ThoiGian')->useCurrent();
            
            $table->foreign('MaKH')->references('MaKH')->on('nguoidung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thongbao');
    }
};
