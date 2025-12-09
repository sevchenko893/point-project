<?php

namespace Database\Seeders;

use App\Models\MenuPriceOption;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat transaksi contoh
        $transaction = Transaction::create([
            'user_id' => null, // Bisa diisi jika ada user login
            'total_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'paid',
        ]);

        // Ambil beberapa menu variant secara acak
        $variants = MenuPriceOption::inRandomOrder()->take(2)->get();

        $total = 0;

        foreach ($variants as $variant) {
            $quantity = rand(1, 3);
            $subtotal = $variant->price * $quantity;

            // Hapus field yang tidak ada di table
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'table_number' => '1',
                'customer_name' => 'test', // tambahkan ini
                'menu_id' => $variant->menu_id,
                'quantity' => $quantity,
                'price' => $subtotal,
            ]);

            $total += $subtotal;
        }

        $transaction->update(['total_amount' => $total]);
    }
}