@extends('layouts.portal')

@section('title', 'Presensi')

@php $navActive = 'presensi'; @endphp

@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Presensi</strong>
</div>

<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @unless($consentsOk)
        <div class="alert alert-warning small">
            Setujui <a href="{{ route('portal.consent') }}">Surat Perjanjian</a> dan Task List sebelum absen.
        </div>
    @endunless

    <div class="portal-card-dark">
        <div class="small opacity-75">{{ strtoupper($greeting ?? 'SELAMAT DATANG') }} — {{ strtoupper($karyawan->name) }}</div>
        <div id="live-clock" class="portal-time-big mt-2">00:00:00</div>
        <div class="small opacity-75">{{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</div>

        <div class="portal-shift-box">
            <strong>SHIFT HARI INI</strong><br>
            @if($shift)
                {{ strtoupper($shift->nama) }} ({{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }})
            @else
                Belum dijadwalkan
            @endif
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="lokasi_dinas" name="lokasi_dinas">
            <label class="form-check-label small" for="lokasi_dinas">Lokasi Tugas / Dinas Luar</label>
        </div>
        <input type="text" id="catatan" class="form-control form-control-sm mb-2" placeholder="Catatan lain (opsional)">

        @if($nextType)
            <button type="button" id="btn-checkin" class="portal-btn-checkin" data-tipe="{{ $nextType }}" @disabled(!$consentsOk)>
                <i class="fas fa-fingerprint"></i>
                {{ $nextType === 'masuk' ? 'CHECK IN' : 'CHECK OUT' }}
            </button>
        @else
            <button type="button" class="portal-btn-checkin" disabled>
                <i class="fas fa-check"></i> Absensi hari ini selesai
            </button>
        @endif
    </div>

    <div class="portal-verify-card">
        <h6 class="mb-3"><i class="fas fa-shield-alt text-primary"></i> Verifikasi</h6>
        <div class="portal-verify-item ok" id="gps-status">
            <i class="fas fa-map-marker-alt"></i>
            <span>Menunggu GPS...</span>
        </div>
        <div class="portal-verify-item ok" id="device-verify-status">
            <i class="fas fa-check-circle"></i>
            <span>Perangkat terverifikasi</span>
        </div>
        <div class="portal-verify-item ok" id="device-status">
            <i class="fas fa-mobile-alt"></i>
            <span>Memuat info perangkat...</span>
        </div>
        @if($location)
            <div class="portal-verify-item small text-muted mt-2">
                Titik absensi: {{ $location->nama }} (radius {{ $location->radius_meter }}m)
            </div>
            @php
                $locations = $karyawan->attendance_location_id 
                    ? collect([$location]) 
                    : \App\Models\AttendanceLocation::where('is_aktif', true)->get();
                
                $locationData = $locations->map(function($loc) {
                    return [
                        'lat' => (float) $loc->latitude,
                        'lng' => (float) $loc->longitude,
                        'radius' => (int) $loc->radius_meter,
                        'nama' => $loc->nama
                    ];
                });
            @endphp
            <script>
                window.ATTENDANCE_LOCATIONS = @json($locationData);
            </script>
        @endif
    </div>
</div>
@endsection
