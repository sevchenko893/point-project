<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->enum('ice_level', ['normal', 'less'])->default('normal');
            $table->enum('sugar_level', ['normal', 'less'])->default('normal');
            $table->enum('size', ['normal', 'large'])->default('normal');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // total harga item
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};