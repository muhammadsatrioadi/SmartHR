@extends('layouts.main')

@section('title', 'Jenis Cuti')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Master Jenis Cuti") }}</h6>
                <a class="btn btn-primary" href="{{ route('leaveType.create') }}">{{ __("Tambah") }}</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ __("Kode") }}</th>
                            <th>{{ __("Nama") }}</th>
                            <th>{{ __("Grup") }}</th>
                            <th>{{ __("Periode") }}</th>
                            <th>{{ __("Masa Kerja Min") }}</th>
                            <th>{{ __("Max Backdate") }}</th>
                            <th>{{ __("Rekap") }}</th>
                            <th style="width: 180px;">{{ __("Aksi") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $row->kode }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->grup }}</td>
                                <td>
                                    @if ($row->pakai_periode)
                                        {{ $row->satuan_periode }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($row->min_masa_kerja)
                                        {{ $row->min_masa_kerja }} {{ $row->satuan_masa_kerja }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($row->max_backdate)
                                        {{ $row->max_backdate }} hari
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $row->rekap ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('leaveType.edit', $row->id) }}">{{ __("Edit") }}</a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('leaveType.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus jenis cuti ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">{{ __("Hapus") }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __("Belum ada data.") }}</td>
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

