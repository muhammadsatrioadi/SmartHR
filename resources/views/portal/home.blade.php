@extends('layouts.portal')

@section('title', 'Beranda')

@php $navActive = 'home'; @endphp

@section('content')
<header class="portal-header">
    <div class="portal-header-top">
        <div>
            <div class="portal-greeting">{{ $greeting }}</div>
            <div class="portal-name">{{ strtoupper($karyawan->nama_lengkap ?? $karyawan->name) }}{{ $karyawan->gelar ? ', ' . $karyawan->gelar : '' }}</div>
            <div class="portal-unit">{{ $karyawan->workUnit->nama ?? $karyawan->department->nama ?? 'Unit Kerja' }}</div>
        </div>
        <div class="portal-avatar">{{ strtoupper(substr($karyawan->name, 0, 1)) }}</div>
    </div>
</header>

<div class="portal-content">
    <div class="portal-card-dark">
        <h6>ABSENSI HARI INI — {{ $today->translatedFormat('d M Y') }}</h6>
        @if($masuk)
            <div class="portal-time-big">{{ \Carbon\Carbon::parse($masuk->time)->format('H:i') }}</div>
            <p class="mb-0 mt-2 small opacity-75">
                Masuk tercatat
                @if($pulang) · Pulang {{ \Carbon\Carbon::parse($pulang->time)->format('H:i') }} @endif
            </p>
        @else
            <div class="portal-time-big">--:--</div>
            <p class="mb-0 mt-2 small opacity-75">Belum ada data absensi</p>
        @endif
        <a href="{{ route('portal.presensi') }}" class="portal-btn-checkin text-decoration-none">
            <i class="fas fa-fingerprint"></i>
            {{ $masuk && !$pulang ? 'Clock Out' : 'Clock In' }}
        </a>
    </div>

    <p class="portal-section-title">MENU CEPAT</p>
    <div class="portal-quick-grid">
        <a href="{{ route('portal.absensi') }}" class="portal-quick-item"><i class="fas fa-calendar-alt text-primary"></i>Absensi</a>
        @if(in_array($user->role, ['atasan', 'manajer', 'admin_hr']))
            <a href="{{ route('portal.absensi.subordinate') }}" class="portal-quick-item"><i class="fas fa-users text-primary"></i>Absen Tim</a>
            <a href="{{ route('portal.cuti.approvals') }}" class="portal-quick-item"><i class="fas fa-check-double text-success"></i>Approval</a>
        @endif
        <a href="{{ route('portal.cuti') }}" class="portal-quick-item"><i class="fas fa-calendar-check text-success"></i>Cuti</a>
        <a href="{{ route('portal.gaji') }}" class="portal-quick-item"><i class="fas fa-wallet text-warning"></i>Slip Gaji</a>
        <a href="{{ route('portal.cuti.ajukan') }}" class="portal-quick-item"><i class="fas fa-plus-circle text-info"></i>Ajukan Cuti</a>
        <a href="{{ route('portal.reimburse.index') }}" class="portal-quick-item"><i class="fas fa-receipt" style="color:#8b5cf6"></i>Reimburse</a>
        <a href="{{ route('portal.profil') }}" class="portal-quick-item"><i class="fas fa-id-card text-primary"></i>Profil</a>
        <a href="{{ route('portal.profil.password') }}" class="portal-quick-item"><i class="fas fa-key text-danger"></i>Password</a>
        <a href="{{ route('actionlogout') }}" class="portal-quick-item" id="btn-logout"><i class="fas fa-sign-out-alt text-secondary"></i>Logout</a>
    </div>

    @if($managerStats)
        <p class="portal-section-title">STATISTIK TIM</p>
        <div class="portal-stat-grid mb-3">
            <div class="portal-stat-card" style="background: #e0f2fe; border: 1px solid #bae6fd;">
                <i class="fas fa-users text-primary fa-lg"></i>
                <div><div class="value">{{ $managerStats['total_tim'] }}</div><div class="label">Total Tim</div></div>
            </div>
            <div class="portal-stat-card" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                <i class="fas fa-user-check text-success fa-lg"></i>
                <div><div class="value">{{ $managerStats['hadir_hari_ini'] }}</div><div class="label">Hadir Hari Ini</div></div>
            </div>
            <div class="portal-stat-card" style="background: #fffbeb; border: 1px solid #fef3c7;">
                <i class="fas fa-plane-departure text-warning fa-lg"></i>
                <div><div class="value">{{ $managerStats['cuti_pending'] }}</div><div class="label">Cuti Pending</div></div>
            </div>
            <div class="portal-stat-card" style="background: #f5f3ff; border: 1px solid #ddd6fe;">
                <i class="fas fa-stopwatch text-indigo fa-lg" style="color:#8b5cf6"></i>
                <div><div class="value">{{ $managerStats['lembur_pending'] }}</div><div class="label">Lembur Pending</div></div>
            </div>
        </div>
    @endif

    @if($pendingApprovals->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center">
            <p class="portal-section-title">PERSETUJUAN ATASAN</p>
            <a href="{{ route('portal.cuti.approvals') }}" class="small text-decoration-none">Lihat Semua</a>
        </div>
        @foreach($pendingApprovals as $cuti)
            <div class="portal-approval-card">
                <strong>{{ $cuti->karyawan->name ?? '-' }}</strong> — {{ $cuti->leaveType->nama ?? $cuti->jenis_cuti }}
                <br><small>{{ $cuti->tanggal_mulai?->format('d/m/Y') }} s/d {{ $cuti->tanggal_berakhir?->format('d/m/Y') }}</small>
                <div class="mt-2 d-flex gap-2">
                    <form method="POST" action="{{ route('cuti.approveSupervisor', $cuti->id) }}">@csrf<button class="btn btn-sm btn-success">Setuju</button></form>
                    <button class="btn btn-sm btn-outline-danger btn-reject-cuti" data-id="{{ $cuti->id }}">Tolak</button>
                </div>
            </div>
        @endforeach
    @endif

    <p class="portal-section-title">RINGKASAN BULAN INI</p>
    <div class="portal-stat-grid">
        <div class="portal-stat-card">
            <i class="fas fa-check-circle text-primary fa-lg"></i>
            <div><div class="value">{{ $ringkasan['hadir'] }}</div><div class="label">Hari Hadir</div></div>
        </div>
        <div class="portal-stat-card">
            <i class="fas fa-clock text-warning fa-lg"></i>
            <div><div class="value">{{ $ringkasan['terlambat'] }}</div><div class="label">Terlambat</div></div>
        </div>
        <div class="portal-stat-card">
            <i class="fas fa-paper-plane text-success fa-lg"></i>
            <div><div class="value">{{ $ringkasan['cuti_pending'] }}</div><div class="label">Cuti Pending</div></div>
        </div>
        <div class="portal-stat-card">
            <i class="fas fa-book fa-lg" style="color:#8b5cf6"></i>
            <div><div class="value">{{ $ringkasan['reimburse'] }}</div><div class="label">Reimburse</div></div>
        </div>
    </div>

    <p class="portal-section-title">SALDO CUTI {{ date('Y') }}</p>
    <div class="portal-saldo-grid">
        @forelse($saldoCuti as $saldo)
            <div class="portal-saldo-card">
                <div class="type">{{ $saldo->leaveType->nama ?? 'Cuti' }}</div>
                <div class="balance">{{ number_format($saldo->sisa, 1) }} dari {{ number_format($saldo->kuota, 0) }} hari</div>
            </div>
        @empty
            <div class="portal-saldo-card"><div class="type">Belum ada kuota</div><div class="balance">Hubungi HR</div></div>
        @endforelse
    </div>

    <p class="portal-section-title mt-3">DOKUMEN WAJIB</p>
    <a href="{{ route('portal.consent') }}" class="btn btn-outline-primary btn-sm w-100 mb-3">
        <i class="fas fa-file-signature"></i> Surat Perjanjian & Task List
    </a>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btn-logout').addEventListener('click', function(e) {
    e.preventDefault();
    const url = this.href;
    Swal.fire({
        title: 'Keluar?',
        text: "Anda akan keluar dari aplikasi portal.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    })
});

document.querySelectorAll('.btn-reject-cuti').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Tolak Cuti',
            text: 'Masukkan alasan penolakan:',
            input: 'textarea',
            inputPlaceholder: 'Tulis alasan di sini...',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
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
                form.action = `/cuti/${id}/reject`;
                
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
    });
});
</script>
@endpush
