@extends('layouts.app')

@section('content')
<style>
    .menu-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .menu-item {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        transition: 0.2s;
        background-color: #fff;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .menu-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .menu-item h3 {
        margin: 0 0 10px;
        font-size: 18px;
        color: #3e2723;
    }

    .menu-item p {
        margin: 0;
        font-size: 14px;
        color: #555;
    }

    a.menu-link {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 1000px) {
        .menu-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .menu-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container mt-4">
    <header class="text-center mb-4">
        <h1 class="fw-bold text-dark">Kopi Kita</h1>
        <p class="text-muted">Pilih menu kopi favoritmu</p>
    </header>

    <div class="menu-container">
        @foreach($menus as $menu)
            <a href="{{ route('menu.show', $menu->id) }}" class="menu-link">
                <div class="menu-item">
                    <h3>{{ $menu->name }}</h3>
                    <p>Harga: Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
