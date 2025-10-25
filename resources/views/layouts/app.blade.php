<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard') | KantorApp</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


    <style>
        :root {
            --sidebar-width: 260px;
            --navbar-height: 56px;
        }

        body {
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebarMenu {
            width: var(--sidebar-width);
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            bottom: 0;
            background: #fff;
            border-right: 1px solid #dee2e6;
            transition: transform 0.3s ease;
            z-index: 1030;
            overflow-y: auto;
        }

        #sidebarMenu.hidden {
            transform: translateX(-100%);
        }

        /* Main content */
        #mainContent {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            transition: margin-left 0.3s ease;
            background: #f8f9fa;
            min-height: calc(100vh - var(--navbar-height));
        }

        #mainContent.full {
            margin-left: 0;
        }

        @media (max-width: 992px) {
            #sidebarMenu {
                transform: translateX(-100%);
            }

            #sidebarMenu.show {
                transform: translateX(0);
            }

            #mainContent {
                margin-left: 0 !important;
            }
        }

        .rotate {
            transition: transform 0.2s ease;
        }

        .rotate.down {
            transform: rotate(90deg);
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm fixed-top" style="height:56px;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ url('/dashboard') }}">
                <i class="fas fa-bolt me-2"></i>KantorApp
            </a>

            <button class="btn btn-outline-primary d-lg-none" id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>

            <form class="d-none d-md-flex ms-auto me-3">
                <div class="input-group input-group-sm">
                    <input class="form-control border-end-0" type="text" placeholder="Search...">
                    <button class="btn btn-outline-primary border-start-0" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            @auth
                @php
                    $pendingFollowUps = \App\Models\PenagihanLapangan::where('status', 'JANJI')
                        ->where('user_id', Auth::id())
                        ->whereDate('tanggal_janji', '<=', now())
                        ->count();
                @endphp

                @if ($pendingFollowUps > 0)
                    <a href="{{ route('penagihan.laporan', ['filter' => 'followup']) }}"
                        class="btn btn-warning btn-sm rounded-pill me-3 shadow-sm">
                        🔔 Follow-Up: <strong>{{ $pendingFollowUps }}</strong>
                    </a>
                @endif

            @endauth

            @auth
                <div class="dropdown">
                    <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#"
                        data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d6efd&color=fff"
                            width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()->name }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
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

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebarMenu">
            <div class="p-3">
                <h6 class="text-muted text-uppercase small">Core</h6>

                <a href="{{ url('/dashboard') }}"
                    class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 {{ request()->is('dashboard') ? 'bg-primary text-white' : 'text-dark' }}">
                    <i class="fas fa-gauge me-2"></i> Dashboard
                </a>

                @can('manage users')
                    <a href="{{ url('/users') }}"
                        class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 {{ request()->is('users*') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                @endcan

                @can('manage roles')
                    <a href="{{ url('/roles') }}"
                        class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 {{ request()->is('roles*') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-user-shield me-2"></i> Roles
                    </a>
                @endcan

                @can('manage permissions')
                    <a href="{{ url('/permissions') }}"
                        class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 {{ request()->is('permissions*') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-key me-2"></i> Permissions
                    </a>
                @endcan

                @can('view logs')
                    <a href="{{ route('logs.index') }}"
                        class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 {{ request()->is('logs*') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-clipboard-list me-2"></i> Activity Logs
                    </a>
                @endcan
                @can('manage atk')
                    <h6 class="text-muted text-uppercase small mt-4">Inventaris</h6>

                    <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
        {{ request()->is('atk*') || request()->is('atk-masuk*') || request()->is('atk-keluar*') ? 'bg-primary text-white' : 'text-dark' }}"
                        data-bs-toggle="collapse" href="#collapseAtk" role="button"
                        aria-expanded="{{ request()->is('atk*') || request()->is('atk-masuk*') || request()->is('atk-keluar*') ? 'true' : 'false' }}"
                        aria-controls="collapseAtk">
                        <span><i class="fas fa-boxes me-2"></i> ATK</span>
                        <i
                            class="fas fa-chevron-right small rotate
            {{ request()->is('atk*') || request()->is('atk-masuk*') || request()->is('atk-keluar*') ? 'down' : '' }}"></i>
                    </a>

                    <div class="collapse ps-3
        {{ request()->is('atk*') || request()->is('atk-masuk*') || request()->is('atk-keluar*') ? 'show' : '' }}"
                        id="collapseAtk">

                        <a href="{{ route('atk.index') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
            {{ request()->is('atk*') && !request()->is('atk-masuk*') && !request()->is('atk-keluar*') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-box text-secondary me-2"></i> Master ATK
                        </a>

                        <a href="{{ route('atk-masuk.index') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
            {{ request()->is('atk-masuk*') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-sign-in-alt text-secondary me-2"></i> Barang Masuk
                        </a>

                        <a href="{{ route('atk-keluar.index') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
            {{ request()->is('atk-keluar*') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-sign-out-alt text-secondary me-2"></i> Barang Keluar
                        </a>

                    </div>
                @endcan
                @can('manage penagihan')
                    <h6 class="text-muted text-uppercase small mt-4">Penagihan</h6>

                    {{-- Collapsible Parent --}}
                    <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
        {{ request()->is('penagihan*') || request()->is('kredit-lalai*') ? 'bg-primary text-white' : 'text-dark' }}"
                        data-bs-toggle="collapse" href="#collapsePenagihan" role="button"
                        aria-expanded="{{ request()->is('penagihan*') || request()->is('kredit-lalai*') ? 'true' : 'false' }}"
                        aria-controls="collapsePenagihan">

                        <span><i class="fas fa-hand-holding-dollar me-2"></i> Penagihan</span>
                        <i
                            class="fas fa-chevron-right small rotate
           {{ request()->is('penagihan*') || request()->is('kredit-lalai*') ? 'down' : '' }}"></i>
                    </a>

                    {{-- Child Menus --}}
                    <div class="collapse ps-3
        {{ request()->is('penagihan*') || request()->is('kredit-lalai*') ? 'show' : '' }}"
                        id="collapsePenagihan">

                        {{-- Kredit Lalai --}}
                        <a href="{{ route('kredit-lalai.create') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('kredit-lalai/create') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-triangle-exclamation text-secondary me-2"></i> Input Kredit Lalai
                        </a>

                        <a href="{{ route('kredit-lalai.index') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('kredit-lalai') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-chart-line text-secondary me-2"></i> Laporan Kredit Lalai
                        </a>

                        {{-- Penagihan Lapangan --}}
                        <a href="{{ route('penagihan.create') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('penagihan/create') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-plus text-secondary me-2"></i> Input Penagihan
                        </a>

                        <a href="{{ route('penagihan.laporan') }}"
                            class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('penagihan-laporan') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fas fa-chart-pie text-secondary me-2"></i> Laporan Penagihan
                        </a>

                    </div>
                @endcan

                {{-- === KLPK SECTION === --}}
                <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
    {{ request()->is('klpk*') ? 'bg-primary text-white' : 'text-dark' }}"
                    data-bs-toggle="collapse" href="#collapseKLPK" role="button"
                    aria-expanded="{{ request()->is('klpk*') ? 'true' : 'false' }}" aria-controls="collapseKLPK">

                    <span><i class="fas fa-user-slash me-2"></i> Data KLPK</span>
                    <i class="fas fa-chevron-right small rotate {{ request()->is('klpk*') ? 'down' : '' }}"></i>
                </a>

                <div class="collapse ps-3 {{ request()->is('klpk*') ? 'show' : '' }}" id="collapseKLPK">

                    {{-- Daftar KLPK --}}
                    <a href="{{ route('klpk.index') }}"
                        class="d-flex align-items-center p-2 small rounded text-decoration-none mb-1
        {{ request()->is('klpk') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-table me-2 text-secondary"></i> Daftar KLPK
                    </a>
                    <a href="{{ route('klpk.rekap.bulanan') }}"
                        class="d-flex align-items-center p-2 small rounded text-decoration-none mb-1
         {{ request()->is('klpk-rekap') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-folder-open me-2 text-secondary"></i> Rekap Bulanan
                    </a>
                </div>


                {{-- === KEPEGAWAIAN SECTION === --}}
                @canany(['view pegawai', 'manage pegawai', 'view jabatan', 'view bagian'])
                    <h6 class="text-muted text-uppercase small mt-4">Kepegawaian</h6>

                    <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
        {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'bg-primary text-white' : 'text-dark' }}"
                        data-bs-toggle="collapse" href="#collapseKepegawaian" role="button"
                        aria-expanded="{{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'true' : 'false' }}"
                        aria-controls="collapseKepegawaian">
                        <span><i class="fas fa-briefcase me-2"></i> Kepegawaian</span>
                        <i
                            class="fas fa-chevron-right small rotate {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'down' : '' }}"></i>
                    </a>

                    <div class="collapse ps-3 {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'show' : '' }}"
                        id="collapseKepegawaian">

                        @canany(['view pegawai', 'manage pegawai'])
                            <a href="{{ route('pegawai.index') }}"
                                class="d-flex align-items-center p-2 rounded text-decoration-none small mb-1
                {{ request()->is('pegawai*') ? 'bg-primary text-white' : 'text-dark' }}">
                                <i class="fas fa-id-badge me-2 text-secondary"></i> Pegawai
                            </a>
                        @endcanany

                        @canany(['view bagian', 'manage bagian'])
                            <a href="{{ route('bagian.index') }}"
                                class="d-flex align-items-center p-2 rounded text-decoration-none small mb-1
                {{ request()->is('bagian*') ? 'bg-primary text-white' : 'text-dark' }}">
                                <i class="fas fa-layer-group me-2 text-secondary"></i> Bagian
                            </a>
                        @endcanany

                        @canany(['view jabatan', 'manage jabatan'])
                            <a href="{{ route('jabatan.index') }}"
                                class="d-flex align-items-center p-2 rounded text-decoration-none small mb-1
                {{ request()->is('jabatan*') ? 'bg-primary text-white' : 'text-dark' }}">
                                <i class="fas fa-briefcase me-2 text-secondary"></i> Jabatan
                            </a>
                        @endcanany
                    </div>
                @endcanany

                <h6 class="text-muted text-uppercase small mt-4">Reports</h6>

                <!-- Collapsible Reports -->
                <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none text-dark mb-1"
                    data-bs-toggle="collapse" href="#collapseReports" role="button" aria-expanded="false"
                    aria-controls="collapseReports">
                    <span><i class="fas fa-chart-line me-2"></i> Reports</span>
                    <i class="fas fa-chevron-right small rotate"></i>
                </a>
                <div class="collapse ps-3" id="collapseReports">
                    <a href="#"
                        class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                        <i class="fas fa-chart-column me-2 text-secondary"></i> Sales
                    </a>
                    <a href="#"
                        class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                        <i class="fas fa-coins me-2 text-secondary"></i> Finance
                    </a>
                    <a href="#"
                        class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                        <i class="fas fa-users-gear me-2 text-secondary"></i> HR
                    </a>
                </div>
                @can('view logs')
                    <a href="{{ route('logs.index') }}" class="d-flex align-items-center p-2 text-dark small mb-1">
                        <i class="fas fa-history me-2"></i> Log Aktivitas
                    </a>
                @endcan
                @can('manage kredit lalai')
                    <a href="{{ route('klpk.followup') }}"
                        class="d-flex align-items-center p-2 small rounded text-decoration-none mb-1
        {{ request()->is('klpk-followup') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-bell text-danger me-2"></i> Follow-Up
                    </a>
                @endcan

                @can('view all kredit lalai')
                    <a href="{{ route('klpk.dashboard') }}"
                        class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
    {{ request()->is('klpk-dashboard') ? 'bg-primary text-white' : 'text-dark' }}">
                        <i class="fas fa-chart-bar text-secondary me-2"></i> Dashboard KLPK
                    </a>
                @endcan

                <!-- System Section -->
                @role('admin')
                    <h6 class="text-muted text-uppercase small mt-4">System</h6>

                    <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none text-dark mb-1"
                        data-bs-toggle="collapse" href="#collapseSystem" role="button" aria-expanded="false"
                        aria-controls="collapseSystem">
                        <span><i class="fas fa-cogs me-2"></i> System</span>
                        <i class="fas fa-chevron-right small rotate"></i>
                    </a>
                    <div class="collapse ps-3" id="collapseSystem">
                        <a href="{{ route('logs.index') }}"
                            class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                            <i class="fas fa-list me-2 text-secondary"></i> Activity Logs
                        </a>
                        <a href="{{ url('/roles') }}"
                            class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                            <i class="fas fa-user-gear me-2 text-secondary"></i> Roles
                        </a>
                        <a href="{{ url('/permissions') }}"
                            class="d-flex align-items-center p-2 rounded text-decoration-none text-dark small mb-1">
                            <i class="fas fa-key me-2 text-secondary"></i> Permissions
                        </a>
                    </div>
                @endrole

                <div class="border-top text-center pt-3 mt-3 small text-muted">
                    Logged in as:<br><strong>{{ Auth::user()->name ?? 'Guest' }}</strong>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="mainContent" class="flex-grow-1">
            <main class="container-fluid py-4">
                @yield('content')
            </main>
            <footer class="py-3 bg-white text-center small text-muted border-top mt-4">
                © KantorApp 2025 — All Rights Reserved
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebarMenu");
            const main = document.getElementById("mainContent");
            const toggleBtn = document.getElementById("sidebarToggle");

            function toggleSidebar() {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle("show");
                    return;
                }
                const hidden = sidebar.classList.toggle("hidden");
                main.classList.toggle("full", hidden);
                localStorage.setItem("sidebarHidden", hidden ? "true" : "false");
            }

            const saved = localStorage.getItem("sidebarHidden") === "true";
            if (saved && window.innerWidth >= 992) {
                sidebar.classList.add("hidden");
                main.classList.add("full");
            }

            toggleBtn.addEventListener("click", toggleSidebar);

            // Chevron rotation for collapse
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(trigger => {
                const icon = trigger.querySelector('.rotate');
                trigger.addEventListener('click', () => {
                    setTimeout(() => {
                        icon.classList.toggle('down', trigger.getAttribute(
                            'aria-expanded') === 'true');
                    }, 150);
                });
            });

            window.addEventListener("resize", () => {
                if (window.innerWidth < 992) {
                    sidebar.classList.add("hidden");
                    main.classList.add("full");
                } else {
                    const saved = localStorage.getItem("sidebarHidden") === "true";
                    sidebar.classList.toggle("hidden", saved);
                    main.classList.toggle("full", saved);
                }
            });
            document.addEventListener("DOMContentLoaded", function() {
                const sidebar = document.getElementById("sidebarMenu");
                const main = document.getElementById("mainContent");
                const toggleBtn = document.getElementById("sidebarToggle");

                function toggleSidebar() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.toggle("show");
                        return;
                    }
                    const hidden = sidebar.classList.toggle("hidden");
                    main.classList.toggle("full", hidden);
                    localStorage.setItem("sidebarHidden", hidden ? "true" : "false");
                }
            });
        });
    </script>
</body>

</html>
