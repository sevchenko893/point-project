<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .menu-detail {
        border: 1px solid #000;
        padding: 20px;
        width: 400px;
        margin: 20px auto;
    }

    .menu-detail h2 {
        margin: 0 0 10px 0;
    }

    .menu-detail p {
        margin: 5px 0;
    }

    .back-link {
        margin-top: 10px;
        display: inline-block;
    }

    .form-group {
        margin: 10px 0;
    }

    label {
        display: block;
        margin-bottom: 3px;
    }

    select {
        width: 100%;
        padding: 5px;
    }

    .price {
        font-weight: bold;
        margin-top: 10px;
    }

    button {
        margin-top: 10px;
        padding: 10px;
        width: 100%;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="menu-detail">
        <h2>{{ $menu->name }}</h2>
        <p>Deskripsi: {{ $menu->description ?? '-' }}</p>

        <form id="menuForm" method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
            <input type="hidden" id="priceInput" name="price" value="{{ $menu->price }}">

            <div class="form-group">
                <label for="temperature">Temperature</label>
                <select id="temperature" name="temperature">
                    @foreach($menu->priceOptions->pluck('temperature')->unique() as $temp)
                    <option value="{{ $temp }}">{{ ucfirst($temp) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="size">Size</label>
                <select id="size" name="size">
                    @foreach($menu->priceOptions->pluck('size')->unique() as $size)
                    <option value="{{ $size }}">{{ ucfirst($size) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="sugar_level">Sugar Level</label>
                <select id="sugar_level" name="sugar_level">
                    <option value="normal">Normal</option>
                    <option value="less">Less</option>
                    <option value="no">No Sugar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" style="width:100%;padding:5px;">
            </div>

            <p class="price">Harga: Rp <span id="price">{{ number_format($menu->price,0,',','.') }}</span></p>

            <button type="submit">Tambah ke Keranjang</button>
        </form>

        <div class="back-link">
            <a href="{{ route('menu.index') }}">Kembali ke daftar menu</a>
        </div>
    </div>

    <script>
    const priceOptions = [
        @foreach($menu->priceOptions as $option) {
            temperature: "{{ $option->temperature }}",
            size: "{{ $option->size }}",
            price: {
                {
                    $option->price
                }
            }
        }
        @if(!$loop->last), @endif
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
        if (option) {
            const total = option.price * quantity;
            priceSpan.textContent = total.toLocaleString('id-ID');
            priceInput.value = total;
        }
    }

    temperatureSelect.addEventListener('change', updatePrice);
    sizeSelect.addEventListener('change', updatePrice);
    quantityInput.addEventListener('input', updatePrice);
    </script>


</body>

</html>