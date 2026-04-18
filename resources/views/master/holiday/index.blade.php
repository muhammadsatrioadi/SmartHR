@extends('layouts.main')

@section('title', 'Hari Raya & Libur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Setup Hari Raya & Libur</h6>
                <a class="btn btn-primary" href="{{ route('holiday.create') }}">Tambah</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card bg-dark border-secondary mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('holiday.sync') }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label">Tahun Awal</label>
                            <input type="number" name="from_year" class="form-control @error('from_year') is-invalid @enderror"
                                value="{{ old('from_year', $defaultFromYear) }}" min="2026" max="2100" required>
                            @error('from_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun Akhir</label>
                            <input type="number" name="to_year" class="form-control @error('to_year') is-invalid @enderror"
                                value="{{ old('to_year', $defaultToYear) }}" min="2026" max="2100" required>
                            @error('to_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <button class="btn btn-outline-info" type="submit">Sinkronkan Hari Libur Nasional Indonesia</button>
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
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Keterangan</th>
                            <th>Tetap</th>
                            <th>Hari Raya</th>
                            <th style="width: 160px;">Aksi</th>
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
                                    <a class="btn btn-sm btn-warning" href="{{ route('holiday.edit', $row->id) }}">Edit</a>
                                    <form class="d-inline" method="POST" action="{{ route('holiday.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus hari libur ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data.</td>
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

