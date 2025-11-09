@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Pilih Meja</h2>
    <form action="{{ route('table.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="table_number" class="form-label">Nomor Meja</label>
            <input type="text" name="table_number" id="table_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="device_token" class="form-label">Device Token</label>
            <input type="text" name="device_token" id="device_token" class="form-control" required>
        </div>
        <button class="btn btn-primary">Masuk</button>
    </form>
</div>
@endsection
