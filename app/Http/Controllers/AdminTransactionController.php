<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Menu;
use App\Models\MenuPriceOption; // Jika menu price opsi ada
use App\Models\Size;
use App\Models\Sugar;
use App\Models\Temperature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user', 'items.menu')->orderBy('created_at','desc')->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Load user dan items beserta menu
        $transaction->load('user', 'items.menu');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function create()
{

    $menus = Menu::all();
    $sizes = Size::all();
    $temperatures = Temperature::all();
    $sugars = Sugar::all();

    return view('admin.transactions.create', compact('menus', 'sizes', 'temperatures', 'sugars'));
}

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id'        => 'nullable|exists:users,id',
    //         'payment_method' => 'nullable|in:cash,card,qris',
    //         'status'         => 'required|in:pending,paid,cancelled',
    //         'items'          => 'required|array|min:1',
    //         'items.*.menu_id' => 'required|exists:menus,id',
    //         'items.*.quantity'=> 'required|integer|min:1',
    //         'items.*.price'   => 'required|integer|min:0',
    //     ]);

    //     $transaction = Transaction::create([
    //         'user_id' => $request->user_id,
    //         'total_amount' => array_sum(array_map(fn($i)=> $i['price']*$i['quantity'],$request->items)),
    //         'payment_method' => $request->payment_method,
    //         'status' => $request->status,
    //     ]);

    //     foreach($request->items as $item){
    //         $transaction->items()->create($item);
    //     }

    //     return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    // }

    public function store(Request $request)
{
    // return 1;
    $request->validate([
        'customer_name' => 'required|string',
        'table_number' => 'required|string',
        'menus' => 'required|array',
        'menus.*.menu_id' => 'required|exists:menus,id',
        'menus.*.quantity' => 'required|integer|min:1',
        'menus.*.size' => 'nullable|string',
        'menus.*.temperature' => 'nullable|string',
        'menus.*.sugar_level' => 'nullable|string',
    ]);

    $totalAmount = 0;

    // Buat transaksi utama
    $transaction = Transaction::create([
        'user_id' => auth()->id(),
        'customer_name' => $request->customer_name,
        'table_number' => $request->table_number,
        'payment_method' => 'cash', // Admin manual
        'status' => 'paid', // Admin anggap langsung paid
        'total_amount' => 0, // diupdate nanti
    ]);

    foreach ($request->menus as $item) {

        $menu = Menu::findOrFail($item['menu_id']);

        // Harga dasar
        $subtotal = $menu->base_price;

        // Tambah harga size
        if (!empty($item['size'])) {
            $size = Size::where('name', $item['size'])->first();
            if ($size) {
                $subtotal += $size->price;
            }
        }

        // Tambah harga temperature
        if (!empty($item['temperature'])) {
            $temp = Temperature::where('name', $item['temperature'])->first();
            if ($temp) {
                $subtotal += $temp->price;
            }
        }

        // Tambah harga sugar level
        if (!empty($item['sugar_level'])) {
            $sugar = Sugar::where('name', $item['sugar_level'])->first();
            if ($sugar) {
                $subtotal += $sugar->price;
            }
        }

        // Hitung berdasarkan quantity
        $subtotal = $subtotal * $item['quantity'];

        // Tambahkan ke total transaksi
        $totalAmount += $subtotal;

        // Insert item transaksi
        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'menu_id' => $menu->id,
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'size' => $item['size'] ?? null,
            'temperature' => $item['temperature'] ?? null,
            'sugar_level' => $item['sugar_level'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $subtotal,
        ]);
    }

    // Update total transaksi
    $transaction->update([
        'total_amount' => $totalAmount
    ]);

    return redirect()
        ->route('transactions.index')
        ->with('success', 'Transaksi berhasil dibuat!');
}



    public function edit(Transaction $transaction)
    {
        $users = User::all();
        $menus = Menu::all();
        $transaction->load('items');
        return view('admin.transactions.edit', compact('transaction', 'users', 'menus'));
    }

    // public function update(Request $request, Transaction $transaction)
    // {
    //     $request->validate([
    //         'user_id'        => 'nullable|exists:users,id',
    //         'payment_method' => 'nullable|in:cash,card,qris',
    //         'status'         => 'required|in:pending,paid,cancelled,completed,processing',
    //         'items'          => 'required|array|min:1',
    //         'items.*.menu_id' => 'required|exists:menus,id',
    //         'items.*.quantity'=> 'required|integer|min:1',
    //         'items.*.price'   => 'required|integer|min:0',
    //     ]);

    //     $transaction->update([
    //         'user_id' => $request->user_id,
    //         'total_amount' => array_sum(array_map(fn($i)=> $i['price']*$i['quantity'],$request->items)),
    //         'payment_method' => $request->payment_method,
    //         'status' => $request->status,
    //     ]);

    //     // Hapus item lama dan insert baru
    //     $transaction->items()->delete();
    //     foreach($request->items as $item){
    //         $transaction->items()->create($item);
    //     }

    //     return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    // }


    public function update(Request $request, Transaction $transaction)
{
    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'payment_method' => 'nullable|in:cash,card,qris',
        'status' => 'required|in:pending,processing,paid,completed,cancelled',
        'items' => 'required|array',
        'items.*.menu_id' => 'required|exists:menus,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|integer|min:0',
        'items.*.temperature' => 'nullable|string',
        'items.*.size' => 'nullable|string',
        'items.*.ice_level' => 'nullable|string',
        'items.*.sugar_level' => 'nullable|string',
    ]);

    $transaction->update([
        'user_id' => $request->user_id,
        'payment_method' => $request->payment_method,
        'status' => $request->status,
    ]);

    $total = 0;

    // Update Transaction Items
    foreach ($request->items as $index => $itemData) {
        $item = $transaction->items[$index];
        $item->update([
            'menu_id'     => $itemData['menu_id'],
            'quantity'    => $itemData['quantity'],
            'price'       => $itemData['price'],
            'temperature' => $itemData['temperature'] ?? null,
            'size'        => $itemData['size'] ?? null,
            'ice_level'   => $itemData['ice_level'] ?? null,
            'sugar_level' => $itemData['sugar_level'] ?? null,
        ]);

        // Hitung total harga
        $total += $itemData['price'] * $itemData['quantity'];
    }

    // Update total_amount di transactions
    $transaction->total_amount = $total;
    $transaction->save();

    return redirect()->route('transactions.index')->with('success', 'Transaction successfully updated.');
}

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
