@extends('layouts.main')

@section('title', 'Shift Kerja')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Setup Shift Kerja</h6>
                <a class="btn btn-primary" href="{{ route('workShift.create') }}">Tambah</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Group Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Toleransi Telat (menit)</th>
                            <th>Aktif</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->shiftGroup?->nama ?? '-' }}</td>
                                <td>{{ $row->jam_masuk }}</td>
                                <td>{{ $row->jam_pulang }}</td>
                                <td>{{ $row->toleransi_telat_menit }}</td>
                                <td>{{ $row->is_aktif ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('workShift.edit', $row->id) }}">Edit</a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('workShift.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus shift ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data.</td>
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

