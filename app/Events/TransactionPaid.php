<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // <--- ini kontrak
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TransactionPaid implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        Log::info('[EVENT] TransactionPaid created', ['transaction_id' => $transaction->id]);
    }

    public function broadcastOn()
    {
        Log::info('[EVENT] TransactionPaid broadcastOn channel', ['channel' => 'transactions']);
        return new Channel('transactions');
    }

    public function broadcastWith()
    {
        $payload = [
            'id' => $this->transaction->id,
            'table_number' => $this->transaction->table_number ?? null,
            'total_amount' => $this->transaction->total_amount,
            'status' => $this->transaction->status,
        ];
        Log::info('[EVENT] TransactionPaid broadcastWith payload', $payload);
        return $payload;
    }
}
