<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sugar_levels', function (Blueprint $table) {
            $table->id();

            // Normal, Less Sugar, No Sugar
            $table->string('name', 30);

            $table->decimal('extra_price', 10, 2)->default(0);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('sugar_levels');
    }
};
