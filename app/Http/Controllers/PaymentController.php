<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show($transactionId)
    {
        $transaction = Transaction::with('items.menu')->findOrFail($transactionId);

        Log::info('Payment show page opened', ['transaction_id' => $transactionId]);

        return view('payment.show', compact('transaction'));
    }

    public function pay(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        Log::info('Payment attempt', [
            'transaction_id' => $transactionId,
            'payment_method' => $request->payment_method ?? 'cash'
        ]);

        // Contoh sederhana: ubah status ke 'paid'
        $transaction->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method ?? 'cash',
        ]);

        Log::info('Transaction marked as paid manually', [
            'transaction_id' => $transactionId,
            'payment_method' => $transaction->payment_method,
            'status' => $transaction->status
        ]);

        return redirect()
            ->route('payment.success', ['transaction' => $transaction->id])
            ->with('success', 'Pembayaran berhasil!');
    }

    public function success($transactionId)
    {
        $transaction = Transaction::with('items.menu')->findOrFail($transactionId);

        Log::info('Payment success page opened', ['transaction_id' => $transactionId, 'status' => $transaction->status]);

        return view('payment.success', compact('transaction'));
    }
}
