@extends('layouts.portal')

@section('title', 'Ajukan Lembur')

@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.reimburse.index') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Pengajuan Lembur (ESS)</strong>
</div>

<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('error'))<div class="alert alert-danger small">{{ session('error') }}</div>@endif

    <form action="{{ route('portal.reimburse.overtime.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Tanggal Lembur</label>
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label small">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control form-control-sm" required>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label small">Jam Selesai (Check-out)</label>
                <input type="time" name="jam_selesai" class="form-control form-control-sm" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label small">Pilihan Pembayaran</label>
            <select name="pilihan_pembayaran" class="form-select form-select-sm" required>
                <option value="bulan_ini">Masuk gaji bulan ini</option>
                <option value="bulan_depan">Masuk gaji bulan depan</option>
            </select>
            <div class="form-text small text-info">
                <i class="fas fa-info-circle"></i> Cut-off perusahaan tanggal 15 setiap bulan.
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small">Jenis Hari</label>
            <select name="jenis_hari" class="form-select form-select-sm" required id="jenis_hari">
                <option value="hari_kerja">Hari Kerja</option>
                <option value="hari_libur">Hari Libur / Sabtu-Minggu</option>
                <option value="hari_raya">Hari Raya</option>
            </select>
        </div>

        <div class="mb-3 form-check d-none" id="div_terpendek">
            <input type="checkbox" name="is_hari_kerja_terpendek" class="form-check-input" id="is_hari_kerja_terpendek" value="1">
            <label class="form-check-label small" for="is_hari_kerja_terpendek">Lembur pada Hari Kerja Terpendek (Contoh: Jumat/Sabtu)</label>
        </div>

        <div class="mb-3">
            <label class="form-label small">Keterangan Pekerjaan</label>
            <textarea name="keterangan_pekerjaan" class="form-control form-control-sm" rows="3" placeholder="Apa yang dikerjakan selama lembur?" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label small">Bukti Tambahan (Screenshot Check-out)</label>
            <input type="file" name="bukti_screenshot" class="form-control form-control-sm" accept="image/*">
            <div class="form-text small">Unggah bukti presensi pulang atau pendukung lainnya.</div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="confirm" required>
            <label class="form-check-label small" for="confirm">Saya menyatakan pengajuan ini benar dan sesuai dengan hasil kerja saya.</label>
        </div>

        <button type="submit" class="portal-btn-checkin w-100">
            <i class="fas fa-paper-plane"></i> AJUKAN LEMBUR
        </button>
    </form>
</div>
@push('scripts')
<script>
    document.getElementById('jenis_hari').addEventListener('change', function() {
        const div = document.getElementById('div_terpendek');
        if (this.value === 'hari_kerja') {
            div.classList.add('d-none');
        } else {
            div.classList.remove('d-none');
        }
    });
</script>
@endpush
@endsection
