@extends('layouts.main')

@section('title', 'Kelola Saldo Cuti Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Saldo Cuti Pegawai ({{ request('tahun', date('Y')) }})</h4>
                <div class="card-header-action d-flex gap-2">
                    <form action="{{ route('leaveBalance.sync') }}" method="POST" onsubmit="return confirm('Sinkronisasi akan membuat saldo cuti untuk semua karyawan di tahun terpilih. Lanjutkan?')">
                        @csrf
                        <input type="hidden" name="tahun" value="{{ request('tahun', date('Y')) }}">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-sync"></i> Sinkronisasi Saldo
                        </button>
                    </form>
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

                <div class="row mb-4">
                    <div class="col-md-8">
                        <form action="{{ route('leaveBalance.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIK..." value="{{ request('search') }}">
                            <select name="tahun" class="form-control" style="width: 120px;">
                                @for($i = date('Y') + 1; $i >= date('Y') - 2; $i--)
                                    <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>Thn {{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            <a href="{{ route('leaveBalance.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i></a>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pegawai</th>
                                <th>Jenis Cuti</th>
                                <th>Tahun</th>
                                <th>Kuota</th>
                                <th>Terpakai</th>
                                <th>Sisa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row->karyawan->name }}</strong><br>
                                        <small class="text-muted">{{ $row->karyawan->nik }}</small>
                                    </td>
                                    <td>{{ $row->leaveType->nama }}</td>
                                    <td>{{ $row->tahun }}</td>
                                    <td>{{ number_format($row->kuota, 1) }}</td>
                                    <td>{{ number_format($row->terpakai, 1) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $row->sisa > 0 ? 'success' : 'danger' }}">
                                            {{ number_format($row->sisa, 1) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('leaveBalance.edit', $row->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak ditemukan. Silakan lakukan sinkronisasi saldo jika data kosong.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
