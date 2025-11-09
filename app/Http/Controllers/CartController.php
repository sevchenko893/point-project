<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan semua item di cart untuk meja + device tertentu.
     */
    // public function index(Request $request)
    // {
    //     $request->validate([
    //         'table_number' => 'required|string',
    //         'device_token' => 'required|string',
    //     ]);

    //     $cartItems = Cart::with('menu')
    //         ->where('table_number', $request->table_number)
    //         ->where('device_token', $request->device_token)
    //         ->get();

    //     // return $cartItems;

    //     $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);
    //     // return 1;
    //     return view('cart.index', [
    //         'table_number' => $request->table_number,
    //         'device_token' => $request->device_token,
    //         'total' => $total,
    //         'cartItems' => $cartItems, // <-- nama variabel "items"
    //     ]);
    // }


    public function index($table_number, $device_token)
{
    $cartItems = Cart::with('menu')
        ->where('table_number', $table_number)
        ->where('device_token', $device_token)
        ->get();

    $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

    return view('cart.index', [
        'table_number' => $table_number,
        'device_token' => $device_token,
        'cartItems' => $cartItems,
        'total' => $total,
    ]);
}
//     public function index(Request $request)
// {
//     $request->validate([
//         'table_number' => 'required|string',
//         'device_token' => 'required|string',
//     ]);

//     $cartItems = \App\Models\Cart::with('menu')
//         ->where('table_number', $request->table_number)
//         ->where('device_token', $request->device_token)
//         ->get();

//     $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

//     return view('cart.index', [
//         'cartItems' => $cartItems,
//         'table_number' => $request->table_number,
//         'device_token' => $request->device_token,
//         'total' => $total,
//     ]);
// }


    /**
     * Menambah item ke cart.
     */
    public function store(Request $request)
    {
        // return $request->price;
        $request->validate([
            'table_number' => 'required|string',
            'device_token' => 'required|string',
            'menu_id' => 'required|exists:menus,id',
            'temperature' => 'nullable|string',
            'size' => 'nullable|string',
            'ice_level' => 'nullable|string',
            'sugar_level' => 'nullable|string',
            'quantity' => 'integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        // Cek apakah item yang sama sudah ada di cart
        $cartItem = Cart::where('table_number', $request->table_number)
            ->where('device_token', $request->device_token)
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            // Tambah item baru
            $cartItem = Cart::create([
                'table_number' => $request->table_number,
                'device_token' => $request->device_token,
                'menu_id' => $request->menu_id,
                'temperature' => $request->temperature,
                'size' => $request->size,
                'ice_level' => $request->ice_level,
                'sugar_level' => $request->sugar_level,
                'quantity' => $request->input('quantity', 1),
                'price' => $menu['base_price']
            ]);
        }

        return redirect()->route('cart.index', [
            'table_number' => $request->table_number,
            'device_token' => $request->device_token,
        ])->with('success', 'Item berhasil ditambahkan ke cart!');
    }

    /**
     * Update item di cart.
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'integer|min:1',
            'temperature' => 'nullable|string',
            'size' => 'nullable|string',
            'ice_level' => 'nullable|string',
            'sugar_level' => 'nullable|string',
        ]);

        $cart->update($request->only([
            'quantity',
            'temperature',
            'size',
            'ice_level',
            'sugar_level',
        ]));

        return response()->json([
            'message' => 'Item berhasil diupdate',
            'cart_item' => $cart
        ]);
    }

    /**
     * Hapus item dari cart.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus'
        ]);
    }
}
