<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('links')
<style>

</style>
@endsection

@section('navJabatan')
active
@endsection

@section('content')

    <section id="edit">
      <div class="container m-5 ">
        <div class="row justify-content-center">
          <div class="col-10 bg-secondary rounded p-5">
            <form class="text-white text-center" action="{{ route('jabatan.update', ['id' => $data->id]) }}" method="POST">
              @csrf
                @method('PUT')
                <div class="row justify-content-center">
                  <div class="col-5">
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Nama Jabatan</label>
                      <input type="text" name="nama_jabatan" value="{{ $data->nama_jabatan }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Jam Mulai Kerja</label>
                      <input type="time" name="jam_mulai_kerja" value="{{ $data->jam_mulai_kerja }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Jam Selesai Kerja</label>
                      <input type="time" name="jam_selesai_kerja" value="{{ $data->jam_selesai_kerja }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Note Pekerjaan</label>
                      <input type="text" name="note_pekerjaan" value="{{ $data->note_pekerjaan }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    
                  </div>
                  <div class="col-5">
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Gaji Pokok</label>
                      <input type="text" name="gaji_pokok" value="{{ $data->gaji_pokok }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Tunjangan</label>
                      <input type="text" name="tunjangan" value="{{ $data->tunjangan }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Potongan</label>
                      <input type="text" name="potongan" value="{{ $data->potongan }}" class="form-control bg-secondary" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                  </div>
                </div>
              
              
              <button type="submit" class="btn btn-primary mb-5 mt-3">Edit</button>
            </form>
          </div>
        </div>
      </div>
    </section>

   
@endsection
