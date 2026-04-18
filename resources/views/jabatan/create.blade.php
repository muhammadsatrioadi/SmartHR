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

    <section id="create" class=" m-5 ">
      <div class="container ">
        <div class="row justify-content-center">
          <div class="col-9 bg-secondary rounded p-5">
          <p class="h3 fw-medium text-center text-white mb-5">Buat Jabatan Baru</p>

            <form class="text-white text-center" action="{{ route('jabatan.createProses') }}" method="POST">
              @csrf
                @method('POST')
                <div class="row justify-content-between">
                  <div class="col-5">
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Nama Jabatan</label>
                      <input type="text" name="nama_jabatan"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Jam Mulai Kerja</label>
                      <input type="time" name="jam_mulai_kerja"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text fst-italic">contoh : 8</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Jam Selesai Kerja</label>
                      <input type="time" name="jam_selesai_kerja"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text fst-italic">contoh : 8</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Note Pekerjaan</label>
                      <input type="text" name="note_pekerjaan"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                  </div>
                  <div class="col-5">
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Gaji Pokok</label>
                      <input type="text" name="gaji_pokok"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text fst-italic">contoh : 1000000</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Tunjangan</label>
                      <input type="text" name="tunjangan"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text fst-italic">contoh : 1000000</div>
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label fw-medium">Potongan</label>
                      <input type="text" name="potongan"  class="form-control text-white" id="exampleInputEmail1" aria-describedby="emailHelp">
                      <div id="emailHelp" class="form-text fst-italic">contoh : 1000000</div>
                    </div>
                  </div>
                </div>
              
              
              <button type="submit" class="btn btn-primary mb-4 mt-3">Buat Jabatan Baru</button>
            </form>
          </div>
        </div>
      </div>
    </section>

   
@endsection
