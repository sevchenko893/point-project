<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

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

        // Buat invoice QRIS
        $params = [
            'external_id' => 'order-' . $transaction->id,
            'amount' => $total,
            'description' => 'Pembayaran kopi meja ' . $request->table_number,
            'invoice_duration' => 3600,
            'success_redirect_url' => route('payment.success', $transaction->id),
            'failure_redirect_url' => route('payment.failed', $transaction->id),
            'currency' => 'IDR',
            'payment_methods' => ['QRIS'],
        ];

        try {
            $invoice = $this->invoiceApi->createInvoice($params);

            $transaction->update([
                'payment_url' => $invoice['invoice_url'],
                'transaction_id' => $invoice['id'],
            ]);

            return redirect($invoice['invoice_url']);


        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'table_number' => 'required|string',
    //         'device_token' => 'required|string',
    //     ]);

    //     // Ambil cart berdasarkan meja & device
    //     $cartItems = Cart::where('table_number', $request->table_number)
    //         ->where('device_token', $request->device_token)
    //         ->with('menu')
    //         ->get();

    //     if ($cartItems->isEmpty()) {
    //         return redirect()->back()->with('error', 'Cart masih kosong!');
    //     }

    //     // Hitung total harga
    //     $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

    //     // Buat transaksi utama
    //     $transaction = Transaction::create([
    //         'user_id'        => auth()->id(),
    //         'total_amount'   => $total,
    //         'payment_method' => 'qris',
    //         'status'         => 'pending',
    //     ]);

    //     // Simpan item-item pesanan
    //     foreach ($cartItems as $item) {
    //         TransactionItem::create([
    //             'transaction_id' => $transaction->id,
    //             'menu_id'        => $item->menu_id,
    //             'temperature'    => $item->temperature,
    //             'size'           => $item->size,
    //             'ice_level'      => $item->ice_level,
    //             'sugar_level'    => $item->sugar_level,
    //             'quantity'       => $item->quantity,
    //             'price'          => $item->price,
    //         ]);
    //     }

    //     // Bersihkan cart setelah checkout
    //     Cart::where('table_number', $request->table_number)
    //         ->where('device_token', $request->device_token)
    //         ->delete();

    //     // =============================
    //     // 🔥 BUAT INVOICE XENDIT v3 (PaymentRequest API)
    //     // =============================
    //     $params = [
    //         'external_id' => 'order-' . $transaction->id,
    //         'amount' => $total,
    //         'description' => 'Pembayaran kopi meja ' . $request->table_number,
    //         'invoice_duration' => 3600, // 1 jam
    //         'success_redirect_url' => url('/payment/success/' . $transaction->id),
    //         'failure_redirect_url' => url('/payment/failed/' . $transaction->id),
    //         'currency' => 'IDR',
    //         'payment_methods' => ['QRIS'],
    //     ];

    //     try {
    //         $invoice = $this->invoiceApi->createInvoice($params);

    //         // Simpan URL pembayaran ke DB
    //         $transaction->update([
    //             'payment_url' => $invoice['invoice_url'],
    //             'transaction_id' => $invoice['id'],
    //         ]);

    //         return redirect($invoice['invoice_url']);
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
    //     }
    // }
}
