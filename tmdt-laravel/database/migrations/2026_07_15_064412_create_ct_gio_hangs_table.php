<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ct_gio_hangs', function (Blueprint $table) {

            $table->unsignedBigInteger("MaGH");
            $table->unsignedBigInteger("MaSP");
            $table->integer("SoLuong")->nullable();
            $table->decimal("ThanhTien", 18, 0)->nullable();
            $table->primary(["MaGH", "MaSP"]);
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('ct_gio_hangs');
    }
};
