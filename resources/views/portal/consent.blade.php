@extends('layouts.portal')
@section('title', 'Dokumen Wajib')
@php $navActive = 'home'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Dokumen Wajib</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('success'))
        <div class="alert alert-success small">{{ session('success') }}</div>
    @endif

    <div class="portal-saldo-card mb-3">
        <h6><i class="fas fa-file-contract"></i> Surat Perjanjian Absensi</h6>
        <p class="small text-muted mb-2">
            Saya menyetujui kebijakan absensi berbasis lokasi GPS, verifikasi biometrik perangkat,
            serta pembatasan satu akun email untuk satu perangkat seluler.
        </p>
        @php $p = $consents->get('perjanjian_absensi'); @endphp
        @if($p?->disetujui)
            <span class="badge bg-success">Disetujui {{ $p->disetujui_pada?->format('d/m/Y H:i') }}</span>
        @else
            <form method="POST" action="{{ route('portal.consent.store') }}">
                @csrf
                <input type="hidden" name="jenis" value="perjanjian_absensi">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="disetujui" value="1" id="perjanjian" required>
                    <label class="form-check-label small" for="perjanjian">Saya setuju</label>
                </div>
                <button class="btn btn-primary btn-sm">Simpan Persetujuan</button>
            </form>
        @endif
    </div>

    <div class="portal-saldo-card mb-3">
        <h6><i class="fas fa-project-diagram"></i> Task List — Flowchart Absensi</h6>
        <p class="small text-muted">Alur proses absensi yang wajib dipahami:</p>
        <pre class="small bg-light p-2 rounded" style="white-space:pre-wrap;font-size:0.7rem;">[START]
  |
  v
[Buka Portal Presensi]
  |
  v
[Perangkat terdaftar?]---Tidak-->[Tolak: ganti perangkat]
  | Ya
  v
[Verifikasi Salah Satu: GPS / Biometrik]
  |
  +--[GPS dalam radius?]--Ya-->[Verifikasi Berhasil]
  |
  +--[Biometrik?]--Berhasil-->[Verifikasi Berhasil]
  |
  +--[Keduanya Gagal]---------->[Tolak: verifikasi gagal]
  |
  v
[Verifikasi Berhasil]
  |
  v
[Kirim ke Server]---Offline-->[Antrian lokal]
  | Online
  v
[Catat Absensi Masuk/Pulang]
  |
  v
[END]</pre>
        @php $t = $consents->get('task_list_flowchart'); @endphp
        @if($t?->disetujui)
            <span class="badge bg-success">Disetujui {{ $t->disetujui_pada?->format('d/m/Y H:i') }}</span>
        @else
            <form method="POST" action="{{ route('portal.consent.store') }}">
                @csrf
                <input type="hidden" name="jenis" value="task_list_flowchart">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="disetujui" value="1" id="tasklist" required>
                    <label class="form-check-label small" for="tasklist">Saya memahami alur di atas</label>
                </div>
                <button class="btn btn-primary btn-sm">Simpan Persetujuan</button>
            </form>
        @endif
    </div>
</div>
@endsection
