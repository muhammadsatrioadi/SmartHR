@extends('layouts.main')

@section('title', 'Pangkat')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Kelola Pangkat</h6>
                <a class="btn btn-primary" href="{{ route('pangkat.create') }}">Buat Pangkat</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Aktif</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('pangkat.edit', $row->id) }}">Edit</a>
                                    <form class="d-inline" method="POST" action="{{ route('pangkat.delete', $row->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger delete-confirm" type="submit" data-message="Hapus pangkat {{ $row->nama }}?">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data.</td>
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
