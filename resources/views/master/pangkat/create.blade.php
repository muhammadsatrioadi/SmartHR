@extends('layouts.main')

@section('title', 'Buat Pangkat')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Buat Pangkat</h6>

            <p class="text-muted fst-italic mb-4">Isi seluruh data di bawah ini. Kolom bertanda <span class="text-danger">*</span> wajib diisi.</p>

            <form class="js-validate" method="POST" action="{{ route('pangkat.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}" class="form-control @error('kode') is-invalid @enderror" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('pangkat.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
