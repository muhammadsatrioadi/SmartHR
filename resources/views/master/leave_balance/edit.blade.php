@extends('layouts.main')

@section('title', 'Edit Saldo Cuti - ' . $karyawan->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">Edit Saldo Cuti Pegawai</h4>
                    <p class="mb-0 text-muted">
                        <strong>{{ $karyawan->name }}</strong> &mdash; {{ $karyawan->nik }} &mdash; Tahun {{ $tahun }}
                    </p>
                </div>
                <a href="{{ route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('leaveBalance.update', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Cuti</th>
                                    <th style="width: 140px;">Kuota (hari)</th>
                                    <th style="width: 140px;">Terpakai (hari)</th>
                                    <th style="width: 120px;">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($balances as $index => $row)
                                    <tr>
                                        <td>
                                            <strong>{{ $row->leaveType->nama }}</strong>
                                            <input type="hidden" name="balances[{{ $index }}][id]" value="{{ $row->id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="1" min="1"
                                                name="balances[{{ $index }}][kuota]"
                                                class="form-control balance-kuota"
                                                data-index="{{ $index }}"
                                                data-terpakai="{{ (int) $row->terpakai }}"
                                                value="{{ old('balances.'.$index.'.kuota', (int) $row->kuota) }}"
                                                required>
                                        </td>
                                        <td>
                                            <span class="form-control-plaintext">{{ number_format($row->terpakai, 0) }} hari</span>
                                            <small class="text-muted d-block">otomatis</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $row->sisa > 0 ? 'success' : 'danger' }} balance-sisa" data-index="{{ $index }}">
                                                {{ number_format($row->sisa, 0) }} hari
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Belum ada saldo cuti. Tambahkan dulu dari halaman detail pegawai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($balances->isNotEmpty())
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Semua Perubahan
                            </button>
                            <a href="{{ route('leaveBalance.show', ['karyawan' => $karyawan->id, 'tahun' => $tahun]) }}" class="btn btn-secondary">Batal</a>
                        </div>
                    @endif
                </form>

                <div class="alert alert-info mt-4 mb-0">
                    <strong>Petunjuk:</strong> Ubah <strong>kuota</strong> sesuai kebutuhan. Jumlah <strong>terpakai</strong> otomatis dari cuti yang disetujui dan sisa dihitung otomatis (<code>Kuota − Terpakai</code>). Kuota tidak boleh 0 dan tidak boleh lebih kecil dari terpakai.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateSisa(index) {
    const input = document.querySelector('.balance-kuota[data-index="' + index + '"]');
    const kuota = parseInt(input.value) || 0;
    const terpakai = parseInt(input.dataset.terpakai) || 0;
    const sisa = Math.max(kuota - terpakai, 0);
    const badge = document.querySelector('.balance-sisa[data-index="' + index + '"]');
    badge.textContent = sisa + ' hari';
    badge.className = 'badge balance-sisa ' + (sisa > 0 ? 'badge-success' : 'badge-danger');
    badge.dataset.index = index;
}

document.querySelectorAll('.balance-kuota').forEach(function (el) {
    el.addEventListener('input', function () {
        updateSisa(this.dataset.index);
    });
});
</script>
@endsection
