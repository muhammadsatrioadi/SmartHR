@extends('layouts.main')

@section('title', 'Edit Hari Libur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit Hari Raya & Libur</h6>

            <form method="POST" action="{{ route('holiday.update', $item->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $item->tanggal) }}"
                        class="form-control @error('tanggal') is-invalid @enderror" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Libur</label>
                    <input type="text" name="kode_libur" value="{{ old('kode_libur', $item->kode_libur) }}"
                        class="form-control @error('kode_libur') is-invalid @enderror" required>
                    @error('kode_libur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan', $item->keterangan) }}"
                        class="form-control @error('keterangan') is-invalid @enderror" required>
                    @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_tetap" name="is_tetap" value="1"
                                {{ old('is_tetap', $item->is_tetap) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_tetap">Tetap</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_hari_raya" name="is_hari_raya"
                                value="1" {{ old('is_hari_raya', $item->is_hari_raya) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_hari_raya">Hari Raya</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('holiday.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

