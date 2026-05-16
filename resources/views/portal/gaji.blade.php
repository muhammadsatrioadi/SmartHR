@extends('layouts.portal')
@section('title', 'Gaji')
@php $navActive = 'gaji'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Slip Gaji</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @forelse($items as $gaji)
        <div class="portal-saldo-card mb-2">
            <strong>{{ $gaji->created_at?->format('M Y') }}</strong>
            <br><small>Total: Rp {{ number_format($gaji->total ?? 0, 0, ',', '.') }}</small>
        </div>
    @empty
        <p class="text-muted text-center">Data gaji belum tersedia.</p>
    @endforelse
    <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
