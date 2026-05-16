@extends('layouts.portal')
@section('title', 'Absensi Bawahan')
@php $navActive = 'absensi'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Absensi Bawahan</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @forelse($items as $row)
        <div class="portal-saldo-card mb-2">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $row->karyawan->name ?? '-' }}</strong>
                    <br><small class="text-muted">{{ $row->tanggal_absensi?->format('d M Y') }} · {{ $row->time }}</small>
                </div>
                <span class="badge bg-{{ $row->status_absen === 'hadir' ? 'success' : 'secondary' }}">{{ strtoupper($row->status_absen) }}</span>
            </div>
            <div class="small mt-1 text-muted">
                {{ $row->tipe_absen ? ucfirst($row->tipe_absen) : 'Absensi' }}
                @if($row->lokasi_dinas) · <span class="text-info">Dinas Luar</span> @endif
                @if($row->latitude)
                    <br><i class="fas fa-map-marker-alt"></i> {{ number_format($row->latitude, 5) }}, {{ number_format($row->longitude, 5) }}
                    @if($row->jarak_meter) ({{ $row->jarak_meter }}m) @endif
                @endif
            </div>
            @if($row->catatan)
                <div class="mt-1 p-1 bg-light rounded small italic">"{{ $row->catatan }}"</div>
            @endif
        </div>
    @empty
        <p class="text-center text-muted">Belum ada data absensi bawahan.</p>
    @endforelse
    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
