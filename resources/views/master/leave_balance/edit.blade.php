@extends('layouts.main')

@section('title', 'Edit Saldo Cuti Pegawai')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Edit Saldo Cuti</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Pegawai:</strong> {{ $item->karyawan->name }} ({{ $item->karyawan->nik }})<br>
                    <strong>Jenis Cuti:</strong> {{ $item->leaveType->nama }}<br>
                    <strong>Tahun:</strong> {{ $item->tahun }}
                </div>

                <form action="{{ route('leaveBalance.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Kuota Hari</label>
                        <input type="number" step="0.5" name="kuota" class="form-control" value="{{ old('kuota', $item->kuota) }}" required>
                        <small class="form-text text-muted">Jumlah total jatah cuti yang diberikan.</small>
                    </div>

                    <div class="form-group">
                        <label>Terpakai</label>
                        <input type="number" step="0.5" name="terpakai" class="form-control" value="{{ old('terpakai', $item->terpakai) }}" required>
                        <small class="form-text text-muted">Jumlah hari yang sudah diambil (disetujui).</small>
                    </div>

                    <div class="form-group">
                        <label>Sisa (Otomatis)</label>
                        <input type="text" class="form-control" value="{{ number_format($item->sisa, 1) }}" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('leaveBalance.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Petunjuk</h4>
            </div>
            <div class="card-body">
                <p>Gunakan halaman ini untuk melakukan penyesuaian saldo cuti pegawai secara manual.</p>
                <ul>
                    <li><strong>Kuota:</strong> Merupakan jatah cuti awal. Default diambil dari pengaturan Jenis Cuti.</li>
                    <li><strong>Terpakai:</strong> Jumlah hari cuti yang sudah digunakan. Sisa saldo akan otomatis dihitung dari <code>Kuota - Terpakai</code>.</li>
                    <li>Jika Anda ingin menambah "bonus" cuti, Anda bisa menaikkan nilai Kuota untuk pegawai ini.</li>
                </ul>
                <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Perubahan manual di sini mungkin akan tertimpa jika sistem melakukan kalkulasi ulang otomatis berdasarkan riwayat pengajuan cuti yang disetujui (tergantung implementasi sistem).</p>
            </div>
        </div>
    </div>
</div>
@endsection
