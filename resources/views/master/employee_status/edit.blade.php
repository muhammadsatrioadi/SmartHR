@extends('layouts.main')

@section('title', 'Edit Status Karyawan')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit Status Karyawan</h6>

            <p class="text-muted fst-italic mb-4">Isi seluruh data di bawah ini. Kolom bertanda <span class="text-danger">*</span> wajib diisi.</p>

            <form class="js-validate" method="POST" action="{{ route('employeeStatus.update', $item->id) }}" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}" class="form-control @error('kode') is-invalid @enderror" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Status <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_payroll" name="is_payroll" {{ old('is_payroll', $item->is_payroll) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_payroll">
                                Termasuk Payroll
                            </label>
                            <div class="form-text fst-italic">
                                Centang bila pegawai dengan status ini diikutkan pada penggajian/rekap gaji bulanan.
                                Kosongkan untuk status yang tidak digaji (mis. magang/peserta didik).
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                            <div class="form-text fst-italic">
                                Centang bila status ini masih dipakai dan muncul pada pilihan data karyawan.
                                Kosongkan untuk menonaktifkan tanpa menghapus datanya.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('employeeStatus.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
