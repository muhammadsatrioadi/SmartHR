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

    @php $role = auth()->user()->role ?? 'karyawan'; @endphp

    <section id="read" class="m-5 mt-4 ">
        <div class="container bg-secondary rounded p-3" style="margin-bottom: 15rem">
            @if ($role !== 'admin_hr')
                <a href="{{ route('cuti.create') }}" class="btn btn-primary mt-2">{{ __("Buat Cuti Baru") }}</a>
            @endif
            <div class="row justify-content-center mt-4">
                <div class="col">
                    @php
                        // Admin HR hanya menangani approval pengajuan cuti manajer,
                        // pengajuan cuti karyawan disembunyikan.
                        $sections = $role === 'admin_hr'
                            ? ['Manajer' => $dataManajer]
                            : ['Karyawan' => $dataKaryawan, 'Manajer' => $dataManajer];
                    @endphp

                    @foreach ($sections as $label => $items)
                    <div class="cuti-section">
                        <h5 class="cuti-section-title">Data Cuti {{ $label }}</h5>

                        @if ($items->count() > 0)
                        <div class="table-responsive">
                            <table class="table" id="table-cuti">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __("No") }}</th>
                                        <th scope="col">{{ __("Nama Karyawan") }}</th>
                                        <th scope="col">{{ __("Jenis Cuti") }}</th>
                                        <th scope="col">{{ __("Tanggal Mulai") }}</th>
                                        <th scope="col">{{ __("Tanggal Berakhir") }}</th>
                                        <th scope="col">{{ __("Hak/Diambil/Sisa") }}</th>
                                        <th scope="col">{{ __("Status") }}</th>
                                        <th scope="col">{{ __("Opsi") }}</th>
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
                                        <td>{{ \App\Support\UiLabels::status($item->status) }}</td>
                                        <td class="opsi">
                                            @if ($item->status === 'menunggu_atasan')
                                                <form action="{{ route('cuti.approveSupervisor', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm confirm-action" data-message="Setujui pengajuan cuti ini (sebagai Atasan)?">{{ __("Approve Atasan") }}</button>
                                                </form>
                                            @endif
                                            @if ($item->status === 'menunggu_hr')
                                                <form action="{{ route('cuti.approveHr', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm confirm-action" data-message="Setujui pengajuan cuti ini (sebagai HR)?">{{ __("Approve HR") }}</button>
                                                </form>
                                            @endif
                                            @if (in_array($item->status, ['menunggu_atasan','menunggu_hr']))
                                                <form action="{{ route('cuti.reject', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="alasan" value="Ditolak melalui daftar cuti">
                                                    <button type="submit" class="btn btn-outline-light btn-sm confirm-action" data-message="Tolak pengajuan cuti ini?">{{ __("Tolak") }}</button>
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
                                <p>{{ __("Showing") }}</p>
                                <span>{{ $items->firstItem() }}</span>
                                <p>{{ __("to") }}</p>
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
