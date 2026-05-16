<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')

@section('navKaryawan')
    active
@endsection

@section('content')

    <div class="section-header d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Master Pegawai</h1>
        <div class="section-header-button">
            <a href="{{ route('karyawans.create') }}" class="btn btn-primary btn-sm">Tambah Karyawan</a>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="card mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Telephone</th>
                                <th scope="col">Status Pegawai</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Departemen</th>
                                <th scope="col">Unit</th>
                                <th scope="col">KTP</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Total Kontak</th>
                                <th scope="col" class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawans as $karyawan)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $karyawan->nik }}</td>
                                    <td>{{ $karyawan->name }}</td>
                                    <td class="text-truncate" style="max-width: 220px;">{{ $karyawan->email }}</td>
                                    <td>{{ $karyawan->jenis_kelamin }}</td>
                                    <td class="text-nowrap">{{ $karyawan->telephone }}</td>
                                    <td>{{ $karyawan->status }}</td>
                                    <td>{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $karyawan->department->nama ?? '-' }}</td>
                                    <td>{{ $karyawan->workUnit->nama ?? '-' }}</td>
                                    <td class="text-nowrap">{{ $karyawan->ktp }}</td>
                                    <td class="text-nowrap">{{ $karyawan->NPWP }}</td>
                                    <td>{{ $karyawan->total_kontak }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('karyawans.edit', ['id' => $karyawan->id]) }}"
                                                class="btn btn-warning" title="Edit Data"><i class="fas fa-edit"></i></a>
                                            
                                            <form action="{{ route('karyawans.resetDevice', $karyawan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info" title="Reset Login HP" 
                                                    onclick="return confirm('Reset login perangkat untuk {{ $karyawan->name }}? Karyawan harus login ulang di HP baru.')">
                                                    <i class="fas fa-mobile-alt"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('karyawans.delete', ['id' => $karyawan->id]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
