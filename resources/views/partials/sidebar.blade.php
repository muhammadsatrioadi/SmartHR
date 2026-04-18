{{-- Sidebar: accordion custom (bukan Bootstrap dropdown) agar submenu tidak tabrakan --}}
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('index') }}">Sistem Karyawan</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('index') }}">SK</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="nav-item {{ request()->routeIs('index') ? 'active' : '' }}">
                <a href="{{ route('index') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('karyawans.*') ? 'active' : '' }}">
                <a href="{{ route('karyawans.index') }}" class="nav-link"><i class="fas fa-users"></i><span>Master Pegawai</span></a>
            </li>
            <li class="nav-item {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
                <a href="{{ route('cuti.read') }}" class="nav-link"><i class="fas fa-keyboard"></i><span>Cuti</span></a>
            </li>

            @php $role = auth()->user()->role ?? 'karyawan'; @endphp

            {{-- Menu khusus Admin HR --}}
            @if ($role === 'admin_hr')
                <li class="nav-item sidebar-accordion {{ request()->routeIs('jabatan.*','golongan.*','pangkat.*','employeeStatus.*','department.*','workUnit.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link sidebar-accordion-trigger" aria-expanded="false"><i class="fas fa-cog"></i><span>Setup Master</span><i class="fas fa-chevron-down sidebar-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li class="{{ request()->routeIs('jabatan.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('jabatan.read') }}">Jabatan</a></li>
                        <li class="{{ request()->routeIs('golongan.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('golongan.index') }}">Golongan</a></li>
                        <li class="{{ request()->routeIs('pangkat.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('pangkat.index') }}">Pangkat</a></li>
                        <li class="{{ request()->routeIs('employeeStatus.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('employeeStatus.index') }}">Status Pegawai RS</a></li>
                        <li class="{{ request()->routeIs('department.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('department.index') }}">Departemen</a></li>
                        <li class="{{ request()->routeIs('workUnit.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('workUnit.index') }}">Unit Kerja</a></li>
                    </ul>
                </li>
                <li class="nav-item sidebar-accordion {{ request()->routeIs('holiday.*','shiftGroup.*','workShift.*','leaveType.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link sidebar-accordion-trigger" aria-expanded="false"><i class="fas fa-calendar"></i><span>Setup Jadwal & Cuti</span><i class="fas fa-chevron-down sidebar-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li class="{{ request()->routeIs('holiday.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('holiday.index') }}">Hari Raya & Libur</a></li>
                        <li class="{{ request()->routeIs('shiftGroup.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('shiftGroup.index') }}">Group Shift</a></li>
                        <li class="{{ request()->routeIs('workShift.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('workShift.index') }}">Shift Kerja</a></li>
                        <li class="{{ request()->routeIs('leaveType.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('leaveType.index') }}">Jenis Cuti</a></li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->routeIs('employeeSchedule.*') ? 'active' : '' }}">
                    <a href="{{ route('employeeSchedule.index') }}" class="nav-link"><i class="fas fa-table"></i><span>Jadwal Shift Pegawai</span></a>
                </li>
                <li class="nav-item sidebar-accordion {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link sidebar-accordion-trigger" aria-expanded="false"><i class="fas fa-chart-bar"></i><span>Absensi</span><i class="fas fa-chevron-down sidebar-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li class="{{ request()->routeIs('absensi.read') ? 'active' : '' }}"><a class="nav-link" href="{{ route('absensi.read') }}">Absensi</a></li>
                        <li class="{{ request()->routeIs('absensi.absensiHadir') ? 'active' : '' }}"><a class="nav-link" href="{{ route('absensi.absensiHadir') }}">Daftar Hadir</a></li>
                        <li class="{{ request()->routeIs('absensi.absensiAlpha') ? 'active' : '' }}"><a class="nav-link" href="{{ route('absensi.absensiAlpha') }}">Daftar Alpha</a></li>
                    </ul>
                </li>
                <li class="nav-item sidebar-accordion {{ request()->routeIs('gaji.*','salaryStep.*','salaryHistory.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link sidebar-accordion-trigger" aria-expanded="false"><i class="fas fa-money-bill"></i><span>Gaji</span><i class="fas fa-chevron-down sidebar-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li class="{{ request()->routeIs('gaji.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('gaji.index') }}">Gaji Bulanan</a></li>
                        <li class="{{ request()->routeIs('salaryStep.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('salaryStep.index') }}">Skala Gaji Berkala</a></li>
                        <li class="{{ request()->routeIs('salaryHistory.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('salaryHistory.index') }}">Riwayat Gaji Pegawai</a></li>
                    </ul>
                </li>
            @endif

            {{-- Lembur & Absensi: bisa diakses admin_hr, atasan, dan karyawan --}}
            @if (in_array($role, ['admin_hr', 'atasan', 'karyawan']))
                <li class="nav-item {{ request()->routeIs('overtime.*') ? 'active' : '' }}">
                    <a href="{{ route('overtime.index') }}" class="nav-link"><i class="fas fa-clock"></i><span>Lembur</span></a>
                </li>
                <li class="nav-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <a href="{{ route('absensi.read') }}" class="nav-link"><i class="fas fa-fingerprint"></i><span>Absensi</span></a>
                </li>
            @endif
        </ul>
    </aside>
</div>
