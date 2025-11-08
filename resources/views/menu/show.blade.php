@extends('layouts.app')

@section('content')

<!-- {{$menu}} -->
{{$temperatures}}
<div class="container mt-4">
    <div class="menu-detail p-4 rounded shadow-sm bg-white mx-auto" style="max-width:500px;">
        <h2 class="fw-bold text-dark">{{ $menu->name }}</h2>
        <p class="text-muted">Deskripsi: {{ $menu->description ?? '-' }}</p>

        <form id="menuForm" method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
            <input type="hidden" id="priceInput" name="price" value="{{ $menu->price }}">

            <div class="mb-3">
                <label for="temperature" class="form-label">Temperature</label>
                <select id="temperature" name="temperature" class="form-select">
                    @foreach($temperatures as $temp)
                        <option value="{{ $temp->id }}">
                            {{ ucfirst($temp->name) }} 
                            @if($temp->price > 0)
                                (+Rp {{ number_format($temp->price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="size" class="form-label">Size</label>
                <select id="size" name="size" class="form-select">
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ ucfirst($size->name) }}
                        @if($size->price > 0)
                                (+Rp {{ number_format($size->price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="sugar_level" class="form-label">Sugar Level</label>
                <!-- <select id="sugar_level" name="sugar_level" class="form-select">
                    <option value="normal">Normal</option>
                    <option value="less">Less</option>
                    <option value="no">No Sugar</option>
                </select> -->
                <select id="sugar_level" name="sugar_level" class="form-select">
                    @foreach($sugars as $sugar)
                        <option value="{{ $sugar->id }}">{{ ucfirst($sugar->name) }}
                        @if($sugar->price > 0)
                                (+Rp {{ number_format($sugar->price, 0, ',', '.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control">
            </div>

            <p class="fw-bold">Harga: Rp <span id="price">{{ number_format($menu->base_price,0,',','.') }}</span></p>

            <button type="submit" class="btn btn-dark w-100">Tambah ke Keranjang</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('menu.index') }}" class="text-decoration-none">&larr; Kembali ke daftar menu</a>
        </div>
    </div>
</div>

@push('styles')
<style>
.menu-detail {
    transition: transform 0.2s, box-shadow 0.2s;
}
.menu-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
const priceOptions = [
    @foreach($menu->priceOptions as $option)
        {temperature: "{{ $option->temperature }}", size: "{{ $option->size }}", price: {{ $option->price }} }@if(!$loop->last),@endif
    @endforeach
];

const temperatureSelect = document.getElementById('temperature');
const sizeSelect = document.getElementById('size');
const quantityInput = document.getElementById('quantity');
const priceSpan = document.getElementById('price');
const priceInput = document.getElementById('priceInput');

function updatePrice() {
    const temp = temperatureSelect.value;
    const size = sizeSelect.value;
    const quantity = parseInt(quantityInput.value) || 1;

    const option = priceOptions.find(o => o.temperature === temp && o.size === size);
    if(option){
        const total = option.price * quantity;
        priceSpan.textContent = total.toLocaleString('id-ID');
        priceInput.value = total;
    }
}

temperatureSelect.addEventListener('change', updatePrice);
sizeSelect.addEventListener('change', updatePrice);
quantityInput.addEventListener('input', updatePrice);
</script>
@endpush
@endsection
