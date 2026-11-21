@extends('layouts.main')

@section('title', 'Proses Rekap Gaji Massal')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <h6 class="mb-1">Proses Rekap Gaji Massal</h6>
                    <p class="text-muted small mb-0">
                        Membuat &amp; memeriksa rekap seluruh pegawai untuk satu periode, lalu mengekspornya ke CSV.
                    </p>
                </div>
                <div class="d-flex align-items-end gap-2">
                    <form class="d-flex align-items-end gap-2" method="GET" action="{{ route('payrollMonthly.batch') }}">
                        <div>
                            <label class="form-label mb-1">Periode</label>
                            <input type="month" name="periode" value="{{ $periode }}" class="form-control">
                        </div>
                        <button class="btn btn-primary" type="submit">Tampilkan</button>
                    </form>
                    <a class="btn btn-outline-success" href="{{ route('payrollMonthly.export', ['periode' => $periode]) }}">Ekspor CSV</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <div class="small text-muted">
                    {{ count($baris) }} pegawai &middot; periode {{ \App\Support\PanduanGajiCalculator::labelPeriode($periode) }}
                </div>
                <form method="POST" action="{{ route('payrollMonthly.batchStore') }}"
                    onsubmit="return confirm('Buat rekap draft untuk semua pegawai yang belum punya rekap pada periode ini?')">
                    @csrf
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    <button class="btn btn-primary" type="submit">Buat Rekap yang Belum Ada</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Jabatan</th>
                            <th class="text-center">Rekap</th>
                            <th class="text-end">Penghasilan (A)</th>
                            <th class="text-end">Resmi (B)</th>
                            <th class="text-end">Lain (D)</th>
                            <th class="text-end">Terima Bersih</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($baris as $row)
                            <tr>
                                <td>
                                    {{ $row['karyawan']->name }}
                                    <div class="small text-muted">{{ $row['karyawan']->nik ?: '-' }}</div>
                                </td>
                                <td>{{ $row['karyawan']->jabatan->nama_jabatan ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($row['rekap'])
                                        <span class="badge bg-{{ $row['rekap']->status === 'final' ? 'success' : 'secondary' }}">
                                            {{ $row['rekap']->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">belum</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($row['slip']['total_pendapatan'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($row['slip']['total_potongan_resmi'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($row['slip']['total_potongan_lain'], 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ number_format($row['slip']['terima_bersih'], 0, ',', '.') }}</td>
                                <td class="text-nowrap">
                                    @if ($row['rekap'])
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('payrollMonthly.edit', $row['rekap']) }}">Ubah</a>
                                    @else
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('payrollMonthly.create', ['karyawan_id' => $row['karyawan']->id, 'periode' => $periode]) }}">Isi</a>
                                    @endif
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('slipGaji.show', ['karyawan' => $row['karyawan']->id, 'periode' => $periode]) }}">Slip</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Belum ada data pegawai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
