@extends('layouts.main')

@section('title', 'Rekap Gaji Bulanan')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <h6 class="mb-0">{{ __("Rekap Gaji Bulanan") }}</h6>
                <div class="d-flex align-items-end gap-2">
                    <form class="d-flex align-items-end gap-2" method="GET" action="{{ route('payrollMonthly.index') }}">
                        <div>
                            <label class="form-label mb-1">{{ __("Periode") }}</label>
                            <select name="periode" class="form-select" onchange="this.form.submit()">
                                <option value="">{{ __("Semua periode") }}</option>
                                @foreach ($periodes as $p)
                                    <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <a class="btn btn-primary" href="{{ route('payrollMonthly.create') }}">{{ __("Input Rekap") }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('payrollMonthly.batch', ['periode' => $periode]) }}">{{ __("Proses Massal") }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('slipGaji.index', ['periode' => $periode]) }}">{{ __("Slip Gaji") }}</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>{{ __("Pegawai") }}</th>
                            <th>{{ __("Periode") }}</th>
                            <th>{{ __("Kehadiran") }}</th>
                            <th class="text-end">{{ __("On call") }}</th>
                            <th class="text-end">{{ __("PPh 21") }}</th>
                            <th class="text-end">{{ __("Infaq") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekap as $r)
                            <tr>
                                <td>{{ $r->karyawan->name ?? '-' }}</td>
                                <td>{{ $r->labelPeriode() }}</td>
                                <td>
                                    {{ (int) $r->hari_pagi }} pagi, {{ (int) $r->hari_siang }} siang, {{ (int) $r->hari_malam }} malam
                                    @if ($r->is_koordinator) <span class="badge bg-info">{{ __("Koordinator") }}</span> @endif
                                    @if ((int) $r->hari_telat_pulang_awal > 0)
                                        <div class="small text-muted">Telat/pulang awal: {{ (int) $r->hari_telat_pulang_awal }} hari</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($r->nominal_on_call, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($r->nominal_pph21, 0, ',', '.') }}</td>
                                <td class="text-end">{{ $r->potongan_infaq === null ? 'Otomatis' : number_format($r->potongan_infaq, 0, ',', '.') }}</td>
                                <td><span class="badge bg-{{ $r->status === 'final' ? 'success' : 'secondary' }}">{{ $r->status }}</span></td>
                                <td class="text-nowrap">
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="{{ route('slipGaji.show', ['karyawan' => $r->karyawan_id, 'periode' => $r->periode]) }}">{{ __("Slip") }}</a>
                                    @if ($r->status !== 'final')
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('payrollMonthly.edit', $r) }}">{{ __("Ubah") }}</a>
                                        <form class="d-inline" method="POST" action="{{ route('payrollMonthly.finalize', $r) }}"
                                            onsubmit="return confirm('Finalkan slip ini? Data akan terkunci.')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success" type="submit">{{ __("Finalkan") }}</button>
                                        </form>
                                        <form class="d-inline" method="POST" action="{{ route('payrollMonthly.delete', $r) }}"
                                            onsubmit="return confirm('Hapus rekap ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">{{ __("Hapus") }}</button>
                                        </form>
                                    @else
                                        <form class="d-inline" method="POST" action="{{ route('payrollMonthly.batalkanFinal', $r) }}"
                                            onsubmit="return confirm('Batalkan finalisasi slip ini?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning" type="submit">{{ __("Buka Kunci") }}</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">{{ __("Belum ada rekap gaji bulanan.") }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $rekap->links() }}</div>
        </div>
    </div>
</div>
@endsection
