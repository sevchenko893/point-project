<?php

use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DashboardController;

// ======================
// AUTH ROUTES
// ======================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


// ======================
// DASHBOARD
// ======================
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


// ======================
// MENU & CART
// ======================
Route::get('/', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

Route::get('/cart/{table_number}/{device_token}', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.add');
Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');


// ======================
// CHECKOUT & PAYMENT
// ======================
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout');

Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{transaction}/pay', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/payment/success/{id}', function($id){
    return view('payment.success', compact('id'));
});

Route::get('/payment/failed/{id}', function($id){
    return view('payment.failed', compact('id'));
});


// ======================
// TABLE SELECT
// ======================
Route::get('/select-table', [TableController::class, 'index'])->name('table.select');
Route::post('/select-table', [TableController::class, 'store'])->name('table.store');


// ======================
// WEBHOOK
// ======================
Route::post('/webhook/xendit', [WebhookController::class, 'handle'])->name('webhook.xendit');

Route::post('/webhook/xendit', [WebhookController::class, 'handle'])->name('webhook.xendit');

Route::prefix('admin')->middleware('auth')->group(function () {


        Route::get('transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create', [AdminTransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [AdminTransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}/edit', [AdminTransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('transactions/{transaction}', [AdminTransactionController::class, 'update'])->name('transactions.update');
        Route::delete('transactions/{transaction}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');
        // Tambahkan route detail
        Route::get('transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');

 Route::prefix('menus')->group(function () {
            Route::get('/', [AdminMenuController::class, 'index'])->name('menus.index');
            Route::get('/create', [AdminMenuController::class, 'create'])->name('menus.create');
            Route::post('/', [AdminMenuController::class, 'store'])->name('menus.store');
            Route::get('/{menu}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
            Route::put('/{menu}', [AdminMenuController::class, 'update'])->name('menus.update');
            Route::delete('/{menu}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
        });
});

