@extends('layouts.portal')
@section('title', 'Slip Gaji')
@php $navActive = 'gaji'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>{{ __("Slip Gaji") }}</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    <form method="GET" action="{{ route('portal.gaji') }}" class="mb-3">
        <label class="form-label small mb-1">{{ __("Periode") }}</label>
        <select name="periode" class="form-select" onchange="this.form.submit()">
            @foreach ($periodes as $p)
                <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>
                    {{ \App\Support\PanduanGajiCalculator::labelPeriode($p) }}
                </option>
            @endforeach
        </select>
    </form>

    @if (!$rekap)
        <div class="alert alert-info py-2 small">
            Rekap bulanan periode ini belum diisi Admin HR. Komponen bulanan (kehadiran, on call, potongan)
            belum ikut dihitung.
        </div>
    @endif

    @include('partials.slip_gaji', ['slip' => $slip, 'karyawan' => $karyawan])
</div>
@endsection
