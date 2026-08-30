<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" srcset="">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">

                @auth

                    {{-- ADMIN --}}
                    @if(auth()->user()->role == 'admin')

                        {{-- Menu Utama --}}
                        <li class="sidebar-title">Menu Utama</li>

                        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                            <a href="{{ route('admin.statistics') }}" class="sidebar-link">
                                <i class="bi bi-graph-up"></i>
                                <span>Statistik</span>
                            </a>
                        </li>

                        {{-- Data & Transaksi --}}
                        <li class="sidebar-title">Data & Transaksi</li>

                        <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>User</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.transactions.index') }}" class="sidebar-link">
                                <i class="bi bi-arrow-left-right"></i>
                                <span>Setoran</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.missions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.missions.index') }}" class="sidebar-link">
                                <i class="bi bi-bullseye"></i>
                                <span>Mission</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.rewards.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.rewards.index') }}" class="sidebar-link">
                                <i class="bi bi-gift"></i>
                                <span>Reward</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                            <a href="{{ route('admin.laporan') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Laporan</span>
                            </a>
                        </li>

                        {{-- Sistem --}}
                        <li class="sidebar-title">Sistem</li>

                        <li class="sidebar-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.notifications.index') }}" class="sidebar-link">
                                <i class="bi bi-bell"></i>
                                <span>Notifikasi</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                            <a href="{{ route('admin.profile') }}" class="sidebar-link">
                                <i class="bi bi-person"></i>
                                <span>Profil</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}" class="sidebar-link">
                                <i class="bi bi-gear"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>

                        {{-- MITRA --}}
                    @elseif(auth()->user()->role == 'mitra')

                        <li class="sidebar-title">Menu Mitra</li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('mitra.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.pickup-requests.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.pickup-requests.index') }}" class="sidebar-link">
                                <i class="bi bi-box-seam"></i>
                                <span>Kelola Setoran</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.transactions.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.transactions.index') }}" class="sidebar-link">
                                <i class="bi bi-clock-history"></i>
                                <span>Riwayat Setoran</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.statistics') ? 'active' : '' }}">
                            <a href="{{ route('mitra.statistics') }}" class="sidebar-link">
                                <i class="bi bi-graph-up"></i>
                                <span>Dashboard Statistik</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Akun</li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.profile') ? 'active' : '' }}">
                            <a href="{{ route('mitra.profile') }}" class="sidebar-link">
                                <i class="bi bi-person"></i>
                                <span>Profil Mitra</span>
                            </a>
                        </li>

                        {{-- WARGA --}}
                    @elseif(auth()->user()->role == 'warga')

                        <li class="sidebar-title">Menu Warga</li>

                        <li class="sidebar-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('user.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.missions.*') ? 'active' : '' }}">
                            <a href="{{ route('user.missions.index') }}" class="sidebar-link">
                                <i class="bi bi-list-check"></i>
                                <span>Waste Mission</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.waste-banks.*') ? 'active' : '' }}">
                            <a href="{{ route('user.waste-banks.index') }}" class="sidebar-link">
                                <i class="bi bi-map"></i>
                                <span>Smart Waste Network</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.user-missions.*') ? 'active' : '' }}">
                            <a href="{{ route('user.user-missions.index') }}" class="sidebar-link">
                                <i class="bi bi-trophy"></i>
                                <span>Eco Habit Score</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.ai-chat-sessions.*') ? 'active' : '' }}">
                            <a href="{{ route('user.ai-chat-sessions.index') }}" class="sidebar-link">
                                <i class="bi bi-robot"></i>
                                <span>Bero AI</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.rewards.*') ? 'active' : '' }}">
                            <a href="{{ route('user.rewards.index') }}" class="sidebar-link">
                                <i class="bi bi-gift"></i>
                                <span>Reward Center</span>
                            </a>
                        </li>

                    @endif

                @endauth

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>