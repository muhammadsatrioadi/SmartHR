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


        <table class="table">
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
                @foreach ($data as $items )

                <tr>
                <th scope="row">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</th>
                <td>{{ optional($items->karyawan)->name }}</td>
                <td>{{ $items->jenis_cuti }}</td>
                <td>{{ $items->tanggal_mulai }}</td>
                <td>{{ $items->tanggal_berakhir }}</td>
                <td>{{ $items->saldo_awal }} / {{ $items->hak_diambil }} / {{ $items->saldo_sisa }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $items->status)) }}</td>
                <td class="opsi">
                    <a href="{{ route('cuti.edit', ['id' => $items->id]) }}" class="btn btn-warning btn-sm"> Edit </a>
                    <form action="{{ route('cuti.delete', ['id' => $items->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    @if ($items->status === 'menunggu_atasan')
                        <form action="{{ route('cuti.approveSupervisor', $items->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve Atasan</button>
                        </form>
                    @endif
                    @if ($items->status === 'menunggu_hr')
                        <form action="{{ route('cuti.approveHr', $items->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve HR</button>
                        </form>
                    @endif
                    @if (in_array($items->status, ['menunggu_atasan','menunggu_hr']))
                        <form action="{{ route('cuti.reject', $items->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="alasan" value="Ditolak melalui daftar cuti">
                            <button type="submit" class="btn btn-outline-light btn-sm">Tolak</button>
                        </form>
                    @endif
                </td>
                </tr>
                @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="d-flex justify-content-between px-5">
            <div class="d-flex gap-2">
                <p>Showing</p>
                {{ $data->firstItem() }}
                <p>to</p>
                {{ $data->lastItem() }}
            </div>
            <div>
                {{ $data->links() }}
            </div>
            </div>

        </div>

    </section>


@endsection
