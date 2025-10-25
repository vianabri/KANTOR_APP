<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/dashboard') }}">
            <i class="fas fa-bolt me-2"></i> KantorApp
        </a>

        <button class="btn btn-outline-primary" id="sidebarToggle" type="button">
            <i class="fas fa-bars"></i>
        </button>

        <form class="d-none d-md-flex ms-auto me-4">
            <div class="input-group input-group-sm">
                <input class="form-control border-end-0" type="text" placeholder="Search...">
                <button class="btn btn-outline-primary border-start-0"><i class="fas fa-search"></i></button>
            </div>
        </form>

        @auth
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    id="dropdownUser" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d6efd&color=fff"
                        width="32" height="32" class="rounded-circle me-2" alt="User Avatar">
                    <strong>{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 text-primary"></i>Profile</a>
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2 text-secondary"></i>Settings</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</nav>
