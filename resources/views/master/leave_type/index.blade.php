@extends('layouts.main')

@section('title', 'Jenis Cuti')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Master Jenis Cuti</h6>
                <a class="btn btn-primary" href="{{ route('leaveType.create') }}">Tambah</a>
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
                            <th>Grup</th>
                            <th>Periode</th>
                            <th>Masa Kerja Min</th>
                            <th>Max Backdate</th>
                            <th>Rekap</th>
                            <th style="width: 180px;">Aksi</th>
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
                                    <a class="btn btn-sm btn-warning" href="{{ route('leaveType.edit', $row->id) }}">Edit</a>
                                    <form class="d-inline" method="POST"
                                        action="{{ route('leaveType.delete', $row->id) }}"
                                        onsubmit="return confirm('Hapus jenis cuti ini?')">
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

