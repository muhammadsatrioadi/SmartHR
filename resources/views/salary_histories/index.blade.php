@extends('layouts.main')

@section('title', 'Riwayat Gaji Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ __("Data Gaji Dasar Pegawai") }}</h6>
                    <div>
                        <a class="btn btn-outline-primary" href="{{ route('payrollMonthly.index') }}">{{ __("Rekap Gaji Bulanan") }}</a>
                        <a class="btn btn-primary" href="{{ route('salaryHistory.create') }}">{{ __("Tambah Data Gaji Dasar") }}</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __("Pegawai") }}</th>
                                <th>{{ __("Tanggal Berlaku") }}</th>
                                <th>{{ __("Skala") }}</th>
                                <th>{{ __("Gaji Pokok") }}</th>
                                <th>{{ __("Tunj. Jabatan") }}</th>
                                <th>{{ __("Tunj. Fungsional") }}</th>
                                <th>{{ __("Tunj. Profesi") }}</th>
                                <th>{{ __("Tunj. Makan") }}</th>
                                <th>{{ __("TKP") }}</th>
                                <th>{{ __("Tunj. Pulsa") }}</th>
                                <th>{{ __("Status Keluarga") }}</th>
                                <th>{{ __("Dasar Lembur") }}</th>
                                <th>{{ __("Upah/Jam") }}</th>
                                <th>{{ __("Alasan") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($histories as $h)
                                <tr>
                                    <td>{{ $h->karyawan->name ?? '-' }}</td>
                                    <td>{{ optional($h->tanggal_berlaku)->format('d/m/Y') }}</td>
                                    <td>{{ $h->salaryStep->kode ?? '-' }}</td>
                                    <td>{{ number_format($h->gaji_pokok, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tunjangan_jabatan, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tunjangan_fungsional, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tunjangan_profesi, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tunjangan_makan, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tkp, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->tunjangan_pulsa, 0, ',', '.') }}</td>
                                    <td>
                                        {{ $h->pasangan_ditanggung ? 'Menikah' : 'Lajang' }}
                                        / {{ (int) $h->jumlah_anak_ditanggung }} anak
                                    </td>
                                    <td>{{ number_format($h->dasar_upah_lembur, 0, ',', '.') }}</td>
                                    <td>{{ number_format($h->upah_per_jam, 0, ',', '.') }}</td>
                                    <td>{{ $h->alasan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">{{ __("Belum ada data.") }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
