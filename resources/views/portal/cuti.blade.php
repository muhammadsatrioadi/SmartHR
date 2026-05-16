@extends('layouts.portal')
@section('title', 'Cuti')
@php $navActive = 'home'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Cuti Saya</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('success'))<div class="alert alert-success small">{{ session('success') }}</div>@endif
    <p class="portal-section-title">SALDO CUTI</p>
    <div class="portal-saldo-grid mb-3">
        @foreach($saldoCuti as $saldo)
            <div class="portal-saldo-card">
                <div class="type">{{ $saldo->leaveType->nama ?? '-' }}</div>
                <div class="balance">{{ number_format($saldo->sisa, 1) }} / {{ number_format($saldo->kuota, 0) }} hari</div>
            </div>
        @endforeach
    </div>
    <a href="{{ route('portal.cuti.ajukan') }}" class="btn btn-primary btn-sm w-100 mb-3">Ajukan Cuti</a>
    @forelse($items as $cuti)
        <div class="portal-saldo-card mb-2">
            <strong>{{ $cuti->leaveType->nama ?? $cuti->jenis_cuti }}</strong>
            <span class="badge bg-secondary float-end">{{ $cuti->status }}</span>
            <br><small>{{ $cuti->tanggal_mulai?->format('d/m/Y') }} - {{ $cuti->tanggal_berakhir?->format('d/m/Y') }} ({{ $cuti->hak_diambil }} hari)</small>
        </div>
    @empty
        <p class="text-muted text-center">Belum ada pengajuan cuti.</p>
    @endforelse
    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
