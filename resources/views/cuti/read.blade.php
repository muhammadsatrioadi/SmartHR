<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')
@section('links')

<style>
    #table-cuti,
    #table-cuti .opsi a,
    #table-cuti .opsi button {
        font-size: 0.8rem;
    }

    .cuti-section + .cuti-section {
        margin-top: 2rem;
    }

    .cuti-section-title {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #34395e;
    }

    .cuti-empty-state {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        color: #6c757d;
        text-align: center;
    }

</style>

@endsection

@section('navCuti')
active
@endsection

@section('content')

    <section id="read" class="m-5 mt-4 ">
        <div class="container bg-secondary rounded p-3" style="margin-bottom: 15rem">
            <a href="{{ route('cuti.create') }}" class="btn btn-primary mt-2">Buat Cuti Baru</a>
            <div class="row justify-content-center mt-4">
                <div class="col">
                    @php
                        $sections = [
                            'Karyawan' => $dataKaryawan,
                            'Manajer' => $dataManajer,
                        ];
                    @endphp

                    @foreach ($sections as $label => $items)
                    <div class="cuti-section">
                        <h5 class="cuti-section-title">Data Cuti {{ $label }}</h5>

                        @if ($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="table-cuti">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Karyawan</th>
                                        <th scope="col">Jenis Cuti</th>
                                        <th scope="col">Tanggal Mulai</th>
                                        <th scope="col">Tanggal Berakhir</th>
                                        <th scope="col">Hak/Diambil/Sisa</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr>
                                        <th scope="row">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</th>
                                        <td>{{ optional($item->karyawan)->name }}</td>
                                        <td>{{ $item->jenis_cuti }}</td>
                                        <td>{{ optional($item->tanggal_mulai)->format('Y-m-d') }}</td>
                                        <td>{{ optional($item->tanggal_berakhir)->format('Y-m-d') }}</td>
                                        <td>{{ $item->saldo_awal }} / {{ $item->hak_diambil }} / {{ $item->saldo_sisa }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                        <td class="opsi">
                                            <a href="{{ route('cuti.edit', ['id' => $item->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('cuti.delete', ['id' => $item->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-confirm" data-message="Yakin ingin menghapus pengajuan cuti {{ optional($item->karyawan)->name }}?">Delete</button>
                                            </form>
                                            @if ($item->status === 'menunggu_atasan')
                                                <form action="{{ route('cuti.approveSupervisor', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm confirm-action" data-message="Setujui pengajuan cuti ini (sebagai Atasan)?">Approve Atasan</button>
                                                </form>
                                            @endif
                                            @if ($item->status === 'menunggu_hr')
                                                <form action="{{ route('cuti.approveHr', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm confirm-action" data-message="Setujui pengajuan cuti ini (sebagai HR)?">Approve HR</button>
                                                </form>
                                            @endif
                                            @if (in_array($item->status, ['menunggu_atasan','menunggu_hr']))
                                                <form action="{{ route('cuti.reject', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="alasan" value="Ditolak melalui daftar cuti">
                                                    <button type="submit" class="btn btn-outline-light btn-sm confirm-action" data-message="Tolak pengajuan cuti ini?">Tolak</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between px-2 mt-3">
                            <div class="d-flex gap-2">
                                <p>Showing</p>
                                <span>{{ $items->firstItem() }}</span>
                                <p>to</p>
                                <span>{{ $items->lastItem() }}</span>
                            </div>
                            <div>
                                {{ $items->links() }}
                            </div>
                        </div>
                        @else
                        <div class="cuti-empty-state">
                            Belum ada data cuti {{ strtolower($label) }}.
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>


@endsection
