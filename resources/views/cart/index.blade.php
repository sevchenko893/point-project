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
                    <tr data-row="{{ $item->id }}">
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->customer_name }}</td>

                        <td>
                            @if($item->temperature) <span class="badge bg-primary">{{ $item->temperature }}</span> @endif
                            @if($item->size) <span class="badge bg-info">{{ $item->size }}</span> @endif
                            @if($item->ice_level) <span class="badge bg-success">{{ $item->ice_level }}</span> @endif
                            @if($item->sugar_level) <span class="badge bg-warning">{{ $item->sugar_level }}</span> @endif
                        </td>

                        <!-- QTY AJAX FIELD -->
                        <td>
                            <div class="d-flex align-items-center">
                                <input
                                    type="number"
                                    min="1"
                                    value="{{ $item->quantity }}"
                                    class="form-control form-control-sm me-2 qty-input"
                                    style="width: 70px;"
                                    data-id="{{ $item->id }}"
                                    data-price="{{ $item->price }}"
                                >
                                <button class="btn btn-sm btn-primary update-btn" data-id="{{ $item->id }}">
                                    Update
                                </button>
                            </div>
                        </td>

                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>

                        <!-- TOTAL PER ITEM -->
                        <td class="item-total">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </td>

                        <td>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus item ini?')">
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
                    <th colspan="2" id="cart-total">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </th>
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


{{-- ================== AJAX UPDATE SCRIPT ================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const updateButtons = document.querySelectorAll('.update-btn');

    updateButtons.forEach(btn => {
        btn.addEventListener('click', async function () {

            const id = this.dataset.id;
            const row = document.querySelector(`tr[data-row="${id}"]`);
            const qtyInput = row.querySelector('.qty-input');
            const price = parseInt(qtyInput.dataset.price);
            const quantity = qtyInput.value;

            const response = await fetch(`/cart/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });

            const data = await response.json();

            // Update total per item
            const totalCell = row.querySelector('.item-total');
            totalCell.textContent = "Rp " + (price * quantity).toLocaleString("id-ID");

            // Update total keseluruhan
            updateCartTotal();
        });
    });

    function updateCartTotal() {
        let total = 0;

        document.querySelectorAll('.qty-input').forEach(input => {
            const price = parseInt(input.dataset.price);
            const qty = parseInt(input.value);
            total += price * qty;
        });

        document.getElementById('cart-total').textContent =
            "Rp " + total.toLocaleString("id-ID");
    }

});
</script>

@endsection
