<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name', 100);
            $table->string('gender', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('role', 20)->default(UserRole::User->value);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('avatar_path')->default('avatars/default.jpg');
            $table->boolean('is_locked')->default(false);
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
