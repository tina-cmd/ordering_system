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
        // Specify the schema for the 'users' table
        Schema::create('admin.users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Specify the schema for the 'password_reset_tokens' table
        Schema::create('admin.password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Specify the schema for the 'sessions' table
        Schema::create('admin.sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables from the 'admin' schema
        Schema::dropIfExists('admin.users');
        Schema::dropIfExists('admin.password_reset_tokens');
        Schema::dropIfExists('admin.sessions');
    }
};
