@extends('layouts.admin')

@section('title', 'Daftar Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Menu</h2>
    <a href="{{ route('menus.create') }}" class="btn btn-primary">Tambah Menu</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered table-striped bg-white shadow-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $menu->name }}</td>
                <td>{{ $menu->category->name ?? '-' }}</td>
                <td>Rp {{ number_format($menu->base_price, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada menu.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
