@extends('layouts.portal')
@section('title', 'Persetujuan')
@php $navActive = 'home'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Persetujuan</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('success'))<div class="alert alert-success small">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger small">{{ session('error') }}</div>@endif

    <ul class="nav nav-tabs nav-fill mb-3" id="approvalTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active small py-2" id="cuti-tab" data-bs-toggle="tab" data-bs-target="#cuti" type="button">Cuti ({{ $cutis->count() }})</button>
        </li>
        <li class="nav-item">
            <button class="nav-link small py-2" id="lembur-tab" data-bs-toggle="tab" data-bs-target="#lembur" type="button">Lembur ({{ $overtimes->count() }})</button>
        </li>
    </ul>

    <div class="tab-content" id="approvalTabsContent">
        <!-- Tab Cuti -->
        <div class="tab-pane fade show active" id="cuti" role="tabpanel">
            @forelse($cutis as $cuti)
                <div class="portal-approval-card {{ in_array($cuti->status, ['disetujui', 'ditolak']) ? 'opacity-75' : '' }}">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $cuti->karyawan->name ?? '-' }}</strong>
                        <span class="badge bg-{{ in_array($cuti->status, ['disetujui']) ? 'success' : (in_array($cuti->status, ['ditolak']) ? 'danger' : 'warning') }} small">
                            {{ strtoupper(str_replace('_', ' ', $cuti->status)) }}
                        </span>
                    </div>
                    <div class="small mt-1">
                        {{ $cuti->leaveType->nama ?? $cuti->jenis_cuti }}
                        <br><small class="text-muted">{{ $cuti->tanggal_mulai?->format('d/m/Y') }} s/d {{ $cuti->tanggal_berakhir?->format('d/m/Y') }} ({{ $cuti->hak_diambil }} hari)</small>
                    </div>
                    
                    <div class="mt-2 p-2 bg-light rounded small border">
                        <strong>Keterangan:</strong><br>
                        {{ $cuti->keterangan }}
                    </div>

                    @if($cuti->lampiran)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $cuti->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 py-1">
                                <i class="fas fa-file-alt"></i> Lihat Dokumen Pendukung
                            </a>
                        </div>
                    @endif
                    
                    @if($cuti->status === 'menunggu_atasan' && in_array(auth()->user()->role, ['atasan', 'manajer']))
                        <div class="mt-2 d-flex gap-2">
                            <form method="POST" action="{{ route('cuti.approveSupervisor', $cuti->id) }}">@csrf<button class="btn btn-sm btn-success">Setuju</button></form>
                            <button class="btn btn-sm btn-outline-danger btn-reject-cuti" data-id="{{ $cuti->id }}">Tolak</button>
                        </div>
                    @endif

                    @if($cuti->status === 'ditolak' && $cuti->rejected_reason)
                        <div class="mt-2 small text-danger">
                            <i class="fas fa-info-circle"></i> Alasan: {{ $cuti->rejected_reason }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted text-center py-4">Tidak ada data persetujuan cuti.</p>
            @endforelse
        </div>

        <!-- Tab Lembur -->
        <div class="tab-pane fade" id="lembur" role="tabpanel">
            @forelse($overtimes as $ot)
                <div class="portal-approval-card {{ in_array($ot->status, ['disetujui', 'ditolak']) ? 'opacity-75' : '' }}">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $ot->karyawan->name ?? '-' }}</strong>
                        <span class="badge bg-{{ in_array($ot->status, ['disetujui']) ? 'success' : (in_array($ot->status, ['ditolak']) ? 'danger' : 'warning') }} small">
                            {{ strtoupper(str_replace('_', ' ', $ot->status)) }}
                        </span>
                    </div>
                    <div class="small mt-1">
                        {{ $ot->tanggal?->format('d/m/Y') }} ({{ $ot->jumlah_jam }} Jam)
                        <br><small class="text-muted">{{ $ot->jam_mulai }} - {{ $ot->jam_selesai }}</small>
                        <br><span class="text-info small">Bayar: {{ $ot->pilihan_pembayaran === 'bulan_ini' ? 'Gaji Bulan Ini' : 'Gaji Bulan Depan' }}</span>
                    </div>

                    <div class="mt-2 p-2 bg-light rounded small border">
                        <strong>Pekerjaan:</strong><br>
                        {{ $ot->keterangan_pekerjaan }}
                    </div>

                    @if($ot->bukti_screenshot)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $ot->bukti_screenshot) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 py-1">
                                <i class="fas fa-image"></i> Lihat Bukti Screenshot
                            </a>
                        </div>
                    @endif
                    
                    @if($ot->status === 'menunggu_approval' && in_array(auth()->user()->role, ['atasan', 'manajer']))
                        <div class="mt-2 d-flex gap-2">
                            <form method="POST" action="{{ route('overtime.approveSupervisor', $ot->id) }}">@csrf<button class="btn btn-sm btn-success">Setuju</button></form>
                            <button class="btn btn-sm btn-outline-danger btn-reject-overtime" data-id="{{ $ot->id }}">Tolak</button>
                        </div>
                    @endif

                    @if($ot->status === 'ditolak' && $ot->rejected_reason)
                        <div class="mt-2 small text-danger">
                            <i class="fas fa-info-circle"></i> Alasan: {{ $ot->rejected_reason }}
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted text-center py-4">Tidak ada data persetujuan lembur.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleReject(id, url) {
    Swal.fire({
        title: 'Tolak Pengajuan',
        text: 'Masukkan alasan penolakan:',
        input: 'textarea',
        inputPlaceholder: 'Tulis alasan di sini...',
        showCancelButton: true,
        confirmButtonText: 'Tolak Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan wajib diisi!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = window.PORTAL_CSRF;
            
            const alasan = document.createElement('input');
            alasan.type = 'hidden';
            alasan.name = 'alasan';
            alasan.value = result.value;
            
            form.appendChild(csrf);
            form.appendChild(alasan);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

document.querySelectorAll('.btn-reject-cuti').forEach(btn => {
    btn.addEventListener('click', function() {
        handleReject(this.dataset.id, `/cuti/${this.dataset.id}/reject`);
    });
});

document.querySelectorAll('.btn-reject-overtime').forEach(btn => {
    btn.addEventListener('click', function() {
        handleReject(this.dataset.id, `/overtime/${this.dataset.id}/reject`);
    });
});
</script>
@endpush
