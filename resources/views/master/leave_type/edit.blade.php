@extends('layouts.main')

@section('title', 'Edit Jenis Cuti')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit Jenis Cuti</h6>

            <form method="POST" action="{{ route('leaveType.update', $item->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                        class="form-control @error('kode') is-invalid @enderror" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Cuti</label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                        class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Grup</label>
                    <input type="text" name="grup" value="{{ old('grup', $item->grup) }}" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="pakai_periode"
                                name="pakai_periode" {{ old('pakai_periode', $item->pakai_periode) ? 'checked' : '' }}>
                            <label class="form-check-label" for="pakai_periode">
                                Sistem Periode
                            </label>
                        </div>
                        <select name="satuan_periode" class="form-select mt-2">
                            <option value="">-</option>
                            <option value="hari"
                                {{ old('satuan_periode', $item->satuan_periode) == 'hari' ? 'selected' : '' }}>Hari
                            </option>
                            <option value="bulan"
                                {{ old('satuan_periode', $item->satuan_periode) == 'bulan' ? 'selected' : '' }}>Bulan
                            </option>
                            <option value="tahun"
                                {{ old('satuan_periode', $item->satuan_periode) == 'tahun' ? 'selected' : '' }}>Tahun
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Expired</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="max_expired"
                                value="{{ old('max_expired', $item->max_expired) }}" class="form-control">
                            <select name="satuan_expired" class="form-select">
                                <option value="">-</option>
                                <option value="hari"
                                    {{ old('satuan_expired', $item->satuan_expired) == 'hari' ? 'selected' : '' }}>Hari
                                </option>
                                <option value="bulan"
                                    {{ old('satuan_expired', $item->satuan_expired) == 'bulan' ? 'selected' : '' }}>Bulan
                                </option>
                                <option value="tahun"
                                    {{ old('satuan_expired', $item->satuan_expired) == 'tahun' ? 'selected' : '' }}>Tahun
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jika Masa Kerja (&gt;=)</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_masa_kerja"
                                value="{{ old('min_masa_kerja', $item->min_masa_kerja) }}" class="form-control">
                            <select name="satuan_masa_kerja" class="form-select">
                                <option value="">-</option>
                                <option value="hari"
                                    {{ old('satuan_masa_kerja', $item->satuan_masa_kerja) == 'hari' ? 'selected' : '' }}>
                                    Hari
                                </option>
                                <option value="bulan"
                                    {{ old('satuan_masa_kerja', $item->satuan_masa_kerja) == 'bulan' ? 'selected' : '' }}>
                                    Bulan
                                </option>
                                <option value="tahun"
                                    {{ old('satuan_masa_kerja', $item->satuan_masa_kerja) == 'tahun' ? 'selected' : '' }}>
                                    Tahun
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max BackDate (Hari)</label>
                        <input type="number" name="max_backdate"
                            value="{{ old('max_backdate', $item->max_backdate) }}" class="form-control">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="rekap" name="rekap"
                        {{ old('rekap', $item->rekap) ? 'checked' : '' }}>
                    <label class="form-check-label" for="rekap">
                        Rekap (Ya/Tidak)
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('leaveType.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

