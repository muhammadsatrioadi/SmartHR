@extends('layouts.main')

@section('title', 'Absensi')

@section('content')
    <div class="container-fluid pt-4 px-4">

        <h1>Absensi</h1>

        <div class="card bg-secondary rounded mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawanBelumAbsen as $karyawanBelumAbsens)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $karyawanBelumAbsens->name }}</td>
                                    <form action="{{ route('absensi.createProses') }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="karyawan_id" value="{{ $karyawanBelumAbsens->id }}">
                                        <td>
                                            <input type="text" name="keterangan" class="form-control">
                                        </td>
                                        <td>
                                            <button type="submit" name="status_absen" value="hadir"
                                                class="btn btn-success">Hadir</button>
                                            <button type="submit" name="status_absen" value="alpha"
                                                class="btn btn-danger">Alpha</button>
                                    </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @if (session('success'))
        <script>
            alert("{{ session('success') }}")
        </script>
    @endif
@endpush
