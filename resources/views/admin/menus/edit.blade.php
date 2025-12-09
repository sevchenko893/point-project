@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Menu: {{ $menu->name }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Nama Menu</label>
                <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
            </div>

            {{-- Category --}}
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $menu->category_id == $cat->id ? 'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Base Price --}}
            <div class="mb-3">
                <label class="form-label">Harga Dasar</label>
                <input type="number" step="0.01" name="base_price" class="form-control"
                       value="{{ $menu->base_price }}" required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control">{{ $menu->description }}</textarea>
            </div>

            {{-- Status --}}
            <div class="mb-3">

                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="available" {{ $menu->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $menu->status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            {{-- Photo --}}
            <div class="mb-3">
                <label class="form-label">Foto Menu</label>
                <input type="file" name="photo" class="form-control">

                @if($menu->photo_path)
                    <img src="{{ asset($menu->photo_path) }}" class="mt-2" width="120">
                @endif
            </div>

            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
