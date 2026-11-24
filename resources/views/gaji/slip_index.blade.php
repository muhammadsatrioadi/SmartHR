@extends('layouts.main')

@section('title', 'Slip Gaji Pegawai')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h1 class="h5 mb-0">{{ __("Slip Gaji Pegawai") }}</h1>
                <form class="d-flex align-items-end gap-2" method="GET" action="{{ route('slipGaji.index') }}">
                    <div>
                        <label class="form-label mb-1">{{ __("Periode") }}</label>
                        <input type="month" name="periode" value="{{ $periode }}" class="form-control">
                    </div>
                    <button class="btn btn-primary" type="submit">{{ __("Tampilkan") }}</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __("Pegawai") }}</th>
                            <th>{{ __("Jabatan") }}</th>
                            <th>{{ __("Rekap Bulanan") }}</th>
                            <th class="text-end">{{ __("Penghasilan (A)") }}</th>
                            <th class="text-end">{{ __("Potongan Resmi (B)") }}</th>
                            <th class="text-end">{{ __("Potongan Lain (D)") }}</th>
                            <th class="text-end">{{ __("Terima Bersih") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slips as $item)
                            <tr>
                                <td>
                                    {{ $item['karyawan']->name }}
                                    <div class="small text-muted">{{ $item['karyawan']->nik ?: '-' }}</div>
                                </td>
                                <td>{{ $item['karyawan']->jabatan->nama_jabatan ?? '-' }}</td>
                                <td>
                                    @if ($item['rekap'])
                                        <span class="badge bg-success">{{ __("Ada") }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __("Belum diisi") }}</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item['slip']['total_pendapatan'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item['slip']['total_potongan_resmi'], 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($item['slip']['total_potongan_lain'], 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">{{ number_format($item['slip']['terima_bersih'], 0, ',', '.') }}</td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('slipGaji.show', ['karyawan' => $item['karyawan']->id, 'periode' => $periode]) }}">
                                        {{ __("Lihat Slip") }}
                                    </a>
                                    <a class="btn btn-sm btn-outline-success"
                                        href="{{ route('slipGaji.cetak', ['karyawan' => $item['karyawan']->id, 'periode' => $periode]) }}"
                                        target="_blank">{{ __("Cetak") }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">{{ __("Belum ada data pegawai.") }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
