@extends('layouts.main')

@section('title', 'Kelola Saldo Cuti Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Saldo Cuti Pegawai ({{ $tahun }})</h4>
                <div class="card-header-action d-flex gap-2">
                    <form action="{{ route('leaveBalance.sync') }}" method="POST" onsubmit="return confirm('Sinkronisasi akan membuat saldo cuti untuk semua karyawan di tahun terpilih. Lanjutkan?')">
                        @csrf
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
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
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>Thn {{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            <a href="{{ route('leaveBalance.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i></a>
                        </form>
                    </div>
                </div>

                <p class="text-muted mb-3"><i class="fas fa-info-circle"></i> Ketuk baris pegawai untuk melihat dan mengelola semua saldo cuti.</p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Pegawai</th>
                                <th>NIK</th>
                                <th>Jumlah Jenis Cuti</th>
                                <th>Ringkasan Sisa</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $pegawai)
                                @php
                                    $balances = $pegawai->leaveBalances;
                                @endphp
                                <tr class="leave-balance-row" role="button"
                                    onclick="window.location='{{ route('leaveBalance.show', ['karyawan' => $pegawai->id, 'tahun' => $tahun]) }}'">
                                    <td><strong>{{ $pegawai->name }}</strong></td>
                                    <td>{{ $pegawai->nik }}</td>
                                    <td>{{ $balances->count() }} jenis</td>
                                    <td>
                                        @foreach($balances as $bal)
                                            <span class="badge badge-{{ $bal->sisa > 0 ? 'success' : 'secondary' }} mr-1 mb-1">
                                                {{ $bal->leaveType->nama }}: {{ number_format($bal->sisa, 1) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="text-muted"><i class="fas fa-chevron-right"></i></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data tidak ditemukan. Silakan lakukan sinkronisasi saldo jika data kosong.</td>
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

<style>
.leave-balance-row {
    cursor: pointer;
}
.leave-balance-row:hover {
    background-color: rgba(0, 91, 172, 0.06) !important;
}
</style>
@endsection
