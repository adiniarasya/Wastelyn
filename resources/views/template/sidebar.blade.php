<style>
    /* Bikin isi menu jadi flexbox biar sejajar vertikal */
    #sidebar .sidebar-link {
        display: flex !important;
        align-items: center !important;
        gap: 12px; /* Jarak antara icon dan teks */
    }

    /* Reset bawaan icon biar gak turun */
    #sidebar .sidebar-link i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        line-height: 1;
        margin: 0;
        padding: 0;
        font-size: 1.2rem; /* Sesuaikan ukuran icon kalau perlu */
    }

    /* Teks juga di tengah */
    #sidebar .sidebar-link span {
        display: inline-block;
        line-height: 1.2;
        vertical-align: middle;
    }
</style>


<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center w-100" style="padding: 20px 22px;">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <div class="logo">
                    <a href="{{ url('/') }}" class="d-flex align-items-center"
                        style="gap: 12px; text-decoration: none;">
                        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="sidebar-logo"
                            style="width: 38px; height: 38px; object-fit: contain; flex-shrink: 0;">
                        <span class="sidebar-brand"
                            style="font-size: 20px; font-weight: 700; line-height: 1; color: #2E7D32; letter-spacing: -0.3px;">
                            WasteLyn
                        </span>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                @auth
                    @php
                        $lastSeen = session('notifications_seen_at');
                        $sidebarNotifCount = 0;

                        if (auth()->user()->role === 'admin') {
                            $sidebarNotifCount = \App\Models\User::where('status', 'pending')
                                ->when($lastSeen, fn($q) => $q->where('created_at', '>', $lastSeen))
                                ->count()
                                + \App\Models\PickupRequest::where('status', 'pending')
                                    ->when($lastSeen, fn($q) => $q->where('created_at', '>', $lastSeen))
                                    ->count();
                        } elseif (auth()->user()->role === 'mitra') {
                            $sidebarNotifCount = \App\Models\PickupRequest::where('status', 'pending')
                                ->where('mitra_id', auth()->id())
                                ->when($lastSeen, fn($q) => $q->where('created_at', '>', $lastSeen))
                                ->count();
                        } elseif (auth()->user()->role === 'warga') {
                            $sidebarNotifCount = \App\Models\PickupRequest::where('user_id', auth()->id())
                                ->whereIn('status', ['completed', 'rejected'])
                                ->when($lastSeen, fn($q) => $q->where('updated_at', '>', $lastSeen))
                                ->count();
                        }
                    @endphp

                    @if(auth()->user()->role == 'admin')

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

                        <li class="sidebar-title">Data & Transaksi</li>

                        <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                                <i class="bi bi-people-fill"></i>
                                <span>User</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('admin.waste-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.waste-categories.index') }}" class="sidebar-link">
                                <i class="bi bi-recycle"></i>
                                <span>Jenis Sampah</span>
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

                        <li class="sidebar-item {{ request()->routeIs('mitra.harga-sampah.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.harga-sampah.index') }}" class="sidebar-link">
                                <i class="bi bi-cash-coin"></i>
                                <span>Kelola Harga Sampah</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.laporan') ? 'active' : '' }}">
                            <a href="{{ route('mitra.laporan') }}" class="sidebar-link">
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span>Laporan Bulanan</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.missions.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.missions.index') }}" class="sidebar-link">
                                <i class="bi bi-bullseye"></i>
                                <span>Kelola Mission</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.rewards.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.rewards.index') }}" class="sidebar-link">
                                <i class="bi bi-gift"></i>
                                <span>Kelola Reward</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Akun</li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.notifications.*') ? 'active' : '' }}">
                            <a href="{{ route('mitra.notifications.index') }}" class="sidebar-link">
                                <i class="bi bi-bell"></i>
                                <span>Notifikasi</span>
                                @if($sidebarNotifCount > 0)
                                    <span class="badge bg-danger ms-auto sidebar-notif-badge">{{ $sidebarNotifCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('mitra.profile') ? 'active' : '' }}">
                            <a href="{{ route('mitra.profile') }}" class="sidebar-link">
                                <i class="bi bi-person"></i>
                                <span>Profil Mitra</span>
                            </a>
                        </li>

                    @elseif(auth()->user()->role == 'warga')

                        <li class="sidebar-title">Menu Warga</li>

                        <li class="sidebar-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('user.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.user-missions.*') ? 'active' : '' }}">
                            <a href="{{ route('user.user-missions.index') }}" class="sidebar-link">
                                <i class="bi bi-list-check"></i>
                                <span>Waste Mission</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.pickup-requests.*') ? 'active' : '' }}">
                            <a href="{{ route('user.pickup-requests.index') }}" class="sidebar-link">
                                <i class="bi bi-truck"></i>
                                <span>Setoran Sampah</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.waste-banks.*') ? 'active' : '' }}">
                            <a href="{{ route('user.waste-banks.index') }}" class="sidebar-link">
                                <i class="bi bi-map"></i>
                                <span>Smart Waste Network</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('user.eco-habit.*') ? 'active' : '' }}">
                            <a href="{{ route('user.eco-habit.index') }}" class="sidebar-link">
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

    </div>
</div>