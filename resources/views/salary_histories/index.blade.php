@extends('layouts.main')

@section('title', 'Riwayat Gaji Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Riwayat Gaji Berkala Pegawai</h6>
                <a class="btn btn-primary" href="{{ route('salaryHistory.create') }}">Entry Gaji Berkala</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Tanggal Berlaku</th>
                            <th>Skala</th>
                            <th>Gaji Pokok</th>
                            <th>Tunj. Tetap</th>
                            <th>Tunj. Tidak Tetap</th>
                            <th>Hari Kerja/Minggu</th>
                            <th>Dasar Lembur</th>
                            <th>Upah/Jam</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $h)
                            <tr>
                                <td>{{ $h->karyawan->name ?? '-' }}</td>
                                <td>{{ $h->tanggal_berlaku }}</td>
                                <td>{{ $h->salaryStep->kode ?? '-' }}</td>
                                <td>{{ number_format($h->gaji_pokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($h->tunjangan_tetap, 0, ',', '.') }}</td>
                                <td>{{ number_format($h->tunjangan_lain, 0, ',', '.') }}</td>
                                <td>{{ $h->hari_kerja_per_minggu }} hari</td>
                                <td>{{ number_format($h->dasar_upah_lembur, 0, ',', '.') }}</td>
                                <td>{{ number_format($h->upah_per_jam, 0, ',', '.') }}</td>
                                <td>{{ $h->alasan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada data.</td>
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
@endsection

