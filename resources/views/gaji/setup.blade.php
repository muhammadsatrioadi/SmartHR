@extends('layouts.main')

@section('title', 'Setup Gaji')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Setup Gaji</h6>
            </div>

            <p class="text-muted fst-italic mb-3">Atur komponen gaji pokok, tunjangan, dan potongan untuk tiap jabatan.</p>

            @if ($jabatans->isEmpty())
                <div class="alert alert-warning mb-0">
                    Belum ada jabatan. Buat jabatan terlebih dahulu di menu <strong>Kelola Master &gt; Jabatan</strong>.
                </div>
            @else
                <form method="POST" action="{{ route('gaji.setup.update') }}" class="js-validate" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Jabatan</th>
                                    <th style="width: 200px;">Gaji Pokok <span class="text-danger">*</span></th>
                                    <th style="width: 200px;">Tunjangan <span class="text-danger">*</span></th>
                                    <th style="width: 200px;">Potongan <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jabatans as $jabatan)
                                    <tr>
                                        <td class="align-middle">{{ $jabatan->nama_jabatan }}</td>
                                        <td>
                                            <input type="number" min="0" name="gaji_pokok[{{ $jabatan->id }}]"
                                                value="{{ old('gaji_pokok.' . $jabatan->id, $jabatan->gaji_pokok ?? 0) }}"
                                                class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="tunjangan[{{ $jabatan->id }}]"
                                                value="{{ old('tunjangan.' . $jabatan->id, $jabatan->tunjangan ?? 0) }}"
                                                class="form-control" required>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="potongan[{{ $jabatan->id }}]"
                                                value="{{ old('potongan.' . $jabatan->id, $jabatan->potongan ?? 0) }}"
                                                class="form-control" required>
                                        </td>
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
