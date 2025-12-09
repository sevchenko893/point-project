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

<form action="{{ route('admin.transactions.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="customer_name" class="form-label">Nama Customer</label>
        <input type="text" name="customer_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="table_number" class="form-label">Nomor Meja</label>
        <input type="text" name="table_number" class="form-control" required>
    </div>

    <h4>Menu</h4>

    <div id="menuItems">
        <div class="menu-item mb-3 border p-3 rounded">

            <div class="mb-2">
                <label>Menu</label>
                <select name="menus[0][menu_id]" class="form-select" required>
                    <option value="" disabled selected>Pilih Menu</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">
                            {{ $menu->name }} - Rp {{ number_format($menu->base_price,0,',','.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Size</label>
                <select name="menus[0][size]" class="form-select">
                    @foreach($sizes as $size)
                        <option value="{{ $size->name }}">
                            {{ $size->name }}
                            @if($size->price > 0)
                                (+Rp {{ number_format($size->price,0,',','.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Temperature / Ice Level</label>
                <select name="menus[0][temperature]" class="form-select">
                    @foreach($temperatures as $temp)
                        <option value="{{ $temp->name }}">
                            {{ $temp->name }}
                            @if($temp->price > 0)
                                (+Rp {{ number_format($temp->price,0,',','.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Sugar Level</label>
                <select name="menus[0][sugar_level]" class="form-select">
                    @foreach($sugars as $sugar)
                        <option value="{{ $sugar->name }}">
                            {{ $sugar->name }}
                            @if($sugar->price > 0)
                                (+Rp {{ number_format($sugar->price,0,',','.') }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Quantity</label>
                <input type="number" name="menus[0][quantity]" class="form-control" value="1" min="1">
            </div>

            <button type="button" class="btn btn-danger btn-sm removeMenuItem">Hapus Menu</button>
        </div>
    </div>

    <button type="button" class="btn btn-primary " id="addMenuItem">Tambah Menu Lain</button>

    <button type="submit" class="btn btn-success">Simpan Transaksi</button>
</form>

@push('scripts')
<script>
let menuIndex = 1;

document.getElementById('addMenuItem').addEventListener('click', () => {
    let container = document.getElementById('menuItems');
    let clone = container.children[0].cloneNode(true);

    clone.querySelectorAll('select, input').forEach(el => {
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        else el.value = el.name.includes('quantity') ? 1 : '';
        el.name = el.name.replace(/\[0\]/, `[${menuIndex}]`);
    });

    container.appendChild(clone);
    menuIndex++;

    clone.querySelector('.removeMenuItem').addEventListener('click', () => clone.remove());
});

document.querySelectorAll('.removeMenuItem').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.menu-item').remove());
});
</script>
@endpush

@endsection
