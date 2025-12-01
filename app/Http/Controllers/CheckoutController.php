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
        // Validasi input
        $request->validate([
            'table_number' => 'required|string',
            'device_token' => 'required|string',
        ]);

        Log::info('Checkout request received', $request->all());

        // Ambil cart berdasarkan meja & device
        $cartItems = Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->with('menu')
            ->get();

        Log::info('Cart items fetched', ['count' => $cartItems->count()]);

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty', ['table' => $request->table_number, 'device' => $request->device_token]);
            return redirect()->back()->with('error', 'Cart masih kosong!');
        }

        // Hitung total harga
        $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        Log::info('Total cart amount', ['total' => $total]);

        // Buat transaksi utama
        $transaction = Transaction::create([
            'user_id'        => auth()->id(),
            'total_amount'   => $total,
            'payment_method' => 'qris',
            'status'         => 'pending',
        ]);

        Log::info('Transaction created', ['transaction_id' => $transaction->id]);

        // Simpan item-item pesanan
        foreach ($cartItems as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'menu_id'        => $item->menu_id,
                'temperature'    => $item->temperature,
                'size'           => $item->size,
                'ice_level'      => $item->ice_level,
                'sugar_level'    => $item->sugar_level,
                'quantity'       => $item->quantity,
                'price'          => $item->price,
            ]);
        }

        Log::info('Transaction items saved', ['transaction_id' => $transaction->id, 'items_count' => $cartItems->count()]);

        // Bersihkan cart setelah checkout
        Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->delete();

        Log::info('Cart cleared', ['table' => $request->table_number, 'device' => $request->device_token]);

        // =============================
        // 🔥 BUAT INVOICE XENDIT QRIS
        // =============================
        $params = [
            'external_id' => 'order-' . $transaction->id,
            'amount' => $total,
            'description' => 'Pembayaran kopi meja ' . $request->table_number,
            'invoice_duration' => 3600, // 1 jam
            'success_redirect_url' => url('/payment/success/' . $transaction->id),
            'failure_redirect_url' => url('/payment/failed/' . $transaction->id),
            'currency' => 'IDR',
            'payment_methods' => ['QRIS'],
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice($params);

            Log::info('Invoice created', [
                'invoice_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url'],
                'transaction_id' => $transaction->id
            ]);

            // Simpan URL pembayaran & invoice id ke DB
            $transaction->update([
                'payment_url' => $invoice['invoice_url'],
                'transaction_id' => $invoice['id'], // pastikan kolom transaction_id untuk Xendit ID
            ]);

            Log::info('Transaction updated with invoice info', [
                'transaction_id' => $transaction->id,
                'invoice_id' => $invoice['id']
            ]);

            // redirect ke halaman Xendit QRIS
            return redirect($invoice['invoice_url']);

        } catch (\Exception $e) {
            Log::error('Xendit Error: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'params' => $params
            ]);
            return redirect()->back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }
}
