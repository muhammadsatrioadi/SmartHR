@extends('layouts.main')

@section('title', 'Tambah Group Shift')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Tambah Group Shift</h6>

            <form method="POST" action="{{ route('shiftGroup.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode Group</label>
                    <input type="text" name="kode" value="{{ old('kode') }}"
                        class="form-control @error('kode') is-invalid @enderror"
                        pattern="[A-Za-z0-9]+" oninput="this.value=this.value.replace(/[^A-Za-z0-9]/g,'')" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Group</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="form-control @error('nama') is-invalid @enderror"
                        pattern="[A-Za-z0-9 ]+" oninput="this.value=this.value.replace(/[^A-Za-z0-9 ]/g,'')" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Absen</label>
                    <input type="text" name="tipe_absen" value="{{ old('tipe_absen') }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Istirahat (menit) <span class="text-danger">*</span></label>
                    <input type="number" name="istirahat_menit" value="{{ old('istirahat_menit') }}"
                        min="1" step="1" class="form-control @error('istirahat_menit') is-invalid @enderror" required>
                    <small class="text-muted">Wajib diisi, minimal 1 menit.</small>
                    @error('istirahat_menit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('shiftGroup.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

