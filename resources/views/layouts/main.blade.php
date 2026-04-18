<!-- Layout Stisla - tema getstisla.com, isi halaman tidak diubah -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Sistem Karyawan')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    @yield('links')
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Bootstrap 5 (agar konten lama ms-/me- tidak rusak) + Stisla assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/custom.css">
    @if(file_exists(public_path('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css')))
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">
    @endif
    <!-- Override agar konten lama tetap rapi -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            @include('partials.topbar')
            @include('partials.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
                @include('partials.footer')
            </div>
        </div>
    </div>

    <!-- Stisla: jQuery + Bootstrap 4 + Stisla JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/stisla.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/custom.js"></script>
    <!-- Lib yang dipakai halaman (chart, datepicker, dll) -->
    <script src="{{ asset('lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    @if(file_exists(public_path('js/main.js')))
    <script src="{{ asset('js/main.js') }}"></script>
    @endif
    @if(file_exists(public_path('js/dashboard.js')))
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @endif
    {{-- Sidebar accordion: animasi stabil (pakai scrollHeight), satu saja terbuka --}}
    <script>
    (function () {
        function closeAccordion(item) {
            item.classList.remove('open');
            var trigger = item.querySelector('.sidebar-accordion-trigger');
            var submenu = item.querySelector('.sidebar-submenu');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            if (submenu) submenu.style.maxHeight = '0px';
        }

        function openAccordion(item) {
            item.classList.add('open');
            var trigger = item.querySelector('.sidebar-accordion-trigger');
            var submenu = item.querySelector('.sidebar-submenu');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
            if (submenu) submenu.style.maxHeight = submenu.scrollHeight + 'px';
        }

        function refreshOpenHeights() {
            document.querySelectorAll('.main-sidebar .sidebar-accordion.open .sidebar-submenu').forEach(function (submenu) {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            });
        }

        var accordions = Array.from(document.querySelectorAll('.main-sidebar .sidebar-accordion'));
        if (!accordions.length) return;

        // init: pastikan semua submenu tertutup dulu (hindari kasus "muncul terus")
        accordions.forEach(function (item) {
            var submenu = item.querySelector('.sidebar-submenu');
            if (submenu) submenu.style.maxHeight = '0px';
            item.classList.remove('open');
        });

        // open yang aktif (jika ada)
        var activeSub = document.querySelector('.main-sidebar .sidebar-submenu li.active');
        if (activeSub) openAccordion(activeSub.closest('.sidebar-accordion'));

        // click handler (event delegation biar stabil walau ada re-render/skrip lain)
        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('.main-sidebar .sidebar-accordion-trigger');
            if (!trigger) return;
            e.preventDefault();
            e.stopPropagation();

            var item = trigger.closest('.sidebar-accordion');
            if (!item) return;

            var isOpen = item.classList.contains('open');
            accordions.forEach(function (other) { closeAccordion(other); });
            if (!isOpen) openAccordion(item);
        }, true);

        window.addEventListener('resize', function () {
            refreshOpenHeights();
        });
    })();
    </script>
    @yield('script')
</body>

</html>
