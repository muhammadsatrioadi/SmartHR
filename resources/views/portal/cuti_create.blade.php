@extends('layouts.portal')
@section('title', 'Ajukan Cuti')
@php $navActive = 'home'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.cuti') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Ajukan Cuti</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('error'))<div class="alert alert-danger small">{{ session('error') }}</div>@endif
    
    <div class="portal-card-dark mb-3">
        <h6 class="mb-2 small opacity-75">SALDO CUTI TERSEDIA</h6>
        <div class="row g-2">
            @foreach($saldoCuti as $saldo)
                <div class="col-6">
                    <div class="p-2 rounded bg-white bg-opacity-10 small">
                        <div class="opacity-75">{{ $saldo->leaveType->nama }}</div>
                        <strong>{{ number_format($saldo->sisa, 1) }} Hari</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('portal.cuti.simpan') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Jenis Cuti</label>
            <select name="leave_type_id" class="form-select form-select-sm" required>
                <option value="">Pilih Jenis Cuti</option>
                @foreach($leaveTypes as $type)
                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label small">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ old('tanggal_mulai') }}" required>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label small">Tanggal Selesai</label>
                <input type="date" name="tanggal_berakhir" class="form-control form-control-sm" value="{{ old('tanggal_berakhir') }}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label small">Keterangan / Alasan</label>
            <textarea name="keterangan" class="form-control form-control-sm" rows="3" required>{{ old('keterangan') }}</textarea>
        </div>
        <button type="submit" class="portal-btn-checkin w-100">
            <i class="fas fa-paper-plane"></i> KIRIM PENGAJUAN
        </button>
    </form>
</div>
@endsection
