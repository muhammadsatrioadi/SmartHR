@extends('layouts.main')

@section('title', 'Order Lembur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Order Lembur Pegawai</h6>

            <form method="POST" action="{{ route('overtime.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pegawai</label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                        <option value="">Pilih Pegawai</option>
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
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control @error('jam_mulai') is-invalid @enderror" required>
                        @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control @error('jam_selesai') is-invalid @enderror" required>
                        @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Hari</label>
                    <select name="jenis_hari" class="form-select @error('jenis_hari') is-invalid @enderror" required>
                        <option value="hari_kerja" {{ old('jenis_hari')=='hari_kerja' ? 'selected' : '' }}>Hari Kerja</option>
                        <option value="hari_libur" {{ old('jenis_hari')=='hari_libur' ? 'selected' : '' }}>Hari Libur</option>
                        <option value="hari_raya" {{ old('jenis_hari')=='hari_raya' ? 'selected' : '' }}>Hari Raya</option>
                    </select>
                    @error('jenis_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_hari_kerja_terpendek"
                        name="is_hari_kerja_terpendek" {{ old('is_hari_kerja_terpendek') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_hari_kerja_terpendek">
                        Hari libur jatuh pada hari kerja terpendek
                    </label>
                </div>

                <div class="alert alert-info py-2">
                    Nominal lembur dihitung otomatis dari setup gaji aktif pegawai: dasar lembur / `173`, lalu dikalikan tarif Depnaker sesuai hari kerja atau hari libur.
                    Total lembur per pegawai dibatasi paling banyak <strong>14 jam per minggu</strong> (Senin–Minggu) dan sesuai jenis hari paling banyak per entri seperti aturan jam lembur.
                </div>

                <div class="mb-3">
                    <label class="form-label">Persetujuan pegawai (Kepmen 102/MEN/VI/2004)</label>
                    <div class="form-check">
                        <input class="form-check-input @error('pegawai_telah_menyetujui') is-invalid @enderror" type="checkbox" value="1" id="pegawai_telah_menyetujui" name="pegawai_telah_menyetujui" {{ old('pegawai_telah_menyetujui') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="pegawai_telah_menyetujui">
                            Persetujuan tertulis / bukti persetujuan lembur dari pegawai yang bersangkutan telah tersedia sebelum lembur dilaksanakan.
                        </label>
                        @error('pegawai_telah_menyetujui') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Referensi persetujuan (opsional)</label>
                    <input type="text" name="referensi_persetujuan_pegawai" value="{{ old('referensi_persetujuan_pegawai') }}" class="form-control @error('referensi_persetujuan_pegawai') is-invalid @enderror" placeholder="Nomor surat, form persetujuan, atau keterangan dokumen">
                    @error('referensi_persetujuan_pegawai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan Pekerjaan</label>
                    <textarea name="keterangan_pekerjaan" rows="3" class="form-control @error('keterangan_pekerjaan') is-invalid @enderror" required>{{ old('keterangan_pekerjaan') }}</textarea>
                    @error('keterangan_pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('overtime.index') }}" class="btn btn-outline-light me-2">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

