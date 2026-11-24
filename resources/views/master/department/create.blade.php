@extends('layouts.main')

@section('title', 'Tambah Departemen')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Tambah Departemen</h6>

            <form method="POST" action="{{ route('department.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" value="{{ old('kode') }}" class="form-control @error('kode') is-invalid @enderror" pattern="[A-Za-z0-9]+" oninput="this.value=this.value.replace(/[^A-Za-z0-9]/g,'')" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" pattern="[A-Za-z0-9 ]+" oninput="this.value=this.value.replace(/[^A-Za-z0-9 ]/g,'')" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Manajer Departemen <span class="text-danger">*</span></label>
                    <select name="manager_karyawan_id" class="form-select @error('manager_karyawan_id') is-invalid @enderror" required>
                        <option value="">- Pilih Manajer -</option>
                        @foreach ($managers as $m)
                            <option value="{{ $m->id }}" {{ (string) old('manager_karyawan_id') === (string) $m->id ? 'selected' : '' }}>
                                {{ $m->nik }} - {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Manajer ini yang menerima persetujuan cuti &amp; lembur karyawan di departemen ini.</small>
                    @error('manager_karyawan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Aktif
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('department.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

