@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🛒 Cart Meja {{ $table_number ?? 'Unknown' }}</h2>

    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Belum ada item di cart.
        </div>
    @else
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Menu</th>
                    <th>Name</th>
                    <th>Opsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->customer_name }}</td>

                        <td>
                            @if($item->temperature) <span class="badge bg-primary">{{ $item->temperature }}</span> @endif
                            @if($item->size) <span class="badge bg-info">{{ $item->size }}</span> @endif
                            @if($item->ice_level) <span class="badge bg-success">{{ $item->ice_level }}</span> @endif
                            @if($item->sugar_level) <span class="badge bg-warning">{{ $item->sugar_level }}</span> @endif
                        </td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="4" class="text-end">Total:</th>
                    <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="text-end mt-3">
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="table_number" value="{{ $table_number }}">
                <input type="hidden" name="device_token" value="{{ $device_token }}">
                <input type="hidden" name="customer_name" value="{{ $cartItems->first()->customer_name ?? '' }}">
                <button class="btn btn-success btn-lg">Checkout Sekarang</button>
            </form>
        </div>

    @endif
</div>
@endsection
