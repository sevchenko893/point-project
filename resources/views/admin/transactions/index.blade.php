<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f5f5; }
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #222;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 20px;
        }
        .sidebar h4 { color: #fff; }
        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            padding: 10px 5px;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .sidebar a:hover { background: #444; color: #fff; }
        .content { margin-left: 260px; padding: 30px; }
        .card { border-radius: 14px; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
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

<!-- CONTENT -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Transaksi</h2>

        <div class="d-flex gap-2">
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                Tambah Transaksi
            </a>

            <a href="{{ route('transactions.export') }}" class="btn btn-success">
                Export Transaksi
            </a>
        </div>
    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white shadow-sm align-middle"
               id="transaction-table">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width:50px;">#</th>
                    <th>Name</th>
                    <th>Table</th>
                    <th class="text-end">Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-center text-nowrap" style="width:170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr id="transaction-{{ $t->id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $t->customer_name ?? '-' }}</td>
                    <td>{{ $t->table_number ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($t->total_amount,0,',','.') }}</td>
                    <td>{{ ucfirst($t->payment_method ?? '-') }}</td>
                    <td>{{ ucfirst($t->status) }}</td>
                    <td class="text-center text-nowrap">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('transactions.show', $t->id) }}"
                               class="btn btn-sm btn-info">Detail</a>

                            <a href="{{ route('transactions.edit', $t->id) }}"
                               class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('transactions.destroy', $t->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin hapus?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="no-transactions">
                    <td colspan="7" class="text-center text-muted py-3">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PUSHER + ECHO -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

<script>
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: true,
    disableStats: true
});

/* INSERT / UPDATE ROW */
function refreshTransactionTable(e) {
    const tbody = document.querySelector('#transaction-table tbody');
    const rowId = 'transaction-' + e.id;
    const existingRow = document.getElementById(rowId);

    const html = `
        <tr id="${rowId}">
            <td class="text-center">New</td>
            <td>${e.customer_name ?? '-'}</td>
            <td>${e.table_number ?? '-'}</td>
            <td class="text-end">
                Rp ${Number(e.total_amount).toLocaleString('id-ID')}
            </td>
            <td>${e.payment_method ?? '-'}</td>
            <td>${e.status ?? '-'}</td>
            <td class="text-center text-nowrap">
                <div class="d-inline-flex gap-1">
                    <a href="/admin/transactions/${e.id}"
                       class="btn btn-sm btn-info">Detail</a>
                    <a href="/admin/transactions/${e.id}/edit"
                       class="btn btn-sm btn-warning">Edit</a>
                </div>
            </td>
        </tr>
    `;

    if (existingRow) {
        existingRow.outerHTML = html;
    } else {
        const noRow = document.getElementById('no-transactions');
        if (noRow) noRow.remove();
        tbody.insertAdjacentHTML('afterbegin', html);
    }
}

/* LISTENER */
window.Echo.channel('transactions')
    .listen('TransactionPaid', (e) => {
        console.log('🎉 EVENT RECEIVED', e);

        refreshTransactionTable(e);

        if (e.status === 'paid') {
            let menuList = Array.isArray(e.menus)
                ? e.menus.map(m => `- ${m.menu_name} (Qty: ${m.qty})`).join("\n")
                : '- Tidak ada menu';

            setTimeout(() => {
                alert(
                    "💰 Transaksi baru dibayar!\n\n" +
                    "Meja: " + (e.table_number ?? '-') + "\n" +
                    "Nama: " + (e.customer_name ?? '-') + "\n" +
                    "Total: Rp " + Number(e.total_amount).toLocaleString('id-ID') + "\n\n" +
                    "🍽 Menu:\n" + menuList
                );
            }, 50);
        }
    });
</script>

</body>
</html>
