@extends('layouts.main')

@section('title', 'Daftar Presensi Alpha')

@section('content')
    <div class="container-fluid pt-4 px-4">

        <h1>{{ __("Daftar Presensi Alpha") }}</h1>

        <div class="card bg-secondary rounded mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __("No") }}</th>
                                <th scope="col">{{ __("Nama") }}</th>
                                <th scope="col">{{ __("Status") }}</th>
                                <th scope="col">{{ __("Tanggal") }}</th>
                                <th scope="col">{{ __("Jam") }}</th>
                                <th scope="col">{{ __("bulan dan tahun") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absensis as $absensi)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $absensi->karyawan->name }}</td>
                                    <td>{{ $absensi->status_absen }}</td>
                                    <td>{{ $absensi->tanggal_absensi }}</td>
                                    <td>{{ $absensi->time }}</td>
                                    <td>{{ \Carbon\Carbon::parse($absensi->tanggal_absensi)->isoFormat('MMMM, YYYY') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
