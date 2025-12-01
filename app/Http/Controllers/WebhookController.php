<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

    public function handle(Request $request)
    {
        // Log raw body
        Log::info('RAW BODY: ' . $request->getContent());

        // JSON decoded body
        $data = $request->all();
        Log::info('DECODED BODY:', $data);

        // Log headers
        Log::info('HEADERS:', $request->headers->all());

        Log::info('WEBHOOK MASUK!');

        // Token Validation (pastikan sama seperti yang ada di Xendit Dashboard)
        $token = $request->header('X-CALLBACK-TOKEN');
        if ($token !== config('services.xendit.webhook_token')) {
            Log::warning('Unauthorized webhook', $data);
            return response()->json(['status' => 'unauthorized'], 401);
        }

        // Pastikan ada id
        if (!isset($data['id'])) {
            Log::error('Missing invoice id', $data);
            return response()->json(['status' => 'missing id'], 400);
        }

        // Cari transaction berdasarkan xendit_id
        $transaction = Transaction::where('xendit_id', $data['id'])->first();

        if (!$transaction) {
            Log::warning('Transaction not found', ['xendit_id' => $data['id']]);
            return response()->json(['status' => 'transaction not found'], 404);
        }

        // Update status sesuai Xendit
        if (isset($data['status'])) {
            switch (strtoupper($data['status'])) {
                case 'PAID':
                    $transaction->update(['status' => 'paid']);
                    Log::info('Transaction marked as PAID', ['transaction_id' => $transaction->id]);
                    break;

                case 'EXPIRED':
                    $transaction->update(['status' => 'expired']);
                    Log::info('Transaction EXPIRED', ['transaction_id' => $transaction->id]);
                    break;

                case 'FAILED':
                    $transaction->update(['status' => 'failed']);
                    Log::info('Transaction FAILED', ['transaction_id' => $transaction->id]);
                    break;
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // public function handle(Request $request)
    // {
    //     // RAW Body
    //     Log::info('RAW BODY: ' . $request->getContent());

    //     // JSON decoded (array)
    //     $data = $request->all();
    //     Log::info('DECODED BODY:', $data);

    //     // Headers
    //     Log::info('HEADERS:', $request->headers->all());

    //     Log::info('WEBHOOK MASUK!');

    //     // Token Validation
    //     $token = $request->header('X-CALLBACK-TOKEN');
    //     if ($token !== config('services.xendit.webhook_token')) {
    //         Log::warning('Unauthorized webhook', $data);
    //         return response()->json(['status' => 'unauthorized'], 401);
    //     }

    //     // --- PERBAIKI PENTING DISINI ---
    //     if (!isset($data['id'])) {
    //         Log::error('Missing invoice id', $data);
    //         return response()->json(['status' => 'missing id'], 400);
    //     }

    //     // Cari transaction
    //     $transaction = Transaction::where('xendit_id', $data['id'])->first();

    //     if (!$transaction) {
    //         Log::warning('Transaction not found', ['xendit_id' => $data['id']]);
    //         return response()->json(['status' => 'transaction not found'], 404);
    //     }

    //     // Update status
    //     if (isset($data['status'])) {
    //         switch (strtoupper($data['status'])) {
    //             case 'PAID':
    //                 $transaction->update(['status' => 'paid']);
    //                 Log::info('Transaction marked as PAID', ['transaction_id' => $transaction->id]);
    //                 break;

    //             case 'EXPIRED':
    //                 $transaction->update(['status' => 'expired']);
    //                 Log::info('Transaction EXPIRED', ['transaction_id' => $transaction->id]);
    //                 break;

    //             case 'FAILED':
    //                 $transaction->update(['status' => 'failed']);
    //                 Log::info('Transaction FAILED', ['transaction_id' => $transaction->id]);
    //                 break;
    //         }
    //     }

    //     return response()->json(['status' => 'ok']);
    // }


//     public function handle(Request $request)
// {

//     \Log::info('WEBHOOK MASUK!', request()->all());

//     \Log::info('WebhookController@handle dipanggil', $request->all());
// return response()->json(['status' => 'received']);

// }
}
