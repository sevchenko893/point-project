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

    {{-- NAMA MENU --}}
    <div class="mb-3">
        <label for="name" class="form-label">Nama Menu</label>
        <input type="text" class="form-control" name="name"
               value="{{ old('name', $menu->name ?? '') }}" required>
    </div>

    {{-- CATEGORY --}}
    <div class="mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select name="category" class="form-control">
            <option value="" disabled {{ !isset($menu) ? 'selected' : '' }}>Pilih Kategori</option>
            <option value="coffee" {{ old('category', $menu->category ?? '') == 'coffee' ? 'selected' : '' }}>
                Coffee
            </option>
            <option value="non-coffee" {{ old('category', $menu->category ?? '') == 'non-coffee' ? 'selected' : '' }}>
                Non Coffee
            </option>
        </select>
    </div>

    {{-- BASE PRICE --}}
    <div class="mb-3">
        <label for="base_price" class="form-label">Harga Dasar (Base Price)</label>
        <input type="number" step="0.01" class="form-control"
               name="base_price"
               value="{{ old('base_price', $menu->base_price ?? '') }}" required>
    </div>

    {{-- DESCRIPTION --}}
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3">{{ old('description', $menu->description ?? '') }}</textarea>
    </div>

    {{-- STATUS --}}
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="available" {{ old('status', $menu->status ?? '') == 'available' ? 'selected' : '' }}>
                Tersedia
            </option>
            <option value="unavailable" {{ old('status', $menu->status ?? '') == 'unavailable' ? 'selected' : '' }}>
                Tidak Tersedia
            </option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">{{ isset($menu) ? 'Update' : 'Simpan' }}</button>
    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
</form>
@endsection
