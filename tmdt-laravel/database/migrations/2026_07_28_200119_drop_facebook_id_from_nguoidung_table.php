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
        Schema::table('nguoidung', function (Blueprint $table) {
            if (Schema::hasColumn('nguoidung', 'facebook_id')) {
                $table->dropColumn('facebook_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            if (!Schema::hasColumn('nguoidung', 'facebook_id')) {
                $table->string('facebook_id', 255)->nullable();
            }
        });
    }
};
