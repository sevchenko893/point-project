<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($transactionId)
    {
        $transaction = Transaction::with('items.menu')->findOrFail($transactionId);

        return view('payment.show', compact('transaction'));
    }

    public function pay(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // Contoh sederhana: ubah status ke 'paid'
        $transaction->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method ?? 'cash',
        ]);

        return redirect()
            ->route('payment.success', ['transaction' => $transaction->id])
            ->with('success', 'Pembayaran berhasil!');
    }

    public function success($transactionId)
    {
        $transaction = Transaction::with('items.menu')->findOrFail($transactionId);

        return view('payment.success', compact('transaction'));
    }
}
