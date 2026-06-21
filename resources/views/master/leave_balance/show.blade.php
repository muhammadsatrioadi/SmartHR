@extends('layouts.main')

@section('title', 'Saldo Cuti - ' . $karyawan->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">Saldo Cuti Pegawai</h4>
                    <p class="mb-0 text-muted">
                        <strong>{{ $karyawan->name }}</strong> &mdash; {{ $karyawan->nik }} &mdash; Tahun {{ $tahun }}
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('leaveBalance.edit', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Saldo
                    </a>
                    <a href="{{ route('leaveBalance.create', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Saldo
                    </a>
                    <a href="{{ route('leaveBalance.index', ['tahun' => $tahun, 'search' => request('search')]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Jenis Cuti</th>
                                <th>Kuota</th>
                                <th>Terpakai</th>
                                <th>Sisa</th>
                                <th style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $row)
                                <tr>
                                    <td><strong>{{ $row->leaveType->nama }}</strong></td>
                                    <td>{{ number_format($row->kuota, 1) }} hari</td>
                                    <td>{{ number_format($row->terpakai, 1) }} hari</td>
                                    <td>
                                        <span class="badge badge-{{ $row->sisa > 0 ? 'success' : 'danger' }}">
                                            {{ number_format($row->sisa, 1) }} hari
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('leaveBalance.destroy', $row->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus saldo cuti {{ $row->leaveType->nama }} untuk pegawai ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        Belum ada saldo cuti. Klik <strong>Tambah Saldo</strong> atau lakukan sinkronisasi dari halaman utama.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
