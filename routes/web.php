<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


Route::get('/', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/cart/{table_number}/{device_token}', [CartController::class, 'index'])->name('cart.index');
// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.add');;      // Tambah item
Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

// untuk co
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout');

// web.php
Route::get('/select-table', [TableController::class, 'index'])->name('table.select');
Route::post('/select-table', [TableController::class, 'store'])->name('table.store');


Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{transaction}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
Route::get('/payment/{transaction}/success', [PaymentController::class, 'success'])->name('payment.success');


Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout');
Route::post('/xendit/callback', [CheckoutController::class, 'callback'])->name('xendit.callback');


Route::get('/payment/success/{id}', function($id) {
    return "Pembayaran order #$id berhasil!";
});

Route::get('/payment/failed/{id}', function($id) {
    return "Pembayaran order #$id gagal atau dibatalkan!";
});


// Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Halaman sukses / gagal
Route::get('/payment/success/{id}', function($id){
    return view('payment.success', compact('id'));
})->name('payment.success');

Route::get('/payment/failed/{id}', function($id){
    return view('payment.failed', compact('id'));
})->name('payment.failed');

// Webhook Xendit
Route::post('/webhook/xendit', [WebhookController::class, 'handle'])->name('webhook.xendit');
