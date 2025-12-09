<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Nomor meja — wajib diisi
            $table->string('table_number');
            $table->string('customer_name')->nullable(); // bisa kosong


            // Token unik per perangkat (disimpan di browser lokal)
            $table->string('device_token')->index();

            // Menu yang dipilih
            $table->foreignId('menu_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Custom options
            $table->string('temperature')->nullable();
            $table->string('size')->nullable();
            $table->string('ice_level')->nullable();
            $table->string('sugar_level')->nullable();

            // Jumlah dan harga
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamps();

            // Pastikan satu perangkat tidak duplikat item menu yang sama
            $table->unique(['device_token', 'menu_id', 'table_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
