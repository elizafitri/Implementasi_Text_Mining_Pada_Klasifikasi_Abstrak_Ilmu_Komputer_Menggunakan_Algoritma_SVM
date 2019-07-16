<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle" src="{{ asset ('assets/img/profile_small.jpg') }}"/>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="block m-t-xs font-bold">{{ Auth::user()->name }}</span>
                        <span class="text-muted text-xs block">Option <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="login.html">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a href="{{ route('home.index') }}"><i class="fa fa-home"></i> <span class="nav-label">Beranda</span></a>
            </li>
            <li class="{{ Request::is('jurnal') ? 'active' : '' }}">
                <a href="{{ route('jurnal') }}"><i class="fa fa-book"></i> <span class="nav-label">Manajemen Jurnal</span></a>
            </li>
            <li class="{{ Request::is('data-token') ? 'active' : '' }}">
                <a href="{{ route('data.token') }}"><i class="fa fa-book"></i> <span class="nav-label">Data Token</span></a>
            </li>
        </ul>
        
    </div>
</nav>