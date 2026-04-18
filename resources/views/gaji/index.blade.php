@extends('layouts.main')

@section('title', 'Daftar Gaji')

@section('navGaji', 'active')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Daftar Gaji</h1>
        {{-- Uncomment to add a button for creating new salary records --}}
        {{-- <a class="btn btn-primary" href="{{ route('gaji.create') }}">Tambah Gaji</a> --}}
    </div>
    <div class="card bg-secondary rounded mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Karyawan</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan Tetap</th>
                            <th>Tunjangan Tidak Tetap</th>
                            <th>Lembur Bulan Ini</th>
                            <th>Bruto</th>
                            <th>Pot. internal</th>
                            <th title="Estimasi dari config/payroll.php — sesuaikan tarif & plafon">Perkiraan iuran BPJS</th>
                            <th title="Mode di .env PAYROLL_PPH21_MODE (none | flat_percent)">PPh 21 (perkiraan)</th>
                            <th title="Bruto − potongan internal − perkiraan BPJS & PPh">THP (perkiraan)</th>
                            <th>Total gaji (internal)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gajis as $gaji)
                        <tr>
                            <td>{{ $gaji->id }}</td>
                            <td>{{ $gaji->karyawan->name }}</td>
                            <td>{{ number_format($gaji->gaji_pokok_aktif, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->tunjangan_tetap_aktif, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->tunjangan_lain_aktif, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->lembur_bulan_ini, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->bruto_bulanan, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->perkiraan_total_iuran_bpjs, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->perkiraan_pph21, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->thp_perkiraan, 0, ',', '.') }}</td>
                            <td>{{ number_format($gaji->total_gaji, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-muted small mb-0 mt-2">
                    Kolom THP memakai estimasi iuran BPJS Kesehatan &amp; Ketenagakerjaan (pekerja) dari <code>config/payroll.php</code>.
                    PPh 21 tidak dihitung otomatis kecuali Anda mengatur <code>PAYROLL_PPH21_MODE=flat_percent</code> di <code>.env</code> — untuk TER resmi silakan integrasikan ke modul pajak tersendiri.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
