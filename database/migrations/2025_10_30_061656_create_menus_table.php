<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            // $table->enum('category', ['coffee', 'non-coffee'])->nullable();
            $table->bigInteger('base_price');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};