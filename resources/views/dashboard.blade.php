@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 class="fw-bold mb-4">Welcome, {{ auth()->user()->name }}</h2>

    <div class="row">

        <!-- Total Revenue -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Total Penjualan</h5>
                <h2 class="fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>Penjualan Hari Ini</h5>
                <h2 class="fw-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</h2>
            </div>
        </div>

    </div>
<!-- Chart -->
<div class="card mt-4 p-4 shadow-sm">
    <h5 class="mb-3">Penjualan Per Bulan</h5>
    <canvas id="salesChart" height="100"></canvas>
</div>

<!-- Load Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Total Penjualan',
                data: @json($totals),
                borderWidth: 3,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection