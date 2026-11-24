@extends('layouts.main')

@section('title', 'Golongan')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Kelola Golongan") }}</h6>
                <a class="btn btn-primary" href="{{ route('golongan.create') }}">{{ __("Buat Golongan") }}</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __("Kode") }}</th>
                            <th>{{ __("Nama") }}</th>
                            <th>{{ __("Aktif") }}</th>
                            <th style="width: 160px;">{{ __("Aksi") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('golongan.edit', $row->id) }}">{{ __("Edit") }}</a>
                                    <form class="d-inline" method="POST" action="{{ route('golongan.delete', $row->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger delete-confirm" type="submit" data-message="Hapus golongan {{ $row->nama }}?">{{ __("Hapus") }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __("Belum ada data.") }}</td>
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

