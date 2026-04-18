@extends('layouts.main')

@section('title', 'Lembur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Daftar Lembur</h6>
                <a class="btn btn-primary" href="{{ route('overtime.create') }}">Order Lembur</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Pegawai</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Jumlah Jam</th>
                            <th>Jenis Hari</th>
                            <th>Pola Kerja</th>
                            <th>Upah/Jam</th>
                            <th>Nominal Lembur</th>
                            <th>Keterangan</th>
                            <th>Persetujuan pegawai</th>
                            <th>Status</th>
                            <th style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($overtimes as $ot)
                            <tr>
                                <td>{{ $ot->karyawan->name ?? '-' }}</td>
                                <td>{{ $ot->tanggal }}</td>
                                <td>{{ $ot->jam_mulai }} - {{ $ot->jam_selesai }}</td>
                                <td>{{ $ot->jumlah_jam }}</td>
                                <td>{{ str_replace('_', ' ', $ot->jenis_hari) }}</td>
                                <td>
                                    {{ $ot->hari_kerja_per_minggu }} hari
                                    @if ($ot->is_hari_kerja_terpendek)
                                        <div><small>hari kerja terpendek</small></div>
                                    @endif
                                </td>
                                <td>{{ number_format($ot->upah_per_jam, 0, ',', '.') }}</td>
                                <td>{{ number_format($ot->nominal_lembur, 0, ',', '.') }}</td>
                                <td>{{ $ot->keterangan_pekerjaan }}</td>
                                <td>
                                    @if ($ot->pegawai_telah_menyetujui)
                                        <span class="text-success">Tercatat</span>
                                        @if ($ot->referensi_persetujuan_pegawai)
                                            <div><small>{{ $ot->referensi_persetujuan_pegawai }}</small></div>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $ot->status)) }}</td>
                                <td>
                                    @php $role = auth()->user()->role ?? 'karyawan'; @endphp
                                    @if ($ot->status === 'menunggu_approval' && $role === 'atasan')
                                        <form action="{{ route('overtime.approveSupervisor', $ot->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm" type="submit">Approve Atasan</button>
                                        </form>
                                    @endif
                                    @if (in_array($ot->status, ['menunggu_approval', 'disetujui_atasan']) && $role === 'admin_hr')
                                        <form action="{{ route('overtime.approveHr', $ot->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-primary btn-sm" type="submit">Approve HR</button>
                                        </form>
                                    @endif
                                    @if (!in_array($ot->status, ['ditolak','disetujui']))
                                        <form action="{{ route('overtime.reject', $ot->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="alasan" value="Ditolak melalui daftar lembur">
                                            <button class="btn btn-outline-light btn-sm" type="submit">Tolak</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Belum ada data lembur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $overtimes->links() }}
            </div>
        </div>
    </div>
@endsection

