@extends('layouts.main')

@section('title', 'Order Lembur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">{{ __("Order Lembur Pegawai") }}</h6>

            <form method="POST" action="{{ route('overtime.store') }}">
                @csrf

                <div class="mb-3">
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

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __("Tanggal") }}</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __("Jam Mulai") }}</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control @error('jam_mulai') is-invalid @enderror" required>
                        @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __("Jam Selesai") }}</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control @error('jam_selesai') is-invalid @enderror" required>
                        @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __("Jenis Hari") }}</label>
                    <select name="jenis_hari" id="jenis_hari" class="form-select @error('jenis_hari') is-invalid @enderror" required>
                        <option value="hari_kerja" {{ old('jenis_hari')=='hari_kerja' ? 'selected' : '' }}>{{ __("Hari Kerja") }}</option>
                        <option value="hari_libur" {{ old('jenis_hari')=='hari_libur' ? 'selected' : '' }}>{{ __("Hari Libur") }}</option>
                        <option value="hari_raya" {{ old('jenis_hari')=='hari_raya' ? 'selected' : '' }}>{{ __("Hari Raya") }}</option>
                    </select>
                    <div class="form-text text-muted small" id="info-jenis-hari">{{ __("Jenis hari akan terdeteksi otomatis dari tanggal (akhir pekan = hari libur, hari libur nasional = hari raya).") }}</div>
                    @error('jenis_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_hari_kerja_terpendek"
                        name="is_hari_kerja_terpendek" {{ old('is_hari_kerja_terpendek') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_hari_kerja_terpendek">
                        {{ __("Hari libur jatuh pada hari kerja terpendek") }}
                    </label>
                </div>

                <div class="alert alert-info py-2">
                    {{ __('Nominal lembur dihitung otomatis dari setup gaji aktif pegawai: dasar lembur / `173`, lalu dikalikan tarif Depnaker sesuai hari kerja atau hari libur.') }}
                    {{ __('Total lembur per pegawai dibatasi paling banyak') }} <strong>{{ __("14 jam per minggu") }}</strong>
                    {{ __('(Senin–Minggu) dan sesuai jenis hari paling banyak per entri seperti aturan jam lembur.') }}
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __("Persetujuan pegawai (Kepmen 102/MEN/VI/2004)") }}</label>
                    <div class="form-check">
                        <input class="form-check-input @error('pegawai_telah_menyetujui') is-invalid @enderror" type="checkbox" value="1" id="pegawai_telah_menyetujui" name="pegawai_telah_menyetujui" {{ old('pegawai_telah_menyetujui') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="pegawai_telah_menyetujui">
                            {{ __('Persetujuan tertulis / bukti persetujuan lembur dari pegawai yang bersangkutan telah tersedia sebelum lembur dilaksanakan.') }}
                        </label>
                        @error('pegawai_telah_menyetujui') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __("Referensi persetujuan (opsional)") }}</label>
                    <input type="text" name="referensi_persetujuan_pegawai" value="{{ old('referensi_persetujuan_pegawai') }}" class="form-control @error('referensi_persetujuan_pegawai') is-invalid @enderror" placeholder="{{ __('Nomor surat, form persetujuan, atau keterangan dokumen') }}">
                    @error('referensi_persetujuan_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __("Keterangan Pekerjaan") }}</label>
                    <textarea name="keterangan_pekerjaan" rows="3" class="form-control @error('keterangan_pekerjaan') is-invalid @enderror" required>{{ old('keterangan_pekerjaan') }}</textarea>
                    @error('keterangan_pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('overtime.index') }}" class="btn btn-outline-light me-2">{{ __("Batal") }}</a>
                    <button class="btn btn-primary" type="submit">{{ __("Simpan") }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
(function () {
    var hariLibur = @json($hariLibur);
    var $tanggal = $('input[name="tanggal"]');
    var $jenisHari = $('#jenis_hari');
    var $info = $('#info-jenis-hari');

    function deteksiJenisHari() {
        var val = $tanggal.val();
        if (!val) return;

        var tgl = new Date(val + 'T00:00:00');
        if (isNaN(tgl.getTime())) return;

        var hari = tgl.getDay(); // 0 = Minggu, 6 = Sabtu
        var isWeekend = (hari === 0 || hari === 6);
        var isHariRaya = hariLibur.indexOf(val) !== -1;

        var jenis, label;
        if (isHariRaya) {
            jenis = 'hari_raya';
            label = 'Hari libur nasional (hari raya)';
        } else if (isWeekend) {
            jenis = 'hari_libur';
            label = 'Akhir pekan (hari libur)';
        } else {
            jenis = 'hari_kerja';
            label = 'Hari kerja';
        }

        $jenisHari.val(jenis);
        $info.text('Terdeteksi: ' + label + '.');
    }

    $tanggal.on('change', deteksiJenisHari);
    @if (old('tanggal'))
        $(deteksiJenisHari);
    @endif
})();
</script>
@endsection
