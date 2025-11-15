@extends('layouts.admin')


@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<h2>{{ isset($menu) ? 'Edit Menu' : 'Tambah Menu' }}</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ isset($menu) ? route('menus.update', $menu->id) : route('menus.store') }}" method="POST">
    @csrf
    @if(isset($menu)) @method('PUT') @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nama Menu</label>
        <input type="text" class="form-control" name="name" value="{{ $menu->name ?? old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" class="form-control" name="price" value="{{ $menu->price ?? old('price') }}" required>
    </div>

    <button type="submit" class="btn btn-success">{{ isset($menu) ? 'Update' : 'Simpan' }}</button>
    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
