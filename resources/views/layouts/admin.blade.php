<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

@stack('scripts')

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

<script>
    console.log('Initializing Pusher & Echo...');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ env('PUSHER_APP_KEY') }}",
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true,
    });

    window.Echo.channel('transactions')
        .listen('TransactionPaid', (e) => {
            console.log("🎉 EVENT RECEIVED:", e);

            // Optional: tambahkan fungsi refresh table otomatis
            if (typeof refreshTransactionTable === "function") {
                refreshTransactionTable(e);
            }
        });

    window.Echo.connector.pusher.connection.bind('connected', function () {
        console.log("🔥 Connected to Pusher successfully!");
    });
</script>




</body>
</html>
