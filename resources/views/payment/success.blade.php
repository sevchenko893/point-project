@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <div class="card shadow-sm p-4">
        <h3 class="text-success mb-3"><i class="fa fa-check-circle"></i> Pembayaran Berhasil!</h3>
        <p>Terima kasih telah melakukan pemesanan.</p>

        <h5>No Transaksi: #{{ $transaction->id }}</h5>
        <p>Total: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>

        <a href="{{ route('menu.index') }}" class="btn btn-dark mt-3">Kembali ke Menu</a>
    </div>
</div>
@endsection
