@extends('layouts.main')

@section('title', 'Riwayat Gaji Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Data Gaji Dasar Pegawai</h6>
                    <div>
                        <a class="btn btn-outline-primary" href="{{ route('payrollMonthly.index') }}">Rekap Gaji Bulanan</a>
                        <a class="btn btn-primary" href="{{ route('salaryHistory.create') }}">Tambah Data Gaji Dasar</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Pegawai</th>
                                <th>Tanggal Berlaku</th>
                                <th>Skala</th>
                                <th>Gaji Pokok</th>
                                <th>Tunj. Jabatan</th>
                                <th>Tunj. Fungsional</th>
                                <th>Tunj. Profesi</th>
                                <th>Tunj. Makan</th>
                                <th>TKP</th>
                                <th>Tunj. Pulsa</th>
                                <th>Status Keluarga</th>
                                <th>Dasar Lembur</th>
                                <th>Upah/Jam</th>
                                <th>Alasan</th>
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
                                    <td colspan="14" class="text-center">Belum ada data.</td>
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
