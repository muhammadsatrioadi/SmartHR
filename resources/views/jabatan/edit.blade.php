@extends('layouts.main')

@section('title', 'Edit Jabatan')

@section('links')
<style>

</style>
@endsection

@section('navJabatan')
active
@endsection

@section('content')

    <section id="edit" class="m-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-9 bg-secondary rounded p-5">
            <p class="h3 fw-medium text-center text-white mb-5">Edit Jabatan</p>

            <form class="text-white js-validate" action="{{ route('jabatan.update', ['id' => $data->id]) }}" method="POST" novalidate>
              @csrf
                @method('PUT')
                <div class="row justify-content-between">
                  <div class="col-5">
                    <div class="mb-3">
                      <label class="form-label fw-medium">Nama Jabatan <span class="text-danger">*</span></label>
                      <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $data->nama_jabatan) }}" class="form-control text-white" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Jam Mulai Kerja <span class="text-danger">*</span></label>
                      <input type="time" name="jam_mulai_kerja" value="{{ old('jam_mulai_kerja', $data->jam_mulai_kerja) }}" class="form-control text-white" required>
                      <div class="form-text fst-italic">contoh : 08:00</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Jam Selesai Kerja <span class="text-danger">*</span></label>
                      <input type="time" name="jam_selesai_kerja" value="{{ old('jam_selesai_kerja', $data->jam_selesai_kerja) }}" class="form-control text-white" required>
                      <div class="form-text fst-italic">contoh : 17:00</div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-medium">Note Pekerjaan <span class="text-danger">*</span></label>
                      <input type="text" name="note_pekerjaan" value="{{ old('note_pekerjaan', $data->note_pekerjaan) }}" class="form-control text-white" required>
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mb-4 mt-3">
                  <a href="{{ route('jabatan.read') }}" class="btn btn-outline-light">Batal</a>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </section>

@endsection
