@extends('layouts.main')

@section('title', 'Data Gaji Dasar Pegawai')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-1">Data Gaji Dasar Pegawai</h6>
                <p class="text-muted small">
                    Halaman ini menyimpan komponen tetap (kelompok 1) yang menjadi acuan penggajian.
                    Komponen otomatis (tunjangan keluarga, beras, BPJS, dana pensiun, infaq) dan komponen bulanan
                    dihitung saat slip gaji dibuat.
                </p>

                <form method="POST" action="{{ route('salaryHistory.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Skala Gaji (opsional)</label>
                            <select name="salary_step_id" class="form-select">
                                <option value="">-</option>
                                @foreach ($steps as $s)
                                    <option value="{{ $s->id }}" {{ old('salary_step_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->kode }} - {{ $s->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="mt-2">Komponen Tetap</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gaji Pokok</label>
                            <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok') }}"
                                class="form-control @error('gaji_pokok') is-invalid @enderror" required>
                            @error('gaji_pokok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Jabatan</label>
                            <input type="number" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Fungsional</label>
                            <input type="number" name="tunjangan_fungsional" value="{{ old('tunjangan_fungsional', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Profesi</label>
                            <input type="number" name="tunjangan_profesi" value="{{ old('tunjangan_profesi', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Makan</label>
                            <input type="number" name="tunjangan_makan" value="{{ old('tunjangan_makan', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">TKP</label>
                            <input type="number" name="tkp" value="{{ old('tkp', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Pulsa</label>
                            <input type="number" name="tunjangan_pulsa" value="{{ old('tunjangan_pulsa', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tunjangan Lain (tidak tetap)</label>
                            <input type="number" name="tunjangan_lain" value="{{ old('tunjangan_lain', 0) }}" class="form-control">
                        </div>
                    </div>

                    <h6 class="mt-2">Status Keluarga (dasar tunjangan keluarga & beras)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="pasangan_ditanggung" value="1"
                                    id="pasangan_ditanggung" {{ old('pasangan_ditanggung') ? 'checked' : '' }}>
                                <label class="form-check-label" for="pasangan_ditanggung">Pasangan ditanggung</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah Anak Ditanggung (maks. 3 dihitung)</label>
                            <input type="number" name="jumlah_anak_ditanggung" min="0" max="10"
                                value="{{ old('jumlah_anak_ditanggung', 0) }}" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pola Kerja per Minggu</label>
                            <select name="hari_kerja_per_minggu" class="form-select @error('hari_kerja_per_minggu') is-invalid @enderror" required>
                                <option value="5" {{ old('hari_kerja_per_minggu', 5) == 5 ? 'selected' : '' }}>5 hari kerja</option>
                                <option value="6" {{ old('hari_kerja_per_minggu') == 6 ? 'selected' : '' }}>6 hari kerja</option>
                            </select>
                            @error('hari_kerja_per_minggu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku') }}"
                                class="form-control @error('tanggal_berlaku') is-invalid @enderror" required>
                            @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Alasan / Nomor SK</label>
                            <input type="text" name="alasan" value="{{ old('alasan') }}" class="form-control">
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small mb-3">
                        Dasar upah lembur dihitung dari upah bulanan yang memenuhi ketentuan Depnaker, lalu upah per jam = dasar lembur / 173.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('salaryHistory.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
