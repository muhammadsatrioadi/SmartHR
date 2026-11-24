@extends('layouts.main')

@section('title', 'Input Rekap Gaji Bulanan')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <h6 class="mb-1">{{ __("Input Rekap Gaji Bulanan") }}</h6>
            <p class="text-muted small">
                Rekap bulanan (kelompok 3) melengkapi data gaji dasar. Bila periode &amp; pegawai yang sama sudah ada,
                data akan diperbarui.
            </p>

            <form method="POST" action="{{ route('payrollMonthly.store') }}">
                @csrf
                @include('payroll_monthly._form', ['rekap' => null])

                <div class="d-flex justify-content-end">
                    <a href="{{ route('payrollMonthly.index') }}" class="btn btn-outline-secondary me-2">{{ __("Batal") }}</a>
                    <button class="btn btn-primary" type="submit">{{ __("Simpan") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
