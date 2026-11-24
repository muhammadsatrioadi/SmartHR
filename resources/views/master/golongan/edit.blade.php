@extends('layouts.main')

@section('title', 'Edit Golongan')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">{{ __("Edit Golongan") }}</h6>

            <p class="text-muted fst-italic mb-4">{{ __("Isi seluruh data di bawah ini. Kolom bertanda") }} <span class="text-danger">*</span> {{ __("wajib diisi.") }}</p>

            <form class="js-validate" method="POST" action="{{ route('golongan.update', $item->id) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">{{ __("Kode") }} <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                        class="form-control @error('kode') is-invalid @enderror"
                        pattern="[A-Za-z0-9]+" title="Kode hanya boleh berisi huruf dan angka" required>
                    <div class="form-text">{{ __("Hanya huruf dan angka, tanpa spasi atau karakter khusus. Contoh: GOL01") }}</div>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __("Nama") }} <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        {{ __("Aktif") }}
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('golongan.index') }}">{{ __("Batal") }}</a>
                    <button class="btn btn-primary" type="submit">{{ __("Simpan") }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
