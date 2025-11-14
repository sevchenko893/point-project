<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        $transaction = Transaction::where('transaction_id', $data['id'])->first();
        if(!$transaction) return response()->json(['status' => 'transaction not found'], 404);

        if($data['status'] === 'PAID') {
            $transaction->update(['status' => 'paid']);
        } elseif($data['status'] === 'EXPIRED') {
            $transaction->update(['status' => 'expired']);
        }

        return response()->json(['status' => 'ok']);
    }
}
