<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user', 'items.menu')->orderBy('created_at','desc')->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $users = User::all();
        $menus = Menu::all();
        return view('admin.transactions.create', compact('users', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'nullable|exists:users,id',
            'payment_method' => 'nullable|in:cash,card,qris',
            'status'         => 'required|in:pending,paid,cancelled',
            'items'          => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity'=> 'required|integer|min:1',
            'items.*.price'   => 'required|integer|min:0',
        ]);

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'total_amount' => array_sum(array_map(fn($i)=> $i['price']*$i['quantity'],$request->items)),
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        foreach($request->items as $item){
            $transaction->items()->create($item);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
    {
        $users = User::all();
        $menus = Menu::all();
        $transaction->load('items');
        return view('admin.transactions.edit', compact('transaction', 'users', 'menus'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'user_id'        => 'nullable|exists:users,id',
            'payment_method' => 'nullable|in:cash,card,qris',
            'status'         => 'required|in:pending,paid,cancelled',
            'items'          => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity'=> 'required|integer|min:1',
            'items.*.price'   => 'required|integer|min:0',
        ]);

        $transaction->update([
            'user_id' => $request->user_id,
            'total_amount' => array_sum(array_map(fn($i)=> $i['price']*$i['quantity'],$request->items)),
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        // Hapus item lama dan insert baru
        $transaction->items()->delete();
        foreach($request->items as $item){
            $transaction->items()->create($item);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
