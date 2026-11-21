@extends('layouts.main')

@section('title', 'Setup Gaji')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Setup Gaji</h6>
            </div>

            <p class="text-muted fst-italic mb-3">
                Atur komponen gaji awal untuk tiap jabatan. Nilai di sini otomatis dipakai
                sebagai <strong>data gaji dasar</strong> saat Admin HR membuat karyawan baru.
            </p>

            @if ($jabatans->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada jabatan. Buat jabatan terlebih dahulu di menu <strong>Setup Master &gt; Jabatan</strong>.
                </div>
            @else
                <form method="POST" action="{{ route('gaji.setup.update') }}" class="js-validate" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="tabel-setup-gaji">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle">Jabatan</th>
                                    <th colspan="7" class="text-center">Komponen Tetap</th>
                                    <th rowspan="2" class="align-middle">Hari Kerja<br>/ Minggu</th>
                                    <th rowspan="2" class="align-middle">Total<br>Komponen</th>
                                </tr>
                                <tr>
                                    <th style="min-width: 140px;">Gaji Pokok <span class="text-danger">*</span></th>
                                    <th style="min-width: 130px;">T. Jabatan</th>
                                    <th style="min-width: 130px;">T. Fungsional</th>
                                    <th style="min-width: 130px;">T. Profesi</th>
                                    <th style="min-width: 130px;">T. Makan</th>
                                    <th style="min-width: 130px;">T. Pulsa</th>
                                    <th style="min-width: 130px;">TKP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jabatans as $jabatan)
                                    <tr data-baris-setup>
                                        <td class="align-middle">{{ $jabatan->nama_jabatan }}</td>
                                        <td>
                                            <input type="number" min="0" name="gaji_pokok[{{ $jabatan->id }}]"
                                                value="{{ old('gaji_pokok.' . $jabatan->id, $jabatan->gaji_pokok ?? 0) }}"
                                                class="form-control js-komponen" required>
                                        </td>
                                        @foreach (\App\Http\Controllers\SalarySetupController::KOMPONEN as $komponen)
                                            <td>
                                                <input type="number" min="0" name="{{ $komponen }}[{{ $jabatan->id }}]"
                                                    value="{{ old($komponen . '.' . $jabatan->id, $jabatan->{$komponen} ?? 0) }}"
                                                    class="form-control js-komponen">
                                            </td>
                                        @endforeach
                                        <td>
                                            <select name="hari_kerja_per_minggu[{{ $jabatan->id }}]" class="form-select">
                                                @php $hariKerja = old('hari_kerja_per_minggu.' . $jabatan->id, $jabatan->hari_kerja_per_minggu ?: 5); @endphp
                                                <option value="5" {{ (int) $hariKerja === 5 ? 'selected' : '' }}>5 hari</option>
                                                <option value="6" {{ (int) $hariKerja === 6 ? 'selected' : '' }}>6 hari</option>
                                            </select>
                                        </td>
                                        <td class="text-end"><span class="js-total">0</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            var formatter = new Intl.NumberFormat('id-ID');

            function hitungTotal(baris) {
                var total = 0;
                baris.querySelectorAll('.js-komponen').forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });
                var target = baris.querySelector('.js-total');
                if (target) target.textContent = formatter.format(total);
            }

            document.querySelectorAll('[data-baris-setup]').forEach(function (baris) {
                hitungTotal(baris);
                baris.querySelectorAll('.js-komponen').forEach(function (input) {
                    input.addEventListener('input', function () {
                        hitungTotal(baris);
                    });
                });
            });
        })();
    </script>
@endsection
