{{-- Navbar Stisla (topbar) --}}
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="nav-item d-flex align-items-center me-2">
            <form method="POST" action="{{ route('language.update') }}" class="language-switch-form">
                @csrf
                <div class="language-switch" role="radiogroup" aria-label="{{ __('Ganti bahasa') }}">
                    <input type="radio" id="topbar-locale-id" name="locale" value="id" {{ app()->getLocale() === 'id' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label for="topbar-locale-id">ID</label>
                    <input type="radio" id="topbar-locale-en" name="locale" value="en" {{ app()->getLocale() === 'en' ? 'checked' : '' }} onchange="this.form.submit()">
                    <label for="topbar-locale-en">EN</label>
                    <span class="language-switch__thumb" aria-hidden="true"></span>
                </div>
            </form>
        </li>
        <li class="dropdown">
            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="/img/{{ session('user')->imgProfile ?? 'user.png' }}" class="rounded-circle mr-1" style="width: 30px; height: 30px;">
                <div class="d-sm-none d-lg-inline-block">{{ session('user')->name ?? 'User' }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item has-icon"><i class="far fa-user"></i> {{ __('My Profile') }}</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('actionlogout') }}" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i> {{ __('Log Out') }}</a>
            </div>
        </li>
    </ul>
</nav>
