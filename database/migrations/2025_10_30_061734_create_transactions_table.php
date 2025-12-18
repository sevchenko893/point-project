<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('table_number', 10)->nullable();
            $table->string('customer_name', 100)->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('xendit_id', 50)->nullable();

            $table->decimal('total_amount', 10, 2)->default(0);

            $table->string('payment_method', 20)->nullable(); // cash, card, qris
            $table->string('status', 20)->default('pending');

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};