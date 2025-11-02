<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kopi Kita</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        /* tengah halaman */
    }

    .container {
        width: 900px;
        /* lebar konten tetap */
        padding: 20px;
    }

    header {
        text-align: center;
        margin-bottom: 20px;
    }

    .menu-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* 4 menu per baris */
        gap: 15px;
    }

    .menu-item {
        border: 1px solid #000;
        padding: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .menu-item:hover {
        background-color: #f0f0f0;
    }

    .menu-item h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
    }

    .menu-item p {
        margin: 0;
        font-size: 14px;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 1000px) {
        .menu-container {
            grid-template-columns: repeat(2, 1fr);
            /* responsive 2 menu per baris */
        }
    }

    @media (max-width: 600px) {
        .menu-container {
            grid-template-columns: 1fr;
            /* mobile 1 menu per baris */
        }
    }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <h1>Kopi Kita</h1>
            <p>Pilih menu kopi favoritmu</p>
        </header>

        <div class="menu-container">
            @foreach($menus as $menu)
            <a href="{{ route('menu.show', $menu->id) }}">
                <div class="menu-item">
                    <h3>{{ $menu->name }}</h3>
                    <p>Harga mulai: Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>

</body>

</html>