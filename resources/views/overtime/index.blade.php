@extends('layouts.main')

@section('title', 'Lembur')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-secondary rounded h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">{{ __("Daftar Lembur") }}</h6>
                <a class="btn btn-primary" href="{{ route('overtime.create') }}">{{ __("Order Lembur") }}</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
                $sections = [
                    'Karyawan' => $dataKaryawan,
                    'Manajer' => $dataManajer,
                ];
                $role = auth()->user()->role ?? 'karyawan';
            @endphp

            @foreach ($sections as $label => $overtimes)
                <div class="mb-5">
                    <h6 class="mb-3">{{ __('Data Lembur') }} {{ __($label) }}</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __("Pegawai") }}</th>
                                    <th>{{ __("Tanggal") }}</th>
                                    <th>{{ __("Jam") }}</th>
                                    <th>{{ __("Jumlah Jam") }}</th>
                                    <th>{{ __("Jenis Hari") }}</th>
                                    <th>{{ __("Pola Kerja") }}</th>
                                    <th>{{ __("Upah/Jam") }}</th>
                                    <th>{{ __("Nominal Lembur") }}</th>
                                    <th>{{ __("Keterangan") }}</th>
                                    <th>{{ __("Alasan Penolakan") }}</th>
                                    <th>{{ __("Persetujuan pegawai") }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th style="width: 220px;">{{ __("Aksi") }}</th>
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
                                                <div><small>{{ __("Hari kerja terpendek") }}</small></div>
                                            @endif
                                        </td>
                                        <td>{{ number_format($ot->upah_per_jam, 0, ',', '.') }}</td>
                                        <td>{{ number_format($ot->nominal_lembur, 0, ',', '.') }}</td>
                                        <td>{{ $ot->keterangan_pekerjaan }}</td>
                                        <td>{{ $ot->rejected_reason ?: '-' }}</td>
                                        <td>
                                            @if ($ot->pegawai_telah_menyetujui)
                                                <span class="text-success">{{ __("Tercatat") }}</span>
                                                @if ($ot->referensi_persetujuan_pegawai)
                                                    <div><small>{{ $ot->referensi_persetujuan_pegawai }}</small></div>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ \App\Support\UiLabels::status($ot->status) }}</td>
                                        <td>
                                            @if ($label === 'Karyawan')
                                                {{-- Karyawan: approval oleh atasan/manajer --}}
                                                @if ($ot->status === 'menunggu_approval' && in_array($role, ['atasan', 'manajer'], true))
                                                    <form action="{{ route('overtime.approveSupervisor', $ot->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" type="submit">{{ __("Approve Atasan") }}</button>
                                                    </form>
                                                @endif
                                            @else
                                                {{-- Manajer: approval oleh admin_hr --}}
                                                @if ($ot->status === 'menunggu_approval' && $role === 'admin_hr')
                                                    <form action="{{ route('overtime.approveSupervisor', $ot->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" type="submit">{{ __("Approve HR") }}</button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            {{-- Tombol Tolak --}}
                                            @if (!in_array($ot->status, ['ditolak','disetujui']))
                                                @if (
                                                    ($label === 'Karyawan' && in_array($role, ['atasan', 'manajer'], true)) ||
                                                    ($label === 'Manajer' && $role === 'admin_hr')
                                                )
                                                    <button class="btn btn-outline-light btn-sm js-reject-overtime"
                                                        type="button" data-action="{{ route('overtime.reject', $ot->id) }}">
                                                        {{ __('Tolak') }}
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">
                                            {{ __('Belum ada data lembur :role.', ['role' => strtolower(__($label))]) }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $overtimes->links() }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            const rejectButtons = document.querySelectorAll('.js-reject-overtime');
            const csrfToken = @json(csrf_token());

            rejectButtons.forEach((button) => {
                button.addEventListener('click', async function () {
                    const result = await Swal.fire({
                        icon: 'warning',
                        title: @json(__('Tolak Pengajuan Lembur')),
                        input: 'textarea',
                        inputLabel: @json(__('Alasan Penolakan')),
                        inputPlaceholder: @json(__('Tulis alasan penolakan di sini...')),
                        inputAttributes: {
                            maxlength: 255,
                            'aria-label': @json(__('Alasan Penolakan'))
                        },
                        showCancelButton: true,
                        confirmButtonText: @json(__('Kirim Penolakan')),
                        cancelButtonText: @json(__('Batal')),
                        inputValidator: (value) => {
                            if (!value || !value.trim()) {
                                return @json(__('Alasan penolakan wajib diisi.'));
                            }
                        }
                    });

                    if (!result.isConfirmed) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = button.dataset.action;

                    const token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = csrfToken;

                    const reason = document.createElement('input');
                    reason.type = 'hidden';
                    reason.name = 'alasan';
                    reason.value = result.value.trim();

                    form.append(token, reason);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        })();
    </script>
@endsection
