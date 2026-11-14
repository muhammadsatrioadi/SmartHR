@extends('layouts.main')

@section('title', 'Status Karyawan')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Kelola Status Karyawan</h6>
                <a class="btn btn-primary" href="{{ route('employeeStatus.create') }}">Buat Status</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Status</th>
                            <th>Payroll</th>
                            <th>Aktif</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->is_payroll ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $row->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('employeeStatus.edit', $row->id) }}">Edit</a>
                                    <form class="d-inline" method="POST" action="{{ route('employeeStatus.delete', $row->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger delete-confirm" type="submit" data-message="Hapus status {{ $row->nama }}?">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data.</td>
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
