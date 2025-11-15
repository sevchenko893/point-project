<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Gagal</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        h1 { color: red; }
        p { font-size: 18px; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <h1>❌ Pembayaran Gagal</h1>
    <p>Transaksi ID: {{ $id }}</p>
    <p>Silakan coba lagi atau hubungi kasir.</p>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>
</html>
