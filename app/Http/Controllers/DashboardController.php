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
        // Total penjualan keseluruhan
        $totalRevenue = Transaction::where('status', 'paid')
            ->sum('total_amount');

        // Penjualan hari ini
        $todaySales = Transaction::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Penjualan per bulan (12 bulan)
        $monthlySales = Transaction::select(
                DB::raw('SUM(total_amount) as total'),
                DB::raw('MONTH(created_at) as month')
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = $monthlySales->pluck('month');
        $totals = $monthlySales->pluck('total');

        return view('dashboard', compact(
            'totalRevenue',
            'todaySales',
            'months',
            'totals'
        ));
    }
}
