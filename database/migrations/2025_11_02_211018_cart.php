<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->string('table_number', 10);
            $table->string('customer_name', 100)->nullable();

            $table->string('device_token', 100)->index();

            $table->foreignId('menu_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('temperature', 10)->nullable();
            $table->string('size', 20)->nullable();
            $table->string('ice_level', 20)->nullable();
            $table->string('sugar_level', 20)->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);

            $table->timestamps();

            $table->unique(['device_token', 'menu_id', 'table_number']);
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
