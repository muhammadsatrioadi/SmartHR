@extends('layouts.main')

@section('title', 'Hari Libur Nasional')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Setup Hari Libur Nasional (Red Dates)") }}</h6>
                <a class="btn btn-primary" href="{{ route('holiday.create') }}">{{ __("Tambah") }}</a>
            </div>

            <div class="card bg-dark border-secondary mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('holiday.sync') }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label">{{ __("Tahun Awal") }}</label>
                            <input type="text" class="form-control" value="{{ $defaultFromYear }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __("Tahun Akhir") }}</label>
                            <input type="text" class="form-control" value="{{ $defaultToYear }}" readonly>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <input type="hidden" name="from_year" value="{{ $defaultFromYear }}">
                            <input type="hidden" name="to_year" value="{{ $defaultToYear }}">
                            <button class="btn btn-outline-info" type="submit">{{ __("Sinkronkan Hari Libur Nasional Indonesia") }}</button>
                        </div>
                    </form>
                    <small class="text-muted">
                        Sistem akan mengambil hari libur nasional Indonesia dari sumber online dan menyimpan atau memperbarui data ke tabel `holidays`.
                    </small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __("Tanggal") }}</th>
                            <th>{{ __("Kode") }}</th>
                            <th>{{ __("Keterangan") }}</th>
                            <th>{{ __("Tetap") }}</th>
                            <th>{{ __("Hari Raya") }}</th>
                            <th style="width: 160px;">{{ __("Aksi") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $row->kode_libur }}</td>
                                <td>{{ $row->keterangan }}</td>
                                <td>{{ $row->is_tetap ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $row->is_hari_raya ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('holiday.edit', $row->id) }}">{{ __("Edit") }}</a>
                                    <form class="d-inline" method="POST" action="{{ route('holiday.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus hari libur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">{{ __("Hapus") }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __("Belum ada data.") }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection

