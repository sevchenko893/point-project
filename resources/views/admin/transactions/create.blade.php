@extends('layouts.admin')

@section('title', 'Tambah Transaksi')

@section('content')
<h2>Tambah Transaksi</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('transactions.store') }}" method="POST">
    @csrf

    {{-- <div class="mb-3">
        <label for="user_id" class="form-label">User</label>
        <select name="user_id" class="form-control">
            <option value="" disabled selected>Pilih User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div> --}}

    <div class="mb-3">
        <label for="customer_name" class="form-label">Nama Customer</label>
        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
    </div>

    <div class="mb-3">
        <label for="total_amount" class="form-label">Total Amount</label>
        <input type="number" step="0.01" name="total_amount" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select name="payment_method" class="form-control">
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="qris">QRIS</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
