@extends('layouts.admin')

@section('title','Daftar Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Transaksi</h2>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Tambah Transaksi</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped bg-white shadow-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->user->name ?? '-' }}</td>
                <td>Rp {{ number_format($t->total_amount,0,',','.') }}</td>
                <td>{{ ucfirst($t->payment_method ?? '-') }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>
                    <a href="{{ route('transactions.edit',$t->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('transactions.destroy',$t->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada transaksi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
