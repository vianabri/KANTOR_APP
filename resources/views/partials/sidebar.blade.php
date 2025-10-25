<div id="sidebarMenu"
    class="bg-white border-end shadow-sm vh-100 position-fixed d-flex flex-column justify-content-between"
    style="width: 250px; top: 56px;">

    <div class="p-3">

        {{-- ======================= --}}
        {{-- 🔹 CORE SECTION --}}
        {{-- ======================= --}}
        <h6 class="text-muted text-uppercase small mb-2">Core</h6>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center p-2 rounded text-decoration-none mb-1
                {{ request()->is('dashboard') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
            <i class="fas fa-gauge me-2"></i> Dashboard
        </a>

        {{-- Users --}}
        @can('manage users')
            <a href="{{ route('users.index') }}"
                class="d-flex align-items-center p-2 rounded text-decoration-none mb-1
                    {{ request()->is('users*') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                <i class="fas fa-users me-2"></i> Users
            </a>
        @endcan

        {{-- Roles --}}
        @can('manage roles')
            <a href="{{ route('roles.index') }}"
                class="d-flex align-items-center p-2 rounded text-decoration-none mb-1
                    {{ request()->is('roles*') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                <i class="fas fa-user-shield me-2"></i> Roles
            </a>
        @endcan

        {{-- Logs --}}
        @can('view logs')
            <a href="{{ route('logs.index') }}"
                class="d-flex align-items-center p-2 rounded text-decoration-none mb-1
                    {{ request()->is('logs*') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                <i class="fas fa-clipboard-list me-2"></i> Activity Logs
            </a>
        @endcan


        {{-- ======================= --}}
        {{-- 🔹 KEPEGAWAIAN SECTION --}}
        {{-- ======================= --}}
        @canany(['view pegawai', 'manage pegawai', 'view jabatan', 'view bagian'])
            <h6 class="text-muted text-uppercase small mt-4 mb-2">Kepegawaian</h6>

            {{-- Collapsible Parent --}}
            <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
                {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'bg-primary text-white' : 'text-dark' }}"
                data-bs-toggle="collapse" href="#collapseKepegawaian" role="button"
                aria-expanded="{{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'true' : 'false' }}"
                aria-controls="collapseKepegawaian">
                <span><i class="fas fa-briefcase me-2"></i> Kepegawaian</span>
                <i
                    class="fas fa-chevron-right small rotate
                    {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'down' : '' }}"></i>
            </a>

            {{-- Child Items --}}
            <div class="collapse ps-3
                {{ request()->is('pegawai*') || request()->is('bagian*') || request()->is('jabatan*') ? 'show' : '' }}"
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


        {{-- ======================= --}}
        {{-- 🔹 PENAGIHAN SECTION --}}
        {{-- ======================= --}}
        @can('manage penagihan')
            <h6 class="text-muted text-uppercase small mt-4 mb-2">Penagihan</h6>

            {{-- Parent Toggle --}}
            <a class="d-flex justify-content-between align-items-center p-2 rounded text-decoration-none mb-1
        {{ request()->is('penagihan*') ? 'bg-primary text-white' : 'text-dark' }}"
                data-bs-toggle="collapse" href="#collapsePenagihan" role="button"
                aria-expanded="{{ request()->is('penagihan*') ? 'true' : 'false' }}" aria-controls="collapsePenagihan">
                <span><i class="fas fa-hand-holding-dollar me-2"></i> Penagihan</span>
                <i class="fas fa-chevron-right small rotate {{ request()->is('penagihan*') ? 'down' : '' }}"></i>
            </a>

            {{-- Submenu --}}
            <div class="collapse ps-3 {{ request()->is('penagihan*') ? 'show' : '' }}" id="collapsePenagihan">

                <a href="{{ route('penagihan.create') }}"
                    class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('penagihan/create') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                    <i class="fas fa-plus text-secondary me-2"></i> Input Penagihan
                </a>

                <a href="{{ route('penagihan.laporan') }}"
                    class="d-flex align-items-center p-2 rounded small text-decoration-none mb-1
                {{ request()->is('penagihan-laporan*') ? 'bg-primary text-white fw-semibold' : 'text-dark' }}">
                    <i class="fas fa-chart-line text-secondary me-2"></i> Laporan Penagihan
                </a>

            </div>
        @endcan


        {{-- ======================= --}}
        {{-- 🔹 FOOTER (USER INFO) --}}
        {{-- ======================= --}}
        <div class="border-top text-center small text-muted p-2">
            <div>Logged in as:</div>
            <strong>{{ Auth::user()->name ?? 'Guest' }}</strong>
            @role('admin')
                <div class="text-primary small">(Administrator)</div>
            @endrole
        </div>
    </div>
