@extends('layouts.main')

@section('title', 'Entry Gaji Berkala')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Entry Gaji Berkala Pegawai</h6>

            <form method="POST" action="{{ route('salaryHistory.store') }}">
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

                <div class="mb-3">
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

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok') }}"
                            class="form-control @error('gaji_pokok') is-invalid @enderror" required>
                        @error('gaji_pokok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tunjangan Tetap</label>
                        <input type="number" name="tunjangan_tetap" value="{{ old('tunjangan_tetap', 0) }}"
                            class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tunjangan Tidak Tetap / Lain</label>
                        <input type="number" name="tunjangan_lain" value="{{ old('tunjangan_lain', 0) }}"
                            class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pola Kerja per Minggu</label>
                    <select name="hari_kerja_per_minggu" class="form-select @error('hari_kerja_per_minggu') is-invalid @enderror" required>
                        <option value="5" {{ old('hari_kerja_per_minggu', 5) == 5 ? 'selected' : '' }}>5 hari kerja</option>
                        <option value="6" {{ old('hari_kerja_per_minggu') == 6 ? 'selected' : '' }}>6 hari kerja</option>
                    </select>
                    @error('hari_kerja_per_minggu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info py-2">
                    Dasar lembur dihitung dari upah bulanan yang memenuhi ketentuan Depnaker, lalu upah per jam = dasar lembur / `173`.
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Berlaku</label>
                    <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku') }}"
                        class="form-control @error('tanggal_berlaku') is-invalid @enderror" required>
                    @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alasan</label>
                    <input type="text" name="alasan" value="{{ old('alasan') }}" class="form-control">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('salaryHistory.index') }}" class="btn btn-outline-light me-2">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

