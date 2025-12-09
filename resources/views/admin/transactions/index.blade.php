<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Transaksi</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">Tambah Transaksi</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white shadow-sm" id="transaction-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr id="transaction-{{ $t->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($t->total_amount,0,',','.') }}</td>
                    <td>{{ ucfirst($t->payment_method ?? '-') }}</td>
                    <td>{{ ucfirst($t->status) }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="no-transactions"><td colspan="6" class="text-center">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
<script>
console.log('Initializing Pusher & Echo...');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: true,
    disableStats: true
});

window.Echo.connector.pusher.connection.bind('connected', function () {
    console.log("🔥 Connected to Pusher successfully!");
});

// Fungsi update/insert row transaksi
function refreshTransactionTable(event) {
    let tbody = document.querySelector('#transaction-table tbody');
    let rowId = 'transaction-' + event.id;
    let existingRow = document.getElementById(rowId);

    let html = `
        <tr id="${rowId}">
            <td>New</td>
            <td>${event.user_name ? event.user_name : '-'}</td>
            <td>Rp ${Number(event.total_amount).toLocaleString('id-ID')}</td>
            <td>${event.payment_method ?? '-'}</td>
            <td>${event.status ?? '-'}</td>
             <td>
            <a href="/admin/transactions/${event.id}" class="btn btn-sm btn-info">Detail</a>
            <a href="/admin/transactions/${event.id}/edit" class="btn btn-sm btn-warning">Edit</a>
        </td>
        </tr>
    `;

    if (existingRow) {
        existingRow.outerHTML = html; // update row
    } else {
        let noRow = document.getElementById('no-transactions');
        if (noRow) noRow.remove();
        tbody.insertAdjacentHTML('afterbegin', html); // tambah di atas
    }
}

// Listen event dari Pusher
// window.Echo.channel('transactions').listen('TransactionPaid', (e) => {
//     console.log("🎉 EVENT RECEIVED:", e);
//     refreshTransactionTable(e);
// });

window.Echo.channel('transactions').listen('TransactionPaid', (e) => {
    console.log("🎉 EVENT RECEIVED:", e);

    // munculkan alert tanpa diblock browser

    if (e.status === "paid") {

        // format list menu
        let menuList = "";
    if (Array.isArray(e.menus) && e.menus.length > 0) {
        menuList = e.menus
            .map(m => `- ${m.menu_name} (Qty: ${m.qty})`)
            .join("\n");
    } else {
        menuList = "- Tidak ada menu";
    }

    setTimeout(() => {
        alert(

            "💰 Transaksi baru dibayar!\n\n" +
            "Meja Nomor: " + (e.table_number ?? '-') + "\n" +
            "Nama Pelanggan: " + (e.customer_name ?? '-') + "\n" +
            "Total: Rp " + Number(e.total_amount).toLocaleString('id-ID') + "\n\n" +
            "🍽 Menu:\n" + menuList
        );
    }, 10);
}

    refreshTransactionTable(e);
});
</script>

</body>
</html>
