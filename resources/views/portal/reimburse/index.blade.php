@extends('layouts.portal')

@section('title', 'Reimburse & Lembur')

@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Reimburse & Lembur</strong>
</div>

<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('success'))<div class="alert alert-success small">{{ session('success') }}</div>@endif

    <div class="row g-2 mb-3">
        <div class="col-6">
            <a href="{{ route('portal.reimburse.overtime.create') }}" class="btn btn-primary btn-sm w-100 py-3">
                <i class="fas fa-clock d-block mb-1 fa-lg"></i> Ajukan Lembur
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('portal.reimburse.create') }}" class="btn btn-info btn-sm w-100 py-3 text-white">
                <i class="fas fa-file-invoice-dollar d-block mb-1 fa-lg"></i> Tukar Nota
            </a>
        </div>
    </div>

    <p class="portal-section-title">RIWAYAT PENUKARAN NOTA</p>
    @forelse($reimbursements as $reim)
        <div class="portal-saldo-card mb-2">
            <div class="d-flex justify-content-between">
                <strong>Rp {{ number_format($reim->nominal, 0, ',', '.') }}</strong>
                <span class="badge bg-{{ $reim->status === 'disetujui' ? 'success' : ($reim->status === 'ditolak' ? 'danger' : 'warning') }} small">
                    {{ strtoupper(str_replace('_', ' ', $reim->status)) }}
                </span>
            </div>
            <div class="small mt-1">
                {{ $reim->tanggal->format('d M Y') }} · {{ $reim->keterangan }}
            </div>
            @if($reim->lampiran)
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $reim->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0">Lihat Nota</a>
                </div>
            @endif
            @if($reim->status === 'ditolak' && $reim->rejected_reason)
                <div class="mt-1 small text-danger italic">Alasan: {{ $reim->rejected_reason }}</div>
            @endif
        </div>
    @empty
        <p class="text-center text-muted small">Belum ada riwayat nota.</p>
    @endforelse
    <div class="mb-4">{{ $reimbursements->links() }}</div>

    <p class="portal-section-title">RIWAYAT LEMBUR</p>
    @forelse($overtimes as $ot)
        <div class="portal-saldo-card mb-2">
            <div class="d-flex justify-content-between">
                <strong>{{ $ot->tanggal->format('d M Y') }}</strong>
                <span class="badge bg-{{ $ot->status === 'disetujui' ? 'success' : ($ot->status === 'ditolak' ? 'danger' : 'warning') }} small">
                    {{ strtoupper(str_replace('_', ' ', $ot->status)) }}
                </span>
            </div>
            <div class="small mt-1">
                {{ $ot->jam_mulai }} - {{ $ot->jam_selesai }} ({{ $ot->jumlah_jam }} Jam)
                <br><span class="text-muted">{{ $ot->keterangan_pekerjaan }}</span>
                <br><span class="text-info small">Pembayaran: {{ $ot->pilihan_pembayaran === 'bulan_ini' ? 'Gaji Bulan Ini' : 'Gaji Bulan Depan' }}</span>
                @if($ot->bukti_screenshot)
                    <br><a href="{{ asset('storage/' . $ot->bukti_screenshot) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-0 mt-1">Lihat Bukti</a>
                @endif
                @if($ot->nominal_lembur > 0)
                    <br><strong class="text-success">Estimasi: Rp {{ number_format($ot->nominal_lembur, 0, ',', '.') }}</strong>
                @endif
            </div>
            @if($ot->status === 'ditolak' && $ot->rejected_reason)
                <div class="mt-1 small text-danger italic">Alasan: {{ $ot->rejected_reason }}</div>
            @endif
        </div>
    @empty
        <p class="text-center text-muted small">Belum ada riwayat lembur.</p>
    @endforelse
    <div>{{ $overtimes->links() }}</div>
</div>
@endsection
