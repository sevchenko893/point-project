<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Kopi Kita') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Custom CSS (opsional, jika mau tambah style global) --}}
    <style>
        body {
            background-color: #fafafa;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: #3e2723 !important;
        }

        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }

        .nav-link {
            color: #ddd !important;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        /* Main Container */
        main {
            min-height: 80vh;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        /* Footer */
        footer {
            background-color: #3e2723;
            color: #fff;
        }

        footer a {
            color: #ffcc80;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                ☕ Kopi Kita
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="{{ route('menu.index') }}" class="nav-link">Menu</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('cart.index', [
                            'table_number' => request('table_number'),
                            'device_token' => request('device_token')
                        ]) }}" class="btn btn-outline-light">
                            <i class="fa fa-shopping-cart me-1"></i> Cart
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="py-3 text-center mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} <strong>Kopi Kita</strong>. Semua hak dilindungi.</small>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
