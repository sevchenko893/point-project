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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Nama orang tidak panjang
            $table->string('name', 50);

            // Email max RFC 254, tapi 100 cukup & umum
            $table->string('email', 100)->unique();

            $table->timestamp('email_verified_at')->nullable();

            // Hash password (bcrypt / argon) WAJIB panjang
            $table->string('password', 255);

            // Default Laravel = 100
            $table->string('remember_token', 100)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
