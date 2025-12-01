<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'device_token' => 'required|string',
        ]);

        $cartItems = Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->with('menu')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Cart masih kosong!');
        }

        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'payment_method' => 'qris',
            'status' => 'pending',
        ]);

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

        Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->delete();

        // =========================
        // Buat invoice Xendit
        // =========================
        $params = [
            'external_id' => 'order-' . $transaction->id,
            'amount' => $total,
            'description' => 'Pembayaran meja ' . $request->table_number,
            'invoice_duration' => 3600,
            'success_redirect_url' => url('/payment/success/' . $transaction->id),
            'failure_redirect_url' => url('/payment/failed/' . $transaction->id),
            'currency' => 'IDR',
            'payment_methods' => ['QRIS'],
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice($params);

            $transaction->update([
                'payment_url' => $invoice['invoice_url'],
                'xendit_id' => $invoice['id'],
            ]);

            Log::info('Invoice created', ['invoice_id' => $invoice['id'], 'transaction_id' => $transaction->id]);

            return redirect($invoice['invoice_url']);
        } catch (\Exception $e) {
            Log::error('Xendit Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }
}
