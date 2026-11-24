@extends('layouts.main')

@section('title', 'Ubah Rekap Gaji Bulanan')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <h6 class="mb-1">{{ __("Ubah Rekap Gaji Bulanan") }}</h6>
            <p class="text-muted small">{{ $rekap->karyawan->name ?? '-' }} &middot; {{ $rekap->labelPeriode() }}</p>

            <form method="POST" action="{{ route('payrollMonthly.update', $rekap) }}">
                @csrf
                @method('PUT')
                @include('payroll_monthly._form', ['rekap' => $rekap, 'lockIdentitas' => true])

                <div class="d-flex justify-content-end">
                    <a href="{{ route('payrollMonthly.index', ['periode' => $rekap->periode]) }}" class="btn btn-outline-secondary me-2">{{ __("Batal") }}</a>
                    <a href="{{ route('slipGaji.show', ['karyawan' => $rekap->karyawan_id, 'periode' => $rekap->periode]) }}"
                        class="btn btn-outline-primary me-2">{{ __("Lihat Slip") }}</a>
                    <button class="btn btn-primary" type="submit">{{ __("Perbarui") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
