<nav class="navbar navbar-expand navbar-light">
    <div class="container-fluid">

        {{-- Toggle Sidebar --}}
        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>

        {{-- Navbar Toggler Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                {{-- ============================ --}}
                {{-- NOTIFIKASI — SESUAI ROLE --}}
                {{-- ============================ --}}
                <li class="nav-item dropdown me-1">
                    <a class="nav-link active dropdown-toggle"
                        href="#"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        <i class="bi bi-bell bi-sub fs-4 text-gray-600"></i>

                        @php
                            // Ambil jumlah notifikasi sesuai role
                            $notifCount = 0;

                            if (auth()->user()->role === 'admin') {
                                // Notifikasi admin: user pending approval + setoran pending
                                $notifCount = \App\Models\User::where('status', 'pending')->count()
                                            + \App\Models\PickupRequest::where('status', 'pending')->count();
                            } elseif (auth()->user()->role === 'mitra') {
                                // Notifikasi mitra: setoran pending yang belum diambil
                                $notifCount = \App\Models\PickupRequest::where('status', 'pending')
                                                ->whereNull('mitra_id')
                                                ->count();
                            } elseif (auth()->user()->role === 'warga') {
                                // Notifikasi warga: setoran yang sudah diverifikasi / rejected
                                $notifCount = \App\Models\PickupRequest::where('user_id', auth()->id())
                                                ->whereIn('status', ['completed', 'rejected'])
                                                ->whereDate('updated_at', '>=', now()->subDays(7))
                                                ->count();
                            }
                        @endphp

                        @if($notifCount > 0)
                            <span class="badge bg-danger badge-sm">{{ $notifCount }}</span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">

                        <li>
                            <h6 class="dropdown-header">
                                <i class="bi bi-bell"></i> Notifikasi
                            </h6>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        @if(auth()->user()->role === 'admin')
                            {{-- NOTIFIKASI ADMIN --}}
                            @php
                                $pendingUsers = \App\Models\User::where('status', 'pending')->latest()->take(3)->get();
                                $pendingPickups = \App\Models\PickupRequest::where('status', 'pending')->latest()->take(3)->get();
                            @endphp

                            @forelse($pendingUsers as $u)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-person-plus text-warning me-2"></i>
                                        <small>
                                            <strong>{{ $u->name }}</strong> menunggu approval
                                        </small>
                                    </a>
                                </li>
                            @empty
                            @endforelse

                            @forelse($pendingPickups as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.transactions.index') }}">
                                        <i class="bi bi-box-seam text-info me-2"></i>
                                        <small>
                                            Setoran <strong>{{ $p->user->name ?? 'Warga' }}</strong> menunggu
                                        </small>
                                    </a>
                                </li>
                            @empty
                            @endforelse

                            @if($pendingUsers->isEmpty() && $pendingPickups->isEmpty())
                                <li>
                                    <span class="dropdown-item text-muted">
                                        <small>Tidak ada notifikasi baru</small>
                                    </span>
                                </li>
                            @endif

                        @elseif(auth()->user()->role === 'mitra')
                            {{-- NOTIFIKASI MITRA --}}
                            @php
                                $pendingPickups = \App\Models\PickupRequest::where('status', 'pending')
                                                    ->whereNull('mitra_id')
                                                    ->latest()
                                                    ->take(5)
                                                    ->get();
                            @endphp

                            @forelse($pendingPickups as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('mitra.pickup-requests.show', $p->pickup_request_id) }}">
                                        <i class="bi bi-truck text-success me-2"></i>
                                        <small>
                                            Setoran baru dari <strong>{{ $p->user->name ?? 'Warga' }}</strong>
                                        </small>
                                    </a>
                                </li>
                            @empty
                                <li>
                                    <span class="dropdown-item text-muted">
                                        <small>Tidak ada setoran baru</small>
                                    </span>
                                </li>
                            @endforelse

                        @elseif(auth()->user()->role === 'warga')
                            {{-- NOTIFIKASI WARGA --}}
                            @php
                                $myUpdates = \App\Models\PickupRequest::where('user_id', auth()->id())
                                                ->whereIn('status', ['completed', 'rejected'])
                                                ->latest('updated_at')
                                                ->take(5)
                                                ->get();
                            @endphp

                            @forelse($myUpdates as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.pickup-requests.show', $p->pickup_request_id) }}">
                                        @if($p->status === 'completed')
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            <small>
                                                Setoran <strong>{{ $p->wasteCategory->name ?? 'sampah' }}</strong> diverifikasi
                                                (+{{ $p->points_earned }} poin)
                                            </small>
                                        @else
                                            <i class="bi bi-x-circle text-danger me-2"></i>
                                            <small>
                                                Setoran <strong>{{ $p->wasteCategory->name ?? 'sampah' }}</strong> ditolak
                                            </small>
                                        @endif
                                    </a>
                                </li>
                            @empty
                                <li>
                                    <span class="dropdown-item text-muted">
                                        <small>Tidak ada notifikasi baru</small>
                                    </span>
                                </li>
                            @endforelse
                        @endif

                        <li><hr class="dropdown-divider"></li>

                        <li class="text-center">
                            <small class="text-muted">Notifikasi 7 hari terakhir</small>
                        </li>

                    </ul>
                </li>

            </ul>

            {{-- ============================ --}}
            {{-- PROFIL USER — PAKAI INISIAL --}}
            {{-- ============================ --}}
            <div class="dropdown">

                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-menu d-flex">

                        <div class="user-name text-end me-3">
                            <h6 class="mb-0 text-gray-600">
                                {{ auth()->user()->name }}
                            </h6>
                            <p class="mb-0 text-sm text-gray-600">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>

                        {{-- AVATAR INISIAL --}}
                        <div class="user-img d-flex align-items-center">
                            @php
                                // Ambil inisial dari nama
                                $nameParts = explode(' ', trim(auth()->user()->name));
                                $initials = '';
                                if (count($nameParts) >= 2) {
                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                } else {
                                    $initials = strtoupper(substr($nameParts[0], 0, 2));
                                }

                                // Warna background berdasarkan role
                                $bgColor = match(auth()->user()->role) {
                                    'admin' => '#1A237E',   // biru teknologi
                                    'mitra' => '#2E7D32',   // hijau utama
                                    'warga' => '#4CAF50',   // hijau muda
                                    default => '#6C757D',
                                };
                            @endphp

                            <div class="avatar avatar-md"
                                 style="background-color: {{ $bgColor }};
                                        color: white;
                                        width: 40px;
                                        height: 40px;
                                        border-radius: 50%;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-weight: 600;
                                        font-size: 14px;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                {{ $initials }}
                            </div>
                        </div>

                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <h6 class="dropdown-header">
                            Hai, {{ auth()->user()->name }}!
                        </h6>
                    </li>

                    @if(auth()->user()->role == 'admin')
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="icon-mid bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                <i class="icon-mid bi bi-gear me-2"></i> Pengaturan
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'mitra')
                        <li>
                            <a class="dropdown-item" href="{{ route('mitra.profile') }}">
                                <i class="icon-mid bi bi-person me-2"></i> Profil Mitra
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'warga')
                        <li>
                            <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                <i class="icon-mid bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                    @endif

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>