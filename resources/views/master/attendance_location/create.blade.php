@extends('layouts.main')
@section('title', 'Tambah Titik Lokasi')

@section('links')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 400px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4 text-white">
        <h6 class="mb-4 text-white">Tambah Titik Lokasi Absensi</h6>
        <form method="POST" action="{{ route('attendanceLocation.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label text-white">Nama Lokasi</label>
                <input type="text" name="nama" class="form-control bg-dark text-white" value="{{ old('nama') }}" required placeholder="Contoh: Kantor Pusat">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Pilih Lokasi di Peta (Klik atau Geser Marker)</label>
                <div id="map" class="mb-3"></div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude" class="form-control bg-dark text-white" value="{{ old('latitude', -7.815265) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude" class="form-control bg-dark text-white" value="{{ old('longitude', 110.328759) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Radius (meter)</label>
                    <input type="number" name="radius_meter" id="radius_meter" class="form-control bg-dark text-white" value="{{ old('radius_meter', 50) }}" min="1" max="1000" required>
                    <small class="text-white-50">Radius lingkaran di peta (meter)</small>
                </div>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="is_aktif" value="1" class="form-check-input" id="is_aktif" checked>
                <label class="form-check-label text-white" for="is_aktif">Aktif</label>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('attendanceLocation.index') }}" class="btn btn-outline-light">Batal</a>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var defaultLat = {{ old('latitude', -7.815265) }};
        var defaultLng = {{ old('longitude', 110.328759) }};
        var defaultRadius = {{ old('radius_meter', 50) }};

        var map = L.map('map').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        var circle = L.circle([defaultLat, defaultLng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: defaultRadius
        }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);
        }

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            updateInputs(position.lat, position.lng);
        });

        map.on('click', function(e) {
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('latitude').addEventListener('input', function() {
            var lat = parseFloat(this.value);
            var lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });

        document.getElementById('longitude').addEventListener('input', function() {
            var lat = parseFloat(document.getElementById('latitude').value);
            var lng = parseFloat(this.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        });

        document.getElementById('radius_meter').addEventListener('input', function() {
            var radius = parseFloat(this.value);
            if (!isNaN(radius)) {
                circle.setRadius(radius);
            }
        });
    });
</script>
@endsection



