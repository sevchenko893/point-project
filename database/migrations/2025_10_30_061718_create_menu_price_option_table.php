<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_price_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->enum('temperature', ['hot', 'ice'])->default('ice');
            $table->enum('size', ['normal', 'large'])->default('normal');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->unique(['menu_id', 'size', 'temperature']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_price_options');
    }
};