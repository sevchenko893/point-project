<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('menu_id')
                ->constrained()
                ->cascadeOnDelete();

            // STRING SAJA, DIPERKECIL
            $table->string('temperature', 10)->nullable();   // hot, ice
            $table->string('size', 20)->nullable();          // small, normal, large
            $table->string('ice_level', 20)->nullable();     // no_ice, less, normal
            $table->string('sugar_level', 20)->nullable();   // no_sugar, less, normal

            $table->integer('quantity')->default(1);
            $table->integer('price');

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};