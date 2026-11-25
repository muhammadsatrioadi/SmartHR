@extends('layouts.main')

@section('title', 'Data Gaji Dasar Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-1">{{ __("Data Gaji Dasar Pegawai") }}</h6>
                <p class="text-muted small">
                    Halaman ini menyimpan komponen tetap (kelompok 1) yang menjadi acuan penggajian.
                    Komponen otomatis (tunjangan keluarga, beras, BPJS, dana pensiun, infaq) dan komponen bulanan
                    dihitung saat slip gaji dibuat.
                </p>

                <form method="POST" action="{{ route('salaryHistory.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __("Pegawai") }}</label>
                            <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                                <option value="">{{ __("Pilih Pegawai") }}</option>
                                @foreach ($karyawans as $k)
                                    <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nik ?? '' }} - {{ $k->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('karyawan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __("Skala Gaji") }}</label>
                            <select name="salary_step_id_display" id="salary_step_id_display" class="form-select" disabled>
                                <option value="">{{ __("- (otomatis sesuai masa kerja)") }}</option>
                                @foreach ($steps as $s)
                                    <option value="{{ $s->id }}">
                                        {{ $s->kode }} - {{ $s->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="salary_step_id" id="salary_step_id" value="{{ old('salary_step_id') }}">
                            <div class="form-text text-muted small">
                                Skala gaji ditentukan otomatis berdasarkan masa kerja karyawan dan tidak dapat diubah.
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-2">{{ __("Komponen Tetap") }}</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Gaji Pokok") }}</label>
                            <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok') }}"
                                class="form-control @error('gaji_pokok') is-invalid @enderror" required>
                            @error('gaji_pokok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Jabatan") }}</label>
                            <input type="number" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Fungsional") }}</label>
                            <input type="number" name="tunjangan_fungsional" value="{{ old('tunjangan_fungsional', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Profesi") }}</label>
                            <input type="number" name="tunjangan_profesi" value="{{ old('tunjangan_profesi', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Makan") }}</label>
                            <input type="number" name="tunjangan_makan" value="{{ old('tunjangan_makan', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("TKP") }}</label>
                            <input type="number" name="tkp" value="{{ old('tkp', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Pulsa") }}</label>
                            <input type="number" name="tunjangan_pulsa" value="{{ old('tunjangan_pulsa', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tunjangan Lain (tidak tetap)") }}</label>
                            <input type="number" name="tunjangan_lain" value="{{ old('tunjangan_lain', 0) }}" class="form-control">
                        </div>
                    </div>

                    <h6 class="mt-2">{{ __("Status Keluarga (dasar tunjangan keluarga & beras)") }}</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="pasangan_ditanggung" value="1"
                                    id="pasangan_ditanggung" {{ old('pasangan_ditanggung') ? 'checked' : '' }}>
                                <label class="form-check-label" for="pasangan_ditanggung">{{ __("Pasangan ditanggung") }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Jumlah Anak Ditanggung (maks. 3 dihitung)") }}</label>
                            <input type="number" name="jumlah_anak_ditanggung" min="0" max="10"
                                value="{{ old('jumlah_anak_ditanggung', 0) }}" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Pola Kerja per Minggu") }}</label>
                            <select name="hari_kerja_per_minggu" class="form-select @error('hari_kerja_per_minggu') is-invalid @enderror" required>
                                <option value="5" {{ old('hari_kerja_per_minggu', 5) == 5 ? 'selected' : '' }}>{{ __("5 hari kerja") }}</option>
                                <option value="6" {{ old('hari_kerja_per_minggu') == 6 ? 'selected' : '' }}>{{ __("6 hari kerja") }}</option>
                            </select>
                            @error('hari_kerja_per_minggu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Tanggal Berlaku") }}</label>
                            <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku') }}"
                                class="form-control @error('tanggal_berlaku') is-invalid @enderror" required>
                            @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ __("Alasan / Nomor SK") }}</label>
                            <input type="text" name="alasan" value="{{ old('alasan') }}" class="form-control">
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small mb-3">
                        Dasar upah lembur dihitung dari upah bulanan yang memenuhi ketentuan Depnaker, lalu upah per jam = dasar lembur / 173.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('salaryHistory.index') }}" class="btn btn-outline-secondary me-2">{{ __("Batal") }}</a>
                        <button class="btn btn-primary" type="submit">{{ __("Simpan") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
(function () {
    // Data pegawai & skala gaji yang dikirim controller untuk auto-fill sisi klien.
    var karyawanData = @json($karyawanData);
    var stepData = @json($stepData);

    var $karyawan = $('select[name="karyawan_id"]');
    var $stepDisplay = $('#salary_step_id_display');
    var $stepHidden = $('#salary_step_id');

    // Field komponen tetap yang diisi otomatis (tetap bisa diedit oleh user).
    var fieldMap = {
        gaji_pokok: 'gaji_pokok',
        tunjangan_jabatan: 'tunjangan_jabatan',
        tunjangan_fungsional: 'tunjangan_fungsional',
        tunjangan_profesi: 'tunjangan_profesi',
        tunjangan_makan: 'tunjangan_makan',
        tunjangan_pulsa: 'tunjangan_pulsa',
        tkp: 'tkp'
    };

    // Hitung masa kerja (tahun) dari tanggal masuk, meniru Carbon diffInYears.
    function hitungMasaKerja(tanggalMasuk) {
        if (!tanggalMasuk) return 0;
        var masuk = new Date(tanggalMasuk);
        var sekarang = new Date();
        if (isNaN(masuk.getTime())) return 0;
        var tahun = sekarang.getFullYear() - masuk.getFullYear();
        var bulan = sekarang.getMonth() - masuk.getMonth();
        if (bulan < 0 || (bulan === 0 && sekarang.getDate() < masuk.getDate())) {
            tahun--;
        }
        return tahun < 0 ? 0 : tahun;
    }

    // Tentukan skala gaji yang berlaku: masa_kerja_min terbesar yang masih
    // <= masa kerja, dan masih < masa_kerja_maks (bila diisi).
    function tentukanSkalaGaji(tanggalMasuk) {
        if (!tanggalMasuk) return null;
        var masaKerja = hitungMasaKerja(tanggalMasuk);
        var sorted = stepData.slice().sort(function (a, b) {
            return b.masa_kerja_min - a.masa_kerja_min;
        });
        for (var i = 0; i < sorted.length; i++) {
            var step = sorted[i];
            if (step.masa_kerja_min <= masaKerja &&
                (step.masa_kerja_maks === null || masaKerja < step.masa_kerja_maks)) {
                return step.id;
            }
        }
        return null;
    }

    // Isi seluruh field dari data jabatan + status keluarga karyawan.
    function isiOtomatis(karyawanId) {
        var data = null;
        for (var i = 0; i < karyawanData.length; i++) {
            if (karyawanData[i].id == karyawanId) {
                data = karyawanData[i];
                break;
            }
        }
        if (!data) return;

        $.each(fieldMap, function (key, name) {
            var $field = $('input[name="' + name + '"]');
            if ($field.length) {
                $field.val(data[key] != null ? data[key] : 0);
            }
        });

        // Tunjangan lain tidak berasal dari jabatan, default 0 (bisa diedit).
        $('input[name="tunjangan_lain"]').val(0);

        // Status keluarga
        $('input[name="pasangan_ditanggung"]').prop('checked', !!data.pasangan_ditanggung);
        $('input[name="jumlah_anak_ditanggung"]').val(data.jumlah_anak_ditanggung || 0);

        // Pola kerja per minggu
        $('select[name="hari_kerja_per_minggu"]').val(data.hari_kerja_per_minggu || 5);

        // Skala gaji otomatis (tidak bisa diubah)
        var skalaId = tentukanSkalaGaji(data.tanggal_masuk);
        $stepDisplay.val(skalaId != null ? skalaId : '');
        $stepHidden.val(skalaId != null ? skalaId : '');
    }

    // Saat pegawai diganti, isi otomatis semua field.
    $karyawan.on('change', function () {
        if (this.value) {
            isiOtomatis(this.value);
        } else {
            $stepDisplay.val('');
            $stepHidden.val('');
        }
    });

    // Saat halaman dimuat ulang (mis. error validasi) dan karyawan sudah terpilih,
    // set ulang tampilan skala gaji saja agar sesuai masa kerja (field lain
    // tetap memakai nilai lama dari input user).
    @if (old('karyawan_id'))
        (function () {
            var id = {{ old('karyawan_id') }};
            var data = null;
            for (var i = 0; i < karyawanData.length; i++) {
                if (karyawanData[i].id == id) { data = karyawanData[i]; break; }
            }
            if (data) {
                var skalaId = tentukanSkalaGaji(data.tanggal_masuk);
                $stepDisplay.val(skalaId != null ? skalaId : '');
                $stepHidden.val(skalaId != null ? skalaId : '');
            }
        })();
    @endif
})();
</script>
@endsection
