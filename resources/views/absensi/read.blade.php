@extends('layouts.main')

@section('title', 'Presensi')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h1 class="h4 mb-4">Daftar Presensi</h1>

            <form method="GET" action="{{ route('presensi.read') }}" class="row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label for="department_id" class="form-label">Departemen</label>
                    <select id="department_id" name="department_id" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ (string) $departmentId === (string) $department->id ? 'selected' : '' }}>
                                {{ $department->kode }} - {{ $department->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Tanggal Mulai</label>
                    <input id="date_from" type="date" name="date_from" value="{{ $dateFrom }}" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Tanggal Akhir</label>
                    <input id="date_to" type="date" name="date_to" value="{{ $dateTo }}" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status Kehadiran</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Semua (Hadir &amp; Alpha)</option>
                        <option value="hadir" {{ $status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="alpha" {{ $status === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="{{ route('presensi.read') }}" class="btn btn-outline-light">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Departemen</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Tipe Absen</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $absensi)
                            <tr>
                                <td>{{ $absensis->firstItem() + $loop->index }}</td>
                                <td>{{ optional($absensi->karyawan)->name ?? '-' }}</td>
                                <td>{{ optional(optional($absensi->karyawan)->department)->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $absensi->status_absen === 'hadir' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($absensi->status_absen) }}
                                    </span>
                                </td>
                                <td>{{ optional($absensi->tanggal_absensi)->format('d-m-Y') }}</td>
                                <td>{{ $absensi->time ? substr($absensi->time, 0, 5) : '-' }}</td>
                                <td>{{ $absensi->tipe_absen ? ucfirst($absensi->tipe_absen) : '-' }}</td>
                                <td>{{ $absensi->keterangan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data presensi untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $absensis->links() }}
            </div>
        </div>
    </div>
@endsection
