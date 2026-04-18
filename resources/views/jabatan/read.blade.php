<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('links')
<style>
    #table-jabatan{
        font-size: 0.7rem;
    }
    #table-jabatan .opsi a,
    #table-jabatan .opsi button {
        font-size: 0.6rem;
    }

    
</style>
@endsection

@section('navJabatan')
active
@endsection

@section('content')

    <section id="read" class="m-5 mt-4 " >
        <div class="container bg-secondary rounded p-3" style="margin-bottom: 10rem">
            <div id="menu-sorting-jabatan" class="" >
                <a href="{{ route('jabatan.create') }}" class="btn btn-primary mt-4 m-3">Buat Jabatan Baru</a>
                <div id="search-jabatan" class="ms-3 me-3 d-flex">
                    <form action="{{ route('jabatan.search') }}" class="d-flex w-50">
                        <div class="input-group">
                            <span class="input-group-text bg-white" id="basic-addon1"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="jabatan"
                                @if (session()->has('jabatan')) value=" {{ session('jabatan') }} "
                        @else
                        value="" @endif
                                placeholder="Cari Jabatan" aria-describedby="basic-addon1">
                        </div>
                        <button type="submit" class="btn btn-secondary fw-medium ms-2">Cari</button>
                        <select class="form-select w-50 ms-3" name="urutan" aria-label="Default select example">
                            <option selected value="asc">Urutkan</option>
                            <option value="asc">Teratas</option>
                            <option value="desc">Terbawah</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col">
                    <table class="table table-dark table-striped" id="table-jabatan">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">No</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Jam Mulai Kerja</th>
                                <th scope="col">Jam Selesai Kerja</th>
                                <th scope="col">Note Pekerjaan</th>
                                <th scope="col">Gaji Pokok</th>
                                <th scope="col">Tunjangan</th>
                                <th scope="col">Potongan</th>
                                <th scope="col">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $items)
                                <tr class="text-center">
                                    <th scope="row">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</th>
                                    <td>{{ $items->nama_jabatan }}</td>
                                    <td>{{ substr($items->jam_mulai_kerja,0,5) }}</td>
                                    <td>{{ substr($items->jam_selesai_kerja,0,5) }}</td>
                                    <td style="width: 20%;">{{ $items->note_pekerjaan }}</td>
                                    <td>{{ $items->gaji_pokok }}</td>
                                    <td>{{ $items->tunjangan }}</td>
                                    <td>{{ $items->potongan }}</td>
                                    <td class="opsi"><a href="{{ route('jabatan.edit', ['id' => $items->id]) }}"
                                            class="btn btn-warning d-inline"> Edit </a> | <form
                                            action="{{ route('jabatan.delete', ['id' => $items->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="d-flex justify-content-between px-5">
            <div class="d-flex gap-2">
                <p>Showing</p>
                {{ $data->firstItem() }}
                <p>to</p>
                {{ $data->lastItem() }}
            </div>
            <div>
                {{ $data->links() }}
            </div>
            </div>
            
        </div>

    </section>

   
@endsection
