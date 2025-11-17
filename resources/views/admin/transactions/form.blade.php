@extends('layouts.admin')

@section('title', isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi')

@section('content')
<h2>{{ isset($transaction) ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ isset($transaction) ? route('transactions.update', $transaction->id) : route('transactions.store') }}" method="POST">
    @csrf
    @if(isset($transaction)) @method('PUT') @endif

    {{-- USER --}}
    <div class="mb-3">
        <label for="user_id" class="form-label">User</label>
        <select name="user_id" class="form-control">
            <option value="">Pilih User (opsional)</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ isset($transaction) && $transaction->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- PAYMENT --}}
    <div class="mb-3">
        <label for="payment_method" class="form-label">Metode Pembayaran</label>
        <select name="payment_method" class="form-control">
            <option value="">Pilih metode</option>
            <option value="cash" {{ isset($transaction) && $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ isset($transaction) && $transaction->payment_method == 'card' ? 'selected' : '' }}>Card</option>
            <option value="qris" {{ isset($transaction) && $transaction->payment_method == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
    </div>

    {{-- STATUS --}}
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-control" required>
            <option value="pending" {{ isset($transaction) && $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ isset($transaction) && $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="cancelled" {{ isset($transaction) && $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>

    <hr>

    {{-- ITEMS --}}
    <h4>Menu Items</h4>
    <table class="table table-bordered" id="itemsTable">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Temperature</th>
                <th>Size</th>
                <th>Ice Level</th>
                <th>Sugar Level</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($transaction))
                @foreach($transaction->items as $item)
                <tr>
                    <td>
                        <select name="items[{{ $loop->index }}][menu_id]" class="form-control" required>
                            @foreach($menus as $menu)
                            <option value="{{ $menu->id }}" {{ $menu->id == $item->menu_id ? 'selected' : '' }}>{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="items[{{ $loop->index }}][temperature]" class="form-control" value="{{ $item->temperature }}"></td>
                    <td><input type="text" name="items[{{ $loop->index }}][size]" class="form-control" value="{{ $item->size }}"></td>
                    <td><input type="text" name="items[{{ $loop->index }}][ice_level]" class="form-control" value="{{ $item->ice_level }}"></td>
                    <td><input type="text" name="items[{{ $loop->index }}][sugar_level]" class="form-control" value="{{ $item->sugar_level }}"></td>
                    <td><input type="number" name="items[{{ $loop->index }}][quantity]" class="form-control" value="{{ $item->quantity }}" required></td>
                    <td><input type="number" name="items[{{ $loop->index }}][price]" class="form-control" value="{{ $item->price }}" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm removeRow">Hapus</button></td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-secondary mb-3" id="addItem">Tambah Item</button>

    <button type="submit" class="btn btn-success">{{ isset($transaction) ? 'Update' : 'Simpan' }}</button>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
</form>

@endsection

@section('scripts')
<script>
let index = {{ isset($transaction) ? $transaction->items->count() : 0 }};

document.getElementById('addItem').addEventListener('click', function() {
    const tbody = document.querySelector('#itemsTable tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[\${index}][menu_id]" class="form-control" required>
                @foreach($menus as $menu)
                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="items[\${index}][temperature]" class="form-control"></td>
        <td><input type="text" name="items[\${index}][size]" class="form-control"></td>
        <td><input type="text" name="items[\${index}][ice_level]" class="form-control"></td>
        <td><input type="text" name="items[\${index}][sugar_level]" class="form-control"></td>
        <td><input type="number" name="items[\${index}][quantity]" class="form-control" value="1" required></td>
        <td><input type="number" name="items[\${index}][price]" class="form-control" value="0" required></td>
        <td><button type="button" class="btn btn-danger btn-sm removeRow">Hapus</button></td>
    `;
    tbody.appendChild(row);
    index++;
});

document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('removeRow')){
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
