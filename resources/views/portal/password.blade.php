@extends('layouts.portal')
@section('title', 'Ubah Password')
@php $navActive = 'profil'; @endphp
@section('content')
<div class="portal-page-header">
    <a href="{{ route('portal.profil') }}"><i class="fas fa-arrow-left"></i></a>
    <strong>Ubah Password</strong>
</div>
<div class="portal-content" style="margin-top:0;padding-top:1rem;">
    @if(session('error'))<div class="alert alert-danger small">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('portal.profil.password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control form-control-sm" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Password Baru</label>
            <input type="password" name="new_password" class="form-control form-control-sm" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="form-control form-control-sm" required>
        </div>
        <button type="submit" class="portal-btn-checkin w-100">
            <i class="fas fa-key"></i> SIMPAN PASSWORD
        </button>
    </form>
</div>
@endsection
