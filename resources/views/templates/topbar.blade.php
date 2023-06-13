<form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </form>
  
  <ul class="navbar-nav navbar-right">
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user align-middle">
        <i class="fas fa-user-circle mr-1"></i>
        <div class="d-sm-none d-lg-inline-block">
          {{ Auth::user()->name ?? '' }}  
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('profile.index') }}" class="dropdown-item has-icon"><i class="fas fa-user-circle"></i> Ganti Profil</a>
        <a href="{{ route('profile.password') }}" class="dropdown-item has-icon"><i class="fas fa-key"></i> Ganti Password</a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>