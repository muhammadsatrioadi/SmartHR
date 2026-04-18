@extends('layouts.main')

@section('title', 'Edit Shift Kerja')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit Shift Kerja</h6>

            <form method="POST" action="{{ route('workShift.update', $item->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Group Shift</label>
                    <select name="shift_group_id" class="form-select">
                        <option value="">-</option>
                        @foreach ($shiftGroups as $group)
                            <option value="{{ $group->id }}"
                                {{ (string) old('shift_group_id', $item->shift_group_id) === (string) $group->id ? 'selected' : '' }}>
                                {{ $group->kode }} - {{ $group->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Shift</label>
                    <input type="text" name="kode" value="{{ old('kode', $item->kode) }}"
                        class="form-control @error('kode') is-invalid @enderror" required>
                    @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Shift</label>
                    <input type="text" name="nama" value="{{ old('nama', $item->nama) }}"
                        class="form-control @error('nama') is-invalid @enderror" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $item->jam_masuk) }}"
                            class="form-control @error('jam_masuk') is-invalid @enderror" required>
                        @error('jam_masuk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" name="jam_pulang" value="{{ old('jam_pulang', $item->jam_pulang) }}"
                            class="form-control @error('jam_pulang') is-invalid @enderror" required>
                        @error('jam_pulang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Mulai Telat</label>
                        <input type="time" name="jam_mulai_telat"
                            value="{{ old('jam_mulai_telat', $item->jam_mulai_telat) }}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Masuk Cepat</label>
                        <input type="time" name="jam_masuk_cepat"
                            value="{{ old('jam_masuk_cepat', $item->jam_masuk_cepat) }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Toleransi Telat (menit)</label>
                    <input type="number" name="toleransi_telat_menit"
                        value="{{ old('toleransi_telat_menit', $item->toleransi_telat_menit) }}" class="form-control">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_aktif" name="is_aktif"
                        {{ old('is_aktif', $item->is_aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_aktif">
                        Aktif
                    </label>
                </div>

                <div class="d-flex justify-content-end">
                    <a class="btn btn-outline-light me-2" href="{{ route('workShift.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

