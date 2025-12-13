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
        <td>{{ $transaction->customer_name ?? '-' }}</td>
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
        <th>Date</th>
        <td>{{ $transaction->created_at }}</td>
    </tr>
</table>

<h4>Item Pesanan</h4>

<table class="table table-bordered table-striped align-middle" id="item-table">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Menu</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Base Price</th>
            <th>Size</th>
            <th>Temperature</th>
            <th>Sugar</th>
            <th class="text-end">Total Price</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaction->items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>{{ $item->menu->name ?? '-' }}</td>

            <td class="qty text-center">
                {{ $item->quantity }}
            </td>

            <td class="base-price text-end"
                data-price="{{ $item->menu->base_price }}">
                Rp {{ number_format($item->menu->base_price, 0, ',', '.') }}
            </td>

            <td>{{ $item->sizeOption->name ?? '-' }}</td>
            <td>{{ $item->temperatureOption->name ?? '-' }}</td>
            <td>{{ $item->sugarOption->name ?? '-' }}</td>

            <td class="total-price text-end fw-semibold">
                Rp 0
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">
                Belum ada item.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('#item-table tbody tr').forEach(row => {
        const qtyEl = row.querySelector('.qty');
        const basePriceEl = row.querySelector('.base-price');
        const totalPriceEl = row.querySelector('.total-price');

        if (!qtyEl || !basePriceEl || !totalPriceEl) return;

        const qty = parseInt(qtyEl.innerText);
        const basePrice = parseInt(basePriceEl.dataset.price);

        const total = qty * basePrice;

        totalPriceEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
    });

});
</script>
@endpush
