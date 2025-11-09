<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'device_token' => 'required|string',
        ]);

        // Ambil semua item di cart berdasarkan table_number dan device_token
        $cartItems = Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->with('menu')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Cart masih kosong!');
        }

        // Hitung total harga
        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // Buat transaksi baru
        $transaction = Transaction::create([
            'user_id' => auth()->id() ?? null, // jika tidak ada login, boleh null
            'total_amount' => $total,
            'payment_method' => 'cash', // bisa diubah sesuai kebutuhan
            'status' => 'pending',
        ]);

        // Simpan setiap item dari cart ke transaction_items
        foreach ($cartItems as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'menu_id' => $item->menu_id,
                'temperature' => $item->temperature,
                'size' => $item->size,
                'ice_level' => $item->ice_level,
                'sugar_level' => $item->sugar_level,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        // Hapus cart setelah checkout
        Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->delete();

        return redirect()->route('cart.index', [
            'table_number' => $request->table_number,
            'device_token' => $request->device_token,
        ])->with('success', 'Checkout berhasil! Transaksi sedang diproses.');
    }
}
