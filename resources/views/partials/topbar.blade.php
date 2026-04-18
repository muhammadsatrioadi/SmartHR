{{-- Navbar Stisla (topbar) --}}
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="/img/{{ session('user')->imgProfile ?? 'user.png' }}" class="rounded-circle mr-1" style="width: 30px; height: 30px;">
                <div class="d-sm-none d-lg-inline-block">{{ session('user')->name ?? 'User' }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item has-icon"><i class="far fa-user"></i> My Profile</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('actionlogout') }}" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i> Log Out</a>
            </div>
        </li>
    </ul>
</nav>
