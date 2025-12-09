
@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<h2>Detail Transaksi #{{ $transaction->id }}</h2>

<div class="mb-3">
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<table class="table table-bordered">

    <tr>
        <th>User</th>
        <td>{{ $transaction->user->name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Total Amount</th>
        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <th>Payment Method</th>
        <td>{{ ucfirst($transaction->payment_method ?? '-') }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ ucfirst($transaction->status) }}</td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ $transaction->created_at }}</td>
    </tr>
</table>

<h4>Item Pesanan</h4>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Menu</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Size</th>
            <th>Temperature</th>
            <th>Sugar</th>
            <th>Ice</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaction->items as $item)
        {{$item}}
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->menu->name ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>{{ $item->size ?? '-' }}</td>
            <td>{{ $item->temperature ?? '-' }}</td>
            <td>{{ $item->sugar_level ?? '-' }}</td>
            <td>{{ $item->ice_level ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Belum ada item.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
