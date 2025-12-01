<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Log payload dengan channel stack
        Log::channel('stack')->info('Xendit Webhook received', $data);
        Log::channel('stack')->info('Xendit debug', $request->all());
        Log::channel('stack')->info('Xendit request', $request());

        // Optional: validasi callback token
        $token = $request->header('X-CALLBACK-TOKEN');
        if ($token !== config('services.xendit.webhook_token')) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        // Cari transaksi berdasarkan Xendit invoice ID
        $transaction = Transaction::where('transaction_id', $data['id'])->first();

        if (!$transaction) {
            Log::channel('stack')->warning('Transaction not found for Xendit Webhook', $data);
            return response()->json(['status' => 'transaction not found'], 404);
        }

        // Update status
        if (isset($data['status'])) {
            switch (strtoupper($data['status'])) {
                case 'PAID':
                    $transaction->update(['status' => 'paid']);
                    Log::channel('stack')->info('Transaction marked as paid', ['transaction_id' => $transaction->id]);
                    break;
                case 'EXPIRED':
                    $transaction->update(['status' => 'expired']);
                    Log::channel('stack')->info('Transaction marked as expired', ['transaction_id' => $transaction->id]);
                    break;
                case 'FAILED':
                    $transaction->update(['status' => 'failed']);
                    Log::channel('stack')->info('Transaction marked as failed', ['transaction_id' => $transaction->id]);
                    break;
                default:
                    Log::channel('stack')->info('Unknown status received', ['status' => $data['status'], 'transaction_id' => $transaction->id]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
