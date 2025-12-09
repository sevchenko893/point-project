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
            $table->string('table_number')->nullable();
            $table->string('customer_name')->nullable(); // tambahkan nama customer
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('xendit_id')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'qris'])->nullable();
            $table->enum('status', ['pending', 'processing', 'paid', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};