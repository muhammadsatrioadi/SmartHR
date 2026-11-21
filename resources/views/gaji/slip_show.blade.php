@extends('layouts.main')

@section('title', 'Slip Gaji')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h1 class="h5 mb-0">
                    Slip Gaji
                    @if ($rekap)
                        <span class="badge bg-{{ $rekap->status === 'final' ? 'success' : 'secondary' }} align-middle">{{ $rekap->status }}</span>
                    @endif
                </h1>
                <form class="d-flex align-items-end gap-2" method="GET"
                    action="{{ route('slipGaji.show', ['karyawan' => $karyawan->id]) }}">
                    <div>
                        <label class="form-label mb-1">Periode</label>
                        <input type="month" name="periode" value="{{ $periode }}" class="form-control">
                    </div>
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                    <a class="btn btn-outline-success"
                        href="{{ route('slipGaji.cetak', ['karyawan' => $karyawan->id, 'periode' => $periode]) }}" target="_blank">Cetak</a>
                    <a class="btn btn-outline-secondary" href="{{ route('slipGaji.index', ['periode' => $periode]) }}">Kembali</a>
                </form>
            </div>

            @if (!$rekap)
                <div class="alert alert-info py-2">
                    Rekap gaji bulanan periode ini belum diisi. Komponen bulanan (kehadiran, on call, potongan) belum ikut dihitung.
                    <a href="{{ route('payrollMonthly.create', ['karyawan_id' => $karyawan->id, 'periode' => $periode]) }}">Isi rekap bulanan</a>.
                </div>
            @endif

            @include('partials.slip_gaji', ['slip' => $slip, 'karyawan' => $karyawan])
        </div>
    </div>
</div>
@endsection
