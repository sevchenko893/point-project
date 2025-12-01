<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background: #f5f5f5; }
        .sidebar { width: 240px; height: 100vh; background: #222; color: #fff; position: fixed; left: 0; top: 0; padding: 25px 20px; }
        .sidebar h4 { color: #fff; }
        .sidebar a { color: #ccc; text-decoration: none; display: block; padding: 10px 5px; border-radius: 6px; margin-bottom: 6px; }
        .sidebar a:hover { background: #444; color: #fff; }
        .content { margin-left: 260px; padding: 30px; }
        .card { border-radius: 14px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-bold">Admin Panel</h4>
    <hr>

    <a href="/admin/dashboard">📊 Dashboard</a>
    <a href="/admin/transactions">🧾 Transactions</a>
    <a href="/admin/menus">🍽 Menu</a>

    <form method="POST" action="/logout" class="mt-5">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>
</div>

<div class="content">
    @yield('content')
</div>

@stack('scripts') <!-- <-- child view bisa push ke sini -->

<!-- Jika ingin langsung di layout -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

@push('scripts')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
<script>
    console.log('[JS] Initializing Pusher/Echo...');

    Pusher.logToConsole = true;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("PUSHER_APP_KEY") }}',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
    });

    console.log('[JS] Echo setup done');

    window.Echo.channel('transactions')
        .listen('TransactionPaid', (e) => {
            console.log('[JS] TransactionPaid received', e);
            let message = `Meja ${e.table_number ?? '-'} sudah dibayar! Total: Rp ${e.total_amount}`;
            alert(message);

            const div = document.createElement('div');
            div.className = 'alert alert-success mt-2';
            div.textContent = message;
            document.querySelector('.content').prepend(div);
        });
</script>
@endpush

</body>
</html>
