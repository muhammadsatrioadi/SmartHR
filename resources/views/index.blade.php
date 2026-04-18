<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('navDashboard')
active
@endsection
@section('content')
    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Absensi Hadir Hari Ini</p>
                        <h6 class="mb-0">{{ $totalKehadiranHariIni }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Absensi Alpha Hari Ini</p>
                        <h6 class="mb-0">{{ $totalAlphaHariIni }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Absensi Hadir Bulan Ini</p>
                        <h6 class="mb-0">{{ $totalKehadiranBulanIni }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Absensi Alpha Bulan Ini</p>
                        <h6 class="mb-0">{{ $totalAlphaBulanIni }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Data Semua Karyawan</p>
                        <h6 class="mb-0">{{ $dataKaryawan }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Rekap Total Cuti Hari Ini</p>
                        <h6 class="mb-0">{{ $totalCutiHariIni }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Rekap Total Cuti Bulan Ini</p>
                        <h6 class="mb-0">{{ $dataCuti }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Pengeluaran Gaji Seluruh Karyawan</p>
                        <h6 class="mb-0">{{ $totalGaji }}</h6>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Sale & Revenue End -->


    <!-- Sales Chart Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Grafik Karyawan Kehadiran</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="absensi-trafik"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Grafik Karyawan Alpha</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="absensi-trafik-alpha"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Sales Chart End -->


    <!-- Recent Absensi start-->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Absensi Terbaru</h6>
                <a href="{{ route('absensi.read') }}">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jam</th>
                                <th scope="col">bulan dan tahun</th>
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
    <!-- Recent Absensi End -->

    <!-- Recent Cuti Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Data Cuti Terbaru</h6>
                <a href="{{ route('cuti.read') }}">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                            <tr class="text-center">
                                <th scope="col">No</th>
                                <th scope="col">Nama Kariyawan</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Berakhir</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Jenis Cuti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataCutis as $items)
                                <tr class="text-center">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ optional($items->karyawan)->name }}</td>
                                    <td>{{ $items->tanggal_mulai }}</td>
                                    <td>{{ $items->tanggal_berakhir }}</td>
                                    <td>{{ $items->keterangan }}</td>
                                    <td>{{ $items->jenis_cuti }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->


@endsection

@section('script')
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('absensi-trafik').getContext('2d');

            // Mengonversi data PHP ke JSON untuk digunakan di JavaScript
            var chartData = @json($dataAbsensi);
            var labels = Object.keys(chartData).map(function(key) {
                var date = new Date(key);
                return date.toLocaleDateString();
            });
            var values = Object.values(chartData);

            var myChart = new Chart(ctx, {
                type: 'line', // Menggunakan grafik garis untuk kehadiran harian
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Kehadiran',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('absensi-trafik-alpha').getContext('2d');

            // Mengonversi data PHP ke JSON untuk digunakan di JavaScript
            var chartData = @json($dataAlpha);
            var labels = Object.keys(chartData).map(function(key) {
                var date = new Date(key);
                return date.toLocaleDateString();
            });
            var values = Object.values(chartData);

            var myChart = new Chart(ctx, {
                type: 'line', // Menggunakan grafik garis untuk kehadiran harian
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Alpha',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection

