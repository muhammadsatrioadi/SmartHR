@extends('layouts.main')

@section('title', 'Group Shift')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Setup Group Shift") }}</h6>
                <a class="btn btn-primary" href="{{ route('shiftGroup.create') }}">{{ __("Tambah") }}</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __("Kode") }}</th>
                            <th>{{ __("Nama") }}</th>
                            <th>{{ __("Tipe Absen") }}</th>
                            <th>{{ __("Istirahat (menit)") }}</th>
                            <th style="width: 160px;">{{ __("Aksi") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->tipe_absen }}</td>
                                <td>{{ $row->istirahat_menit }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('shiftGroup.edit', $row->id) }}">{{ __("Edit") }}</a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('shiftGroup.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus group shift ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">{{ __("Hapus") }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __("Belum ada data.") }}</td>
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

