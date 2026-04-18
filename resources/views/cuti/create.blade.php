<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('links')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .select2-container--default .select2-selection--single {
    background-color: #1f0606;
    border: 1px solid #aaa;
    border-radius: 4px
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #fff;
    line-height: 28px
}
.select2-dropdown {
    background-color: #1f0606;
    border: 1px solid #aaa;
    border-radius: 4px;
    box-sizing: border-box;
    display: block;
    position: absolute;
    left: -100000px;
    width: 100%;
    z-index: 1051
}
.select2-results__option {
    padding: 6px;
    color: white;
    user-select: none;
    -webkit-user-select: none
}
</style>

@endsection

@section('navCuti')
active
@endsection

@section('content')

    <section id="create">
      <div class="container m-5">
        <div class="row justify-content-center">
          <div class="col-8 bg-secondary rounded p-5">
            <form action="{{ route('cuti.createProses') }}" method="POST" class="text-white">
              @csrf
                @method('POST')
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label fw-medium">Nama Karyawan</label>
                <select class="form-select" aria-label="Default select example" name="karyawan_id">
                  <option selected>Pilih Karyawan</option>
                  @foreach ($karyawan as $items)
                  <option value="{{ $items->id }}">{{ $items->name }}</option>
                  @endforeach

                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">Jenis Cuti</label>
                <select class="form-select" name="leave_type_id">
                  <option value="">Pilih Jenis Cuti</option>
                  @foreach ($leaveTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->kode }} - {{ $type->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tanggal_mulai"  id="date" >
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Tanggal Berakhir</label>
                <input type="date" class="form-control" id="date" name="tanggal_berakhir" >
            </div>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label fw-medium">Keterangan</label>
                <input type="text" name="keterangan"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </section>

   
@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.form-select').select2();
});
</script>

@endsection
