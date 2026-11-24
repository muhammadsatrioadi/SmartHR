@extends('layouts.main')

@section('title', 'Skala Gaji Berkala')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Skala Gaji Berkala") }}</h6>
                <a class="btn btn-primary" href="{{ route('salaryStep.create') }}">{{ __("Tambah Skala") }}</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __("Kode") }}</th>
                            <th>{{ __("Deskripsi") }}</th>
                            <th>{{ __("Gaji Pokok") }}</th>
                            <th>{{ __("Tunjangan Tetap") }}</th>
                            <th>{{ __("Hari Kerja/Minggu") }}</th>
                            <th>{{ __("Dasar Lembur") }}</th>
                            <th>{{ __("Upah/Jam") }}</th>
                            <th>{{ __("Masa Kerja (th)") }}</th>
                            <th style="width: 140px;">{{ __("Aksi") }}</th>
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
                                    <a href="{{ route('salaryStep.edit', $s->id) }}" class="btn btn-warning btn-sm">{{ __("Edit") }}</a>
                                    <form action="{{ route('salaryStep.delete', $s->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm delete-confirm" type="submit" data-message="Hapus skala gaji {{ $s->kode }}?">{{ __("Hapus") }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">{{ __("Belum ada data.") }}</td>
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

