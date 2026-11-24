@extends('layouts.main')

@section('title', __('Saldo Cuti Pegawai') . ' - ' . $karyawan->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">{{ __("Saldo Cuti Pegawai") }}</h4>
                    <p class="mb-0 text-muted">
                        <strong>{{ $karyawan->name }}</strong> &mdash; {{ $karyawan->nik }} &mdash; Tahun {{ $tahun }}
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('leaveBalance.edit', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __("Edit Saldo") }}
                    </a>
                    <a href="{{ route('leaveBalance.create', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __("Tambah Saldo") }}
                    </a>
                    <a href="{{ route('leaveBalance.index', ['tahun' => $tahun, 'search' => request('search')]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __("Kembali") }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __("Jenis Cuti") }}</th>
                                <th>{{ __("Kuota") }}</th>
                                <th>{{ __("Terpakai") }}</th>
                                <th>{{ __("Sisa") }}</th>
                                <th style="width: 80px;">{{ __("Aksi") }}</th>
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
                                        {{ __("Belum ada saldo cuti. Klik") }} <strong>{{ __("Tambah Saldo") }}</strong> {{ __("atau lakukan sinkronisasi dari halaman utama.") }}
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
