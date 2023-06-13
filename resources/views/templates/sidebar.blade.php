<ul class="sidebar-menu">
    @if(Auth::user()->role != "superadmin")
        <li class="@if(URLHelper::has('dashboard')) active @endif">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
    @endif
    @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
        <li class="@if(URLHelper::has('absensi')) active @endif">
            <a class="nav-link" href="{{ route('absensi.index') }}">
                <i class="fas fa-file-alt"></i>
                <span>Absensi Pegawai</span>
            </a>
        </li>

        <li class="menu-header">Data Master</li>
        <li class="@if(URLHelper::has('jabatan')) active @endif">
            <a class="nav-link" href="{{ route('jabatan.index') }}">
                <i class="fas fa-user-tie"></i>
                <span>Jabatan</span>
            </a>
        </li>
        <li class="@if(URLHelper::has('pangkat')) active @endif">
            <a class="nav-link" href="{{ route('pangkat.index') }}">
                <i class="fas fa-user-check"></i>
                <span>Pangkat</span>
            </a>
        </li>
        <li class="@if(URLHelper::has('status')) active @endif">
            <a class="nav-link" href="{{ route('status.index') }}">
                <i class="fas fa-user-tag"></i>
                <span>Status kepegawaian</span>
            </a>
        </li>
        <li class="@if(URLHelper::has('account')) active @endif">
            <a class="nav-link" href="{{ route('account.index') }}">
                <i class="fas fa-user-cog"></i>
                <span>Akun</span>
            </a>
        </li>
    @endif
</ul>