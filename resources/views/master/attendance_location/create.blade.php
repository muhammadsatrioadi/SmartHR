@extends('layouts.main')
@section('title', 'Tambah Titik Lokasi')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h6 class="mb-4">Tambah Titik Lokasi Absensi</h6>
        <form method="POST" action="{{ route('attendanceLocation.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" name="latitude" class="form-control" value="{{ old('latitude', -7.815265) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" name="longitude" class="form-control" value="{{ old('longitude', 110.328759) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Radius (meter)</label>
                    <input type="number" name="radius_meter" class="form-control" value="{{ old('radius_meter', 5) }}" min="1" max="100" required>
                </div>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_aktif" value="1" class="form-check-input" id="is_aktif" checked>
                <label class="form-check-label" for="is_aktif">Aktif</label>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('attendanceLocation.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection



