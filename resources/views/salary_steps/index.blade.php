@extends('layouts.main')

@section('title', 'Skala Gaji Berkala')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Skala Gaji Berkala</h6>
                <a class="btn btn-primary" href="{{ route('salaryStep.create') }}">Tambah Skala</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Deskripsi</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan Tetap</th>
                            <th>Hari Kerja/Minggu</th>
                            <th>Dasar Lembur</th>
                            <th>Upah/Jam</th>
                            <th>Masa Kerja (th)</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($steps as $s)
                            <tr>
                                <td>{{ $s->kode }}</td>
                                <td>{{ $s->deskripsi }}</td>
                                <td>{{ number_format($s->gaji_pokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($s->tunjangan_tetap, 0, ',', '.') }}</td>
                                <td>{{ $s->hari_kerja_per_minggu }} hari</td>
                                <td>{{ number_format($s->dasar_upah_lembur, 0, ',', '.') }}</td>
                                <td>{{ number_format($s->upah_per_jam, 0, ',', '.') }}</td>
                                <td>{{ $s->masa_kerja_min }} - {{ $s->masa_kerja_maks ?? '>' }}</td>
                                <td>
                                    <a href="{{ route('salaryStep.edit', $s->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('salaryStep.delete', $s->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus skala gaji ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $steps->links() }}
            </div>
        </div>
    </div>
@endsection

