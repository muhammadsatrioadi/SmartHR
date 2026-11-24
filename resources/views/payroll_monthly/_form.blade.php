@php
    $v = fn ($kolom, $default = null) => old($kolom, $rekap->{$kolom} ?? $default);
    $lockIdentitas = $lockIdentitas ?? false;
    $usulanPph21 = $usulanPph21 ?? null;
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
        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btn-tarik-absensi">
            Tarik Kehadiran dari Absensi
        </button>
        <span class="small text-muted ms-2" id="info-tarik-absensi"></span>
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
        <input type="number" name="nominal_pph21" min="0" value="{{ $v('nominal_pph21', 0) }}" class="form-control" id="input-pph21">
        @if ($usulanPph21)
            <div class="small text-muted mt-1">
                Usulan: <strong>{{ number_format($usulanPph21['pph_sebulan'], 0, ',', '.') }}</strong>/bulan
                <button type="button" class="btn btn-link btn-sm p-0 align-baseline" id="btn-isi-pph21"
                    data-usulan="{{ (int) round($usulanPph21['pph_sebulan']) }}">Isi otomatis</button>
            </div>
        @else
            <div class="small text-muted mt-1">Usulan otomatis muncul setelah pegawai &amp; periode dipilih.</div>
        @endif
    </div>
</div>
<div class="alert alert-info py-2 small">
    Uang lembur tidak diisi di sini. Nilainya diambil otomatis dari lembur yang sudah disetujui pada periode ini.
    Reimbursement juga otomatis: reimburse nota bulan sebelumnya yang sudah disetujui masuk sebagai pendapatan periode ini.
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

<script>
    (function () {
        var form = document.currentScript.closest('form');
        if (!form) return;

        var btnTarik = form.querySelector('#btn-tarik-absensi');
        if (btnTarik) {
            btnTarik.addEventListener('click', function () {
                var karyawan = form.querySelector('[name="karyawan_id"]');
                var periode = form.querySelector('[name="periode"]');
                var token = form.querySelector('[name="_token"]');
                var info = form.querySelector('#info-tarik-absensi');

                if (!karyawan || !karyawan.value) {
                    if (info) info.textContent = 'Pilih pegawai terlebih dahulu.';
                    return;
                }

                btnTarik.disabled = true;
                if (info) info.textContent = 'Menarik data absensi...';

                fetch("{{ route('payrollMonthly.tarikAbsensi') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token ? token.value : '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        karyawan_id: karyawan.value,
                        periode: periode ? periode.value : ''
                    })
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (!data.success) {
                            if (info) info.textContent = data.message || 'Gagal menarik data.';
                            return;
                        }
                        ['pagi', 'siang', 'malam'].forEach(function (kunci) {
                            var input = form.querySelector('[name="hari_' + kunci + '"]');
                            if (input) input.value = data['hari_' + kunci] || 0;
                        });
                        if (info) info.textContent = 'Kehadiran diisi: ' + data.total_hari + ' hari (telat/pulang awal manual).';
                    })
                    .catch(function () { if (info) info.textContent = 'Gagal menarik data absensi.'; })
                    .finally(function () { btnTarik.disabled = false; });
            });
        }

        var btnIsi = form.querySelector('#btn-isi-pph21');
        if (btnIsi) {
            btnIsi.addEventListener('click', function () {
                var input = form.querySelector('#input-pph21');
                if (input) {
                    input.value = btnIsi.getAttribute('data-usulan');
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        }
    })();
</script>
