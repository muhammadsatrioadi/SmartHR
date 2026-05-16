@extends('layouts.portal')
@section('title', 'Riwayat Absensi')
@php $navActive = 'absensi'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Riwayat Absensi</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @forelse($items as $row)
        <div class="portal-saldo-card mb-2">
            <div class="d-flex justify-content-between">
                <strong>{{ $row->tanggal_absensi?->format('d M Y') }}</strong>
                <span class="badge bg-{{ $row->status_absen === 'hadir' ? 'success' : 'secondary' }}">{{ strtoupper($row->status_absen) }}</span>
            </div>
            <small class="text-muted">
                {{ $row->tipe_absen ? ucfirst($row->tipe_absen) . ' · ' : '' }}{{ $row->time }}
                @if($row->latitude)
                    · GPS {{ number_format($row->latitude, 5) }}, {{ number_format($row->longitude, 5) }}
                @endif
            </small>
        </div>
    @empty
        <p class="text-center text-muted">Belum ada riwayat absensi.</p>
    @endforelse
    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
