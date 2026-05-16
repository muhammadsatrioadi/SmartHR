@extends('layouts.portal')
@section('title', 'Profil')
@php $navActive = 'profil'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.home') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Profil</strong>
</div>
    <div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('success'))<div class="alert alert-success small">{{ session('success') }}</div>@endif
    <div class="portal-saldo-card text-center mb-3">
        <div class="portal-avatar mx-auto mb-2" style="width:64px;height:64px;font-size:1.5rem;">{{ strtoupper(substr($karyawan->name,0,1)) }}</div>
        <h5 class="mb-0">{{ $karyawan->nama_lengkap ?? $karyawan->name }}</h5>
        <small class="text-muted">{{ $karyawan->nik }}</small>
    </div>
    <div class="portal-saldo-card mb-2"><small class="text-muted">Email</small><br>{{ $karyawan->email }}</div>
    <div class="portal-saldo-card mb-2"><small class="text-muted">Jabatan</small><br>{{ $karyawan->jabatan->nama_jabatan ?? '-' }}</div>
    <div class="portal-saldo-card mb-2"><small class="text-muted">Departemen</small><br>{{ $karyawan->department->nama ?? '-' }}</div>
    <div class="portal-saldo-card mb-2"><small class="text-muted">Unit Kerja</small><br>{{ $karyawan->workUnit->nama ?? '-' }}</div>
    <a href="{{ route('portal.profil.password') }}" class="btn btn-outline-primary w-100 mt-2">Ubah Password</a>
</div>
@endsection
