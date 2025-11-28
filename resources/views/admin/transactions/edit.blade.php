@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('content')
<h2>Edit Transaksi</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="user_id" class="form-label">User</label>
        <select name="user_id" class="form-control">
            <option value="" disabled>Pilih User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
            @endforeach
        </select>
        <small class="text-muted">Sebelumnya: {{ $transaction->user->name }}</small>
    </div>

    <div class="mb-3">
        <label for="total_amount" class="form-label">Total Amount</label>
        <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ $transaction->total_amount }}" required>
        <small class="text-muted">Sebelumnya: {{ $transaction->getOriginal('total_amount') }}</small>
    </div>

    <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select name="payment_method" class="form-control">
            <option value="cash" {{ $transaction->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ $transaction->payment_method == 'card' ? 'selected' : '' }}>Card</option>
            <option value="qris" {{ $transaction->payment_method == 'qris' ? 'selected' : '' }}>QRIS</option>
        </select>
        <small class="text-muted">Sebelumnya: {{ $transaction->getOriginal('payment_method') }}</small>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ $transaction->status == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <small class="text-muted">Sebelumnya: {{ $transaction->getOriginal('status') }}</small>
    </div>

    <h4>Transaction Items</h4>
    @foreach($transaction->items as $index => $item)
    <div class="card p-3 mb-2 item-card">
        @php
            $basePrice = $item->menu->base_price ?? 0;
        @endphp
        <input type="hidden" class="item-base-price" value="{{ $basePrice }}">

        <div class="mb-2">
            <label>Menu</label>
            <select name="items[{{ $index }}][menu_id]" class="form-control">
                @foreach($menus as $menu)
                <option value="{{ $menu->id }}" {{ $item->menu_id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Sebelumnya: {{ $item->menu->name ?? '-' }}</small>
        </div>

        <div class="mb-2">
            <label>Quantity</label>
            <input type="number" name="items[{{ $index }}][quantity]" class="form-control item-quantity" value="{{ $item->quantity }}" min="1">
            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('quantity') }}</small>
        </div>

        <div class="mb-2">
            <label>Price</label>
            <input type="number" name="items[{{ $index }}][price]" class="form-control item-price" value="{{ $item->price }}">
            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('price') }}</small>
        </div>

        <div class="mb-2">
            <label>Temperature</label>
            {{-- <select name="items[{{ $index }}][temperature]" class="form-control item-temp">
                <option value="hot" {{ $item->temperature == 'hot' ? 'selected' : '' }}>Hot</option>
                <option value="cold" {{ $item->temperature == 'cold' ? 'selected' : '' }}>Cold</option>
            </select> --}}
            <select name="items[{{ $index }}][temperature]" class="form-control item-temp"
                data-extra-hot="{{ $item->temperature=='hot' ? $item->menu->tempHotPrice ?? 0 : 0 }}"
                data-extra-cold="{{ $item->temperature=='cold' ? $item->menu->tempColdPrice ?? 0 : 0 }}">
                <option value="hot" {{ $item->temperature == 'hot' ? 'selected' : '' }}>Hot</option>
                <option value="cold" {{ $item->temperature == 'cold' ? 'selected' : '' }}>Cold</option>
            </select>
            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('temperature') }}</small>
        </div>

        <div class="mb-2">
            <label>Size</label>
            {{-- <select name="items[{{ $index }}][size]" class="form-control item-size" data-extra-small="0" data-extra-medium="0" data-extra-large="0">
                <option value="small" {{ $item->size == 'small' ? 'selected' : '' }}>Small</option>
                <option value="medium" {{ $item->size == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="large" {{ $item->size == 'large' ? 'selected' : '' }}>Large</option>
            </select> --}}
            <select name="items[{{ $index }}][size]" class="form-control item-size"
                data-extra-small="{{ $item->size=='small' ? $item->menu->sizeSmallPrice ?? 0 : 0 }}"
                data-extra-medium="{{ $item->size=='medium' ? $item->menu->sizeMediumPrice ?? 0 : 0 }}"
                data-extra-large="{{ $item->size=='large' ? $item->menu->sizeLargePrice ?? 0 : 0 }}">
                <option value="small" {{ $item->size == 'small' ? 'selected' : '' }}>Small</option>
                <option value="medium" {{ $item->size == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="large" {{ $item->size == 'large' ? 'selected' : '' }}>Large</option>
            </select>

            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('size') }}</small>
        </div>

        <div class="mb-2">
            <label>Ice Level</label>
            <select name="items[{{ $index }}][ice_level]" class="form-control item-ice">
                <option value="no_ice" {{ $item->ice_level == 'no_ice' ? 'selected' : '' }}>No Ice</option>
                <option value="less" {{ $item->ice_level == 'less' ? 'selected' : '' }}>Less Ice</option>
                <option value="normal" {{ $item->ice_level == 'normal' ? 'selected' : '' }}>Normal Ice</option>
                <option value="extra" {{ $item->ice_level == 'extra' ? 'selected' : '' }}>Extra Ice</option>
            </select>
            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('ice_level') }}</small>
        </div>

        <div class="mb-2">
            <label>Sugar Level</label>
            <select name="items[{{ $index }}][sugar_level]" class="form-control item-sugar">
                <option value="no_sugar" {{ $item->sugar_level == 'no_sugar' ? 'selected' : '' }}>No Sugar</option>
                <option value="less" {{ $item->sugar_level == 'less' ? 'selected' : '' }}>Less Sugar</option>
                <option value="normal" {{ $item->sugar_level == 'normal' ? 'selected' : '' }}>Normal Sugar</option>
                <option value="extra" {{ $item->sugar_level == 'extra' ? 'selected' : '' }}>Extra Sugar</option>
            </select>
            <small class="text-muted">Sebelumnya: {{ $item->getOriginal('sugar_level') }}</small>
        </div>
    </div>
    @endforeach

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    function updateItemPrice(card) {
    const basePrice = parseFloat(card.querySelector('.item-base-price').value) || 0;
    const qty = parseInt(card.querySelector('.item-quantity').value) || 1;
        console.log('qty',qty)
    // Ambil extra price dari size
    const sizeSelect = card.querySelector('.item-size');
    const sizeExtra = parseFloat(sizeSelect.selectedOptions[0].dataset.extraSmall ||
                                sizeSelect.selectedOptions[0].dataset.extraMedium ||
                                sizeSelect.selectedOptions[0].dataset.extraLarge || 0);

    const tempSelect = card.querySelector('.item-temp');
    const tempExtra = parseFloat(tempSelect.selectedOptions[0].dataset.extraHot ||
                                 tempSelect.selectedOptions[0].dataset.extraCold || 0);

    const iceSelect = card.querySelector('.item-ice');
    const iceExtra = parseFloat(iceSelect.selectedOptions[0].dataset.extraNoIce ||
                                iceSelect.selectedOptions[0].dataset.extraLess ||
                                iceSelect.selectedOptions[0].dataset.extraNormal ||
                                iceSelect.selectedOptions[0].dataset.extraExtra || 0);

    const sugarSelect = card.querySelector('.item-sugar');
    const sugarExtra = parseFloat(sugarSelect.selectedOptions[0].dataset.extraNoSugar ||
                                  sugarSelect.selectedOptions[0].dataset.extraLess ||
                                  sugarSelect.selectedOptions[0].dataset.extraNormal ||
                                  sugarSelect.selectedOptions[0].dataset.extraExtra || 0);

    const total = (basePrice + sizeExtra + tempExtra + iceExtra + sugarExtra) * qty;
    card.querySelector('.item-price').value = total;

    updateTransactionTotal();
}

    function updateTransactionTotal() {
        console.log('totalssss')

        let total = 0;
        document.querySelectorAll('.item-price').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        console.log('total',total)

        document.querySelector('[name="total_amount"]').value = total.toFixed(2);
    }

    document.querySelectorAll('.item-card').forEach(card => {
        const qtyInput = card.querySelector('.item-quantity');
        const selects = card.querySelectorAll('select');

        qtyInput.addEventListener('input', () => updateItemPrice(card));
        selects.forEach(sel => sel.addEventListener('change', () => updateItemPrice(card)));

        updateItemPrice(card); // kalkulasi awal
    });
});
</script>
@endpush
