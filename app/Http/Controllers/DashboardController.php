<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
{
    $totalRevenue = Transaction::where('status', 'paid')->sum('total_amount');
    $todaySales = Transaction::where('status', 'paid')
        ->whereDate('created_at', Carbon::today())
        ->sum('total_amount');

    // Inisialisasi array 12 bulan
    $months = [];
    $totals = [];
    for ($m = 1; $m <= 12; $m++) {
        $months[] = Carbon::create()->month($m)->format('F'); // nama bulan
        $totals[] = 0; // default 0
    }

    $monthlySales = Transaction::select(
            DB::raw('SUM(total_amount) as total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->where('status', 'paid')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    foreach ($monthlySales as $sale) {
        $totals[$sale->month - 1] = $sale->total; // bulan index 0–11
    }

    return view('dashboard', compact(
        'totalRevenue',
        'todaySales',
        'months',
        'totals'
    ));
}

}
