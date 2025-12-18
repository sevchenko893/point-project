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

            // Nama menu max ±60 karakter
            $table->string('name', 60);

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            // Harga < 1 juta → INT lebih efisien
            $table->integer('base_price');

            $table->text('description')->nullable();

            // Path relatif gambar
            $table->string('photo_path', 150)->nullable();

            $table->enum('status', ['available', 'unavailable'])
                ->default('available');

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};