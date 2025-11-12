@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Pembayaran</h4>
        </div>
        <div class="card-body">
            <h5>No Transaksi: #{{ $transaction->id }}</h5>
            <p class="text-muted mb-4">Status:
                <span class="badge bg-warning text-dark">{{ ucfirst($transaction->status) }}</span>
            </p>

            <h6>Detail Pesanan:</h6>
            <ul class="list-group mb-3">
                @foreach($transaction->items as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            {{ $item->menu->name }}
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>
                        <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>

            <h5 class="text-end fw-bold">
                Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </h5>

            <form method="POST" action="{{ route('payment.pay', $transaction->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Pilih Metode Pembayaran:</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        {{-- <option value="cash">Bayar Tunai</option> --}}
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-dark w-100">Bayar Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection
