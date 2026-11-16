@php
    $v = fn ($kolom, $default = null) => old($kolom, $rekap->{$kolom} ?? $default);
    $lockIdentitas = $lockIdentitas ?? false;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Pegawai</label>
        @if ($lockIdentitas)
            <input type="text" class="form-control" value="{{ $rekap->karyawan->name ?? '-' }}" disabled>
            <input type="hidden" name="karyawan_id" value="{{ $rekap->karyawan_id }}">
        @else
            <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                <option value="">Pilih Pegawai</option>
                @foreach ($karyawans as $k)
                    <option value="{{ $k->id }}" {{ (string) $v('karyawan_id', $selected) === (string) $k->id ? 'selected' : '' }}>
                        {{ $k->nik ?? '' }} - {{ $k->name }}
                    </option>
                @endforeach
            </select>
            @error('karyawan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @endif
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Periode</label>
        @if ($lockIdentitas)
            <input type="text" class="form-control" value="{{ $rekap->labelPeriode() }}" disabled>
            <input type="hidden" name="periode" value="{{ $rekap->periode }}">
        @else
            <input type="month" name="periode" value="{{ $v('periode', $periode) }}"
                class="form-control @error('periode') is-invalid @enderror" required>
            @error('periode') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @endif
    </div>
</div>

<h6 class="mt-2">Kehadiran (tunjangan kehadiran / THD)</h6>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Hari masuk pagi</label>
        <input type="number" name="hari_pagi" min="0" value="{{ $v('hari_pagi', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Hari masuk siang</label>
        <input type="number" name="hari_siang" min="0" value="{{ $v('hari_siang', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Hari masuk malam</label>
        <input type="number" name="hari_malam" min="0" value="{{ $v('hari_malam', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Hari telat / pulang awal</label>
        <input type="number" name="hari_telat_pulang_awal" min="0" value="{{ $v('hari_telat_pulang_awal', 0) }}" class="form-control">
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_koordinator" value="1" id="is_koordinator"
                {{ $v('is_koordinator') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_koordinator">Koordinator (THD dikali 2)</label>
        </div>
        <div class="small text-muted">THD: pagi/siang Rp32.500, malam Rp37.500. Menjadi 0 bila telat + pulang awal &ge; 11 hari.</div>
    </div>
</div>

<h6 class="mt-2">Pendapatan Bulanan</h6>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">On call</label>
        <input type="number" name="nominal_on_call" min="0" value="{{ $v('nominal_on_call', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Cuti besar</label>
        <input type="number" name="nominal_cuti_besar" min="0" value="{{ $v('nominal_cuti_besar', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Rapel</label>
        <input type="number" name="nominal_rapel" min="0" value="{{ $v('nominal_rapel', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Insentif hari raya</label>
        <input type="number" name="nominal_insentif_hari_raya" min="0" value="{{ $v('nominal_insentif_hari_raya', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Pendapatan lain</label>
        <input type="number" name="nominal_pendapatan_lain" min="0" value="{{ $v('nominal_pendapatan_lain', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">PPh 21 (potongan resmi)</label>
        <input type="number" name="nominal_pph21" min="0" value="{{ $v('nominal_pph21', 0) }}" class="form-control">
    </div>
</div>
<div class="alert alert-info py-2 small">
    Uang lembur tidak diisi di sini. Nilainya diambil otomatis dari lembur yang sudah disetujui pada periode ini.
</div>

<h6 class="mt-2">Potongan Lain (D)</h6>
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Infaq</label>
        <input type="number" name="potongan_infaq" min="0" value="{{ $v('potongan_infaq') }}" class="form-control"
            placeholder="Kosongkan = 2,5% otomatis">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Angsuran pinjaman bank</label>
        <input type="number" name="potongan_angsuran_pinjaman" min="0" value="{{ $v('potongan_angsuran_pinjaman', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Angsuran bank syariah</label>
        <input type="number" name="potongan_bank_syariah" min="0" value="{{ $v('potongan_bank_syariah', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Koperasi / barokah</label>
        <input type="number" name="potongan_koperasi" min="0" value="{{ $v('potongan_koperasi', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Farmasi</label>
        <input type="number" name="potongan_farmasi" min="0" value="{{ $v('potongan_farmasi', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">BSI / Al Ikhlas</label>
        <input type="number" name="potongan_bsi" min="0" value="{{ $v('potongan_bsi', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Perumahan</label>
        <input type="number" name="potongan_perumahan" min="0" value="{{ $v('potongan_perumahan', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">BSM</label>
        <input type="number" name="potongan_bsm" min="0" value="{{ $v('potongan_bsm', 0) }}" class="form-control">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Lain-lain</label>
        <input type="number" name="potongan_lain" min="0" value="{{ $v('potongan_lain', 0) }}" class="form-control">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Catatan</label>
    <textarea name="catatan" rows="2" class="form-control">{{ $v('catatan') }}</textarea>
</div>
