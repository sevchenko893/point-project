<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temperature_options', function (Blueprint $table) {
            $table->id();

            // Hot / Ice
            $table->string('name', 10);

            $table->decimal('extra_price', 10, 2)->default(0);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('temperature_options');
    }
};
