@extends('layouts.main')

@section('title', 'Edit Skala Gaji')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit Skala Gaji Berkala</h6>

            <form method="POST" action="{{ route('salaryStep.update', $step->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode', $step->kode) }}"
                        class="form-control @error('kode') is-invalid @enderror" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="deskripsi" value="{{ old('deskripsi', $step->deskripsi) }}" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $step->gaji_pokok) }}"
                            class="form-control @error('gaji_pokok') is-invalid @enderror" required>
                        @error('gaji_pokok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tunjangan Tetap</label>
                        <input type="number" name="tunjangan_tetap" value="{{ old('tunjangan_tetap', $step->tunjangan_tetap) }}"
                            class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pola Kerja per Minggu</label>
                        <select name="hari_kerja_per_minggu" class="form-select @error('hari_kerja_per_minggu') is-invalid @enderror" required>
                            <option value="5" {{ old('hari_kerja_per_minggu', $step->hari_kerja_per_minggu) == 5 ? 'selected' : '' }}>5 hari kerja</option>
                            <option value="6" {{ old('hari_kerja_per_minggu', $step->hari_kerja_per_minggu) == 6 ? 'selected' : '' }}>6 hari kerja</option>
                        </select>
                        @error('hari_kerja_per_minggu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Masa Kerja Min (th)</label>
                        <input type="number" name="masa_kerja_min" value="{{ old('masa_kerja_min', $step->masa_kerja_min) }}"
                            class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Masa Kerja Maks (th)</label>
                        <input type="number" name="masa_kerja_maks" value="{{ old('masa_kerja_maks', $step->masa_kerja_maks) }}"
                            class="form-control">
                    </div>
                </div>

                <div class="alert alert-info py-2">
                    Dasar lembur mengikuti `gaji pokok + tunjangan tetap`, dengan upah per jam = dasar lembur / `173`.
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('salaryStep.index') }}" class="btn btn-outline-light me-2">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

