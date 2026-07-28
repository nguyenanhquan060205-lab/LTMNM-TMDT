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
            $table->string('google_id', 255)->nullable();
            $table->string('facebook_id', 255)->nullable();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('verification_token', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoidung', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'facebook_id', 'email_verified_at', 'verification_token']);
        });
    }
};
