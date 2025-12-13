<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransactionsExport implements FromArray, WithTitle
{
    public function array(): array
    {
        $rows = [];

        $transactions = Transaction::with([
            'items.menu',
            'items.sizeOption',
            'items.temperatureOption',
            'items.sugarOption',
        ])->orderBy('created_at', 'desc')->get();

        foreach ($transactions as $transaction) {

            // ===== TRANSACTION HEADER =====
            $rows[] = ['Transaction ID', $transaction->id];
            $rows[] = ['Customer', $transaction->customer_name ?? '-'];
            $rows[] = ['Table', $transaction->table_number ?? '-'];
            $rows[] = ['Payment', $transaction->payment_method];
            $rows[] = ['Status', $transaction->status];
            $rows[] = ['Total Amount', $transaction->total_amount];
            $rows[] = ['Created At', $transaction->created_at];
            $rows[] = []; // spacer

            // ===== ITEMS HEADER =====
            $rows[] = [
                '#',
                'Menu',
                'Qty',
                'Base Price',
                'Total Price',
                'Size',
                'Temperature',
                'Sugar'
            ];

            foreach ($transaction->items as $i => $item) {
                $basePrice  = $item->menu->base_price ?? 0;
                $totalPrice = $basePrice * $item->quantity;

                $rows[] = [
                    $i + 1,
                    $item->menu->name ?? '-',
                    $item->quantity,
                    $basePrice,
                    $totalPrice,
                    $item->sizeOption->name ?? '-',
                    $item->temperatureOption->name ?? '-',
                    $item->sugarOption->name ?? '-',
                ];
            }

            // ===== SPACE BETWEEN TRANSACTIONS =====
            $rows[] = [];
            $rows[] = [];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'All Transactions';
    }
}
