@extends('layouts.main')
@section('title', 'Titik Lokasi Absensi')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0">Titik Lokasi Absensi (GPS)</h6>
            <a href="{{ route('attendanceLocation.create') }}" class="btn btn-primary btn-sm">Tambah Titik</a>
        </div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <table class="table table-bordered">
            <thead><tr><th>Nama</th><th>Lat</th><th>Lng</th><th>Radius</th><th>Aktif</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->longitude }}</td>
                    <td>{{ $row->radius_meter }} m</td>
                    <td>{{ $row->is_aktif ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('attendanceLocation.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('attendanceLocation.delete', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $data->links() }}
        <p class="small text-muted mt-3">Karyawan hanya bisa absen dalam radius titik (default 5 meter).</p>
    </div>
</div>
@endsection

