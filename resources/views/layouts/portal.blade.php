<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Karyawan')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="{{ asset('css/portal.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="portal-body">
    @yield('content')

    @php
        $navActive = $navActive ?? 'home';
    @endphp
    <nav class="portal-bottom-nav">
        <a href="{{ route('portal.home') }}" class="{{ $navActive === 'home' ? 'active' : '' }}">
            <i class="fas fa-home"></i> Beranda
        </a>
        <a href="{{ route('portal.absensi') }}" class="{{ $navActive === 'absensi' ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Absensi
        </a>
        <div class="nav-fab">
            <a href="{{ route('portal.presensi') }}" aria-label="Presensi">
                <i class="fas fa-fingerprint"></i>
            </a>
        </div>
        <a href="{{ route('portal.gaji') }}" class="{{ $navActive === 'gaji' ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> Gaji
        </a>
        <a href="{{ route('portal.profil') }}" class="{{ $navActive === 'profil' ? 'active' : '' }}">
            <i class="fas fa-user"></i> Profil
        </a>
    </nav>

    <script>
        window.PORTAL_CSRF = document.querySelector('meta[name="csrf-token"]').content;
        window.PORTAL_USER_EMAIL = @json(auth()->user()?->email);
        window.PORTAL_ROUTES = {
            deviceRegister: @json(route('portal.device.register')),
            checkin: @json(route('portal.presensi.checkin')),
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/portal.js') }}"></script>
    @stack('scripts')
    <script>
        // Global SweetAlert for session messages
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') }}" });
        @endif
    </script>
</body>
</html>
