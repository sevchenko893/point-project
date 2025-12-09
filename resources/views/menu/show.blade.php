@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="menu-detail p-4 rounded shadow-sm bg-white mx-auto" style="max-width:500px;">
        <h2 class="fw-bold text-dark">{{ $menu->name }}</h2>
        <p class="text-muted">Deskripsi: {{ $menu->description ?? '-' }}</p>

        <form id="menuForm" method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
            <input type="hidden" id="priceInput" name="price" value="{{ $menu->base_price }}">
            <input type="hidden" name="device_token" value="{{ request()->cookie('device_token') ?? Str::uuid() }}">

            {{-- Table Number --}}
            {{-- <div class="mb-3">
                <label for="table_number" class="form-label">Table Number </label>
                <input type="text" id="table_number" name="table_number" class="form-control" placeholder="Input Table Number" required>
            </div> --}}
            {{-- Table Number --}}
            <div class="mb-3">
                <label for="customer_name" class="form-label">Name</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control">
            </div>
            @if(session('table_number'))
            {{-- Jika sudah ada di session, simpan sebagai hidden --}}
            <input type="hidden" name="table_number" value="{{ session('table_number') }}">
            <input type="hidden" name="device_token" value="{{ session('device_token') }}">
            <div class="alert alert-secondary py-2">
                <small>
                    <i class="fa fa-chair"></i> Table: <strong>{{ session('table_number') }}</strong> <br>
                    <i class="fa fa-mobile-screen-button"></i> Device: <strong>{{ Str::limit(session('device_token'), 8, '...') }}</strong>
                </small>
            </div>
            @else
            {{-- Jika belum ada session, tampilkan input manual --}}
            <div class="mb-3">
                <label for="table_number" class="form-label">Table Number</label>
                <input type="text" id="table_number" name="table_number" class="form-control" placeholder="Input Table Number" required>
            </div>

            <input type="hidden" name="device_token" value="{{ request()->cookie('device_token') ?? Str::uuid() }}">
            @endif


            {{-- Temperature --}}
            <div class="mb-3">
                <label for="temperature" class="form-label">Ice Level</label>
                <select id="temperature" name="temperature" class="form-select"
                    {{ strtolower($menu->category) === 'non-coffee' ? 'disabled' : '' }}>
                    @foreach($temperatures as $temp)
                        @if(strtolower($menu->category) === 'non-coffee' && strtolower($temp->name) !== 'ice')
                            @continue
                        @endif
                        <option value="{{ $temp->id }}">
                            {{ ucfirst($temp->name) }}
                            @if($temp->price > 0)
                                (+Rp {{ number_format($temp->price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @if(strtolower($menu->category) === 'non-coffee')
                    <input type="hidden" id="temperature_hidden" name="temperature"
                           value="{{ $temperatures->firstWhere('name', 'Ice')->id ?? '' }}">
                @endif
            </div>

            {{-- Size --}}
            <div class="mb-3">
                <label for="size" class="form-label">Size</label>
                <select id="size" name="size" class="form-select">
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">
                            {{ ucfirst($size->name) }}
                            @if($size->price > 0)
                                (+Rp {{ number_format($size->extra_price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sugar --}}
            <div class="mb-3">
                <label for="sugar_level" class="form-label">Sugar Level</label>
                <select id="sugar_level" name="sugar_level" class="form-select">
                    @foreach($sugars as $sugar)
                        <option value="{{ $sugar->id }}">
                            {{ ucfirst($sugar->name) }}
                            @if($sugar->price > 0)
                                (+Rp {{ number_format($sugar->price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantity --}}
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control">
            </div>

            {{-- Harga total --}}
            <p class="fw-bold">Harga: Rp <span id="price">{{ number_format($menu->base_price, 0, ',', '.') }}</span></p>

            <button type="submit" class="btn btn-dark w-100">Tambah ke Keranjang</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('menu.index') }}" class="text-decoration-none">&larr; Kembali ke daftar menu</a>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const basePrice = parseFloat("{{ $menu->base_price }}") || 0;
    const temperatures = @json($temperatures);
    const sizes = @json($sizes);
    const category = "{{ strtolower($menu->category) }}";

    const tempSelect = document.getElementById('temperature');
    const sizeSelect = document.getElementById('size');
    const quantityInput = document.getElementById('quantity');
    const priceSpan = document.getElementById('price');
    const priceInput = document.getElementById('priceInput');

    function safeNumber(value) {
        const num = parseFloat(value);
        return isNaN(num) ? 0 : num;
    }

    function updatePrice() {
        const tempId = tempSelect?.value;
        const sizeId = sizeSelect?.value;
        const qty = parseInt(quantityInput.value) || 1;

        let tempPrice = 0;
        let sizePrice = 0;

        const selectedTemp = temperatures.find(t => t.id == tempId);
        const selectedSize = sizes.find(s => s.id == sizeId);

        // --- Logika kategori ---
        if (category === 'non-coffee') {
            // Non-coffee cuma ICE, harga ICE = base (tidak nambah)
            tempPrice = 0;
        } else if (category === 'coffee') {
            // Coffee: hot = base (0), ice = tambah harga kalau ada
            if (selectedTemp && selectedTemp.name.toLowerCase() === 'ice') {
                tempPrice = safeNumber(selectedTemp.extra_price);
                console.log('tempPrice',tempPrice)
            } else {
                tempPrice = 0; // hot
            }
        } else {
            // fallback category lain
            tempPrice = safeNumber(selectedTemp?.price);
        }

        sizePrice = safeNumber(selectedSize?.extra_price);
        // console.log('sizeprice',sizePrice)

        const total = (basePrice + tempPrice + sizePrice) * qty;

        priceSpan.textContent = total.toLocaleString('id-ID');
        priceInput.value = total;
    }

    tempSelect?.addEventListener('change', updatePrice);
    sizeSelect.addEventListener('change', updatePrice);
    quantityInput.addEventListener('input', updatePrice);

    updatePrice(); // kalkulasi awal
});
</script>
@endpush
@endsection
