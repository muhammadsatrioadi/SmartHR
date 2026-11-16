@php
    /** @var array $slip hasil PanduanGajiCalculator::slip() */
    $rp = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
@endphp

<div class="slip-gaji">
    <div class="border rounded p-3 mb-3 bg-light">
        <div class="d-flex justify-content-between flex-wrap">
            <div>
                <div class="fw-bold">{{ $karyawan->name }}</div>
                <div class="small text-muted">
                    NIK/NIP: {{ $karyawan->nik ?: '-' }}
                    @if ($karyawan->jabatan) &middot; {{ $karyawan->jabatan->nama_jabatan }} @endif
                </div>
                <div class="small text-muted">
                    Unit: {{ $karyawan->department->nama ?? '-' }}
                    &middot; Status: {{ $karyawan->employeeStatus->nama ?? '-' }}
                </div>
                <div class="small text-muted">
                    Status keluarga:
                    {{ $slip['pasangan_ditanggung'] ? 'Menikah (pasangan ditanggung)' : 'Lajang / tanpa tanggungan' }}
                    &middot; {{ (int) $slip['jumlah_anak_ditanggung'] }} anak ditanggung
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold">SLIP GAJI</div>
                <div class="small">Periode: {{ $slip['label_periode'] }}</div>
                <div class="small text-muted">Dasar perhitungan: {{ $rp($slip['dasar_perhitungan']) }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-primary">
                    <tr><th colspan="2">PENDAPATAN</th></tr>
                </thead>
                <tbody>
                    @foreach ($slip['pendapatan'] as $baris)
                        <tr>
                            <td>
                                {{ $baris['label'] }}
                                @if ($baris['keterangan'])
                                    <div class="small text-muted">{{ $baris['keterangan'] }}</div>
                                @endif
                            </td>
                            <td class="text-end">{{ $rp($baris['nilai']) }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold">
                        <td>Total pendapatan (A)</td>
                        <td class="text-end">{{ $rp($slip['total_pendapatan']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-sm table-bordered mb-3">
                <thead class="table-warning">
                    <tr><th colspan="2">POTONGAN RESMI (B)</th></tr>
                </thead>
                <tbody>
                    @forelse ($slip['potongan_resmi'] as $baris)
                        <tr>
                            <td>
                                {{ $baris['label'] }}
                                @if ($baris['keterangan'])
                                    <div class="small text-muted">{{ $baris['keterangan'] }}</div>
                                @endif
                            </td>
                            <td class="text-end">{{ $rp($baris['nilai']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">Tidak ada potongan resmi.</td></tr>
                    @endforelse
                    <tr class="fw-bold">
                        <td>Jumlah potongan resmi (B)</td>
                        <td class="text-end">{{ $rp($slip['total_potongan_resmi']) }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-sm table-bordered mb-0">
                <thead class="table-warning">
                    <tr><th colspan="2">POTONGAN LAIN (D)</th></tr>
                </thead>
                <tbody>
                    @forelse ($slip['potongan_lain'] as $baris)
                        <tr>
                            <td>
                                {{ $baris['label'] }}
                                @if ($baris['keterangan'])
                                    <div class="small text-muted">{{ $baris['keterangan'] }}</div>
                                @endif
                            </td>
                            <td class="text-end">{{ $rp($baris['nilai']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">Tidak ada potongan lain.</td></tr>
                    @endforelse
                    <tr class="fw-bold">
                        <td>Jumlah potongan lain (D)</td>
                        <td class="text-end">{{ $rp($slip['total_potongan_lain']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <div class="d-flex justify-content-between border rounded p-2">
                <span>Penghasilan bruto (A)</span>
                <strong>{{ $rp($slip['total_pendapatan']) }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between border rounded p-2 bg-light">
                <span>Gaji bersih (A &minus; B)</span>
                <strong>{{ $rp($slip['gaji_bersih']) }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between border rounded p-2">
                <span>Potongan resmi (B)</span>
                <strong>{{ $rp($slip['total_potongan_resmi']) }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between border rounded p-2">
                <span>Potongan lain (D)</span>
                <strong>{{ $rp($slip['total_potongan_lain']) }}</strong>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex justify-content-between border rounded p-3 bg-success-subtle">
                <span class="fw-bold">TERIMA BERSIH (A &minus; B &minus; D)</span>
                <strong class="fs-5">{{ $rp($slip['terima_bersih']) }}</strong>
            </div>
        </div>
    </div>

    <p class="small text-muted mt-3 mb-0">
        Biaya jabatan 5% ({{ $rp($slip['biaya_jabatan']) }}) hanya dipakai sebagai pengurang dalam menghitung pajak,
        bukan potongan uang yang diambil dari pegawai. Tunjangan BPJS dan dana pensiun muncul sebagai pendapatan lalu
        diimbangi sebagai potongan, sehingga tidak menambah uang yang diterima.
    </p>
</div>
