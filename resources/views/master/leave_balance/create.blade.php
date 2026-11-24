@extends('layouts.main')

@section('title', 'Tambah Saldo Cuti')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Saldo Cuti</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Pegawai:</strong> {{ $karyawan->name }} ({{ $karyawan->nik }})<br>
                    <strong>Tahun:</strong> {{ $tahun }}
                </div>

                <form action="{{ route('leaveBalance.store', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Jenis Cuti</label>
                        <select name="leave_type_id" class="form-control @error('leave_type_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis Cuti --</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" data-kuota="{{ $type->kuota_hari }}"
                                    {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->nama }} (default {{ number_format($type->kuota_hari, 1) }} hari)
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kuota Hari</label>
                        <input type="number" step="1" min="1" name="kuota" id="kuota" class="form-control @error('kuota') is-invalid @enderror"
                            value="{{ old('kuota') }}" required>
                        <small class="text-muted">Wajib diisi, minimal 1 hari (tanpa desimal).</small>
                        @error('kuota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Jumlah <strong>terpakai</strong> dihitung otomatis dari cuti yang sudah disetujui, sehingga tidak perlu diisi manual.
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="leave_type_id"]').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const kuota = opt.dataset.kuota;
    if (kuota) {
        document.getElementById('kuota').value = kuota;
    }
});
</script>
@endsection
