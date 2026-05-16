@extends('layouts.portal')

@section('title', 'Tukar Nota')

@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.reimburse.index') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Pengajuan Reimburse Nota</strong>
</div>

<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    <form action="{{ route('portal.reimburse.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Tanggal Nota</label>
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Nominal (Rp)</label>
            <input type="number" name="nominal" class="form-control form-control-sm" placeholder="Contoh: 50000" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Keterangan / Keperluan</label>
            <textarea name="keterangan" class="form-control form-control-sm" rows="3" placeholder="Jelaskan keperluan pengeluaran ini..." required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label small">Foto Nota / Bukti</label>
            <input type="file" name="lampiran" class="form-control form-control-sm" accept="image/*">
            <div class="form-text small">Opsional. Maksimal 2MB.</div>
        </div>
        <button type="submit" class="portal-btn-checkin w-100">
            <i class="fas fa-paper-plane"></i> KIRIM PENGAJUAN
        </button>
    </form>
</div>
@endsection
