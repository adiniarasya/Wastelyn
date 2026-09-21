<nav class="navbar navbar-expand navbar-light wl-navbar-clean">
    <div class="container-fluid">

        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

                {{-- NOTIFIKASI --}}
                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown" aria-expanded="false"
                       style="padding: 6px 10px;">
                        <i class="bi bi-bell" style="font-size: 20px; color: #5a6474;"></i>

                        @php
                            $notifCount = 0;
                            if (auth()->user()->role === 'admin') {
                                $notifCount = \App\Models\User::where('status', 'pending')->count()
                                    + \App\Models\PickupRequest::where('status', 'pending')->count();
                            } elseif (auth()->user()->role === 'mitra') {
                                $notifCount = \App\Models\PickupRequest::where('status', 'pending')
                                    ->whereNull('mitra_id')->count();
                            } elseif (auth()->user()->role === 'warga') {
                                $notifCount = \App\Models\PickupRequest::where('user_id', auth()->id())
                                    ->whereIn('status', ['completed', 'rejected'])
                                    ->whereDate('updated_at', '>=', now()->subDays(7))
                                    ->count();
                            }
                        @endphp

                        @if($notifCount > 0)
                            <span class="position-absolute" style="
                                top: 4px;
                                right: 4px;
                                min-width: 18px;
                                height: 18px;
                                padding: 0 5px;
                                background: #e53935;
                                color: #fff;
                                font-size: 10px;
                                font-weight: 600;
                                border-radius: 9px;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                border: 2px solid #fff;
                                line-height: 1;
                            ">{{ $notifCount }}</span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px; padding: 0; border-radius: 12px; border: 1px solid #eef0f3; box-shadow: 0 8px 24px rgba(15,23,42,0.08);">
                        <li style="padding: 12px 16px; border-bottom: 1px solid #f4f6f9;">
                            <span style="font-size: 13px; font-weight: 600; color: #1a2330;">Notifikasi</span>
                        </li>

                        @if(auth()->user()->role === 'admin')
                            @php
                                $pendingUsers = \App\Models\User::where('status', 'pending')->latest()->take(3)->get();
                                $pendingPickups = \App\Models\PickupRequest::where('status', 'pending')->latest()->take(3)->get();
                            @endphp

                            @forelse($pendingUsers as $u)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}" style="padding: 10px 16px; font-size: 13px;">
                                        <i class="bi bi-person-plus text-warning me-2"></i>
                                        <strong>{{ $u->name }}</strong> menunggu approval
                                    </a>
                                </li>
                            @empty
                            @endforelse

                            @forelse($pendingPickups as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.transactions.index') }}" style="padding: 10px 16px; font-size: 13px;">
                                        <i class="bi bi-box-seam text-info me-2"></i>
                                        Setoran <strong>{{ $p->user->name ?? 'Warga' }}</strong> menunggu
                                    </a>
                                </li>
                            @empty
                            @endforelse

                            @if($pendingUsers->isEmpty() && $pendingPickups->isEmpty())
                                <li style="padding: 20px 16px; text-align: center; font-size: 12px; color: #8a94a6;">
                                    Tidak ada notifikasi baru
                                </li>
                            @endif

                        @elseif(auth()->user()->role === 'mitra')
                            @php
                                $pendingPickups = \App\Models\PickupRequest::where('status', 'pending')
                                    ->whereNull('mitra_id')->latest()->take(5)->get();
                            @endphp

                            @forelse($pendingPickups as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('mitra.pickup-requests.show', $p->pickup_request_id) }}" style="padding: 10px 16px; font-size: 13px;">
                                        <i class="bi bi-truck text-success me-2"></i>
                                        Setoran baru dari <strong>{{ $p->user->name ?? 'Warga' }}</strong>
                                    </a>
                                </li>
                            @empty
                                <li style="padding: 20px 16px; text-align: center; font-size: 12px; color: #8a94a6;">
                                    Tidak ada setoran baru
                                </li>
                            @endforelse

                        @elseif(auth()->user()->role === 'warga')
                            @php
                                $myUpdates = \App\Models\PickupRequest::where('user_id', auth()->id())
                                    ->whereIn('status', ['completed', 'rejected'])
                                    ->latest('updated_at')->take(5)->get();
                            @endphp

                            @forelse($myUpdates as $p)
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.pickup-requests.show', $p->pickup_request_id) }}" style="padding: 10px 16px; font-size: 13px;">
                                        @if($p->status === 'completed')
                                            <i class="bi bi-check-circle text-success me-2"></i>
                                            Setoran <strong>{{ $p->wasteCategory->name ?? 'sampah' }}</strong> diverifikasi
                                        @else
                                            <i class="bi bi-x-circle text-danger me-2"></i>
                                            Setoran <strong>{{ $p->wasteCategory->name ?? 'sampah' }}</strong> ditolak
                                        @endif
                                    </a>
                                </li>
                            @empty
                                <li style="padding: 20px 16px; text-align: center; font-size: 12px; color: #8a94a6;">
                                    Tidak ada notifikasi baru
                                </li>
                            @endforelse
                        @endif

                        @if(auth()->user()->role === 'mitra')
                            <li style="padding: 10px 16px; text-align: center; border-top: 1px solid #f4f6f9; background: #fafbfc;">
                                <a href="{{ route('mitra.notifications.index') }}" class="text-success" style="font-size: 12px; font-weight: 600;">
                                    Lihat Semua Notifikasi →
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                {{-- USER --}}
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false"
                       style="padding: 6px 10px; gap: 10px;">
                        <div class="user-name text-end d-none d-md-block" style="line-height: 1.2;">
                            <h6 class="mb-0" style="font-size: 13px; font-weight: 600; color: #1a2330;">
                                {{ auth()->user()->name }}
                            </h6>
                            <p class="mb-0" style="font-size: 11px; color: #8a94a6;">
                                {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>

                        @php
                            $nameParts = explode(' ', trim(auth()->user()->name));
                            $initials = count($nameParts) >= 2
                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                : strtoupper(substr($nameParts[0], 0, 2));
                            $bgColor = match (auth()->user()->role) {
                                'admin' => '#1A237E',
                                'mitra' => '#2E7D32',
                                'warga' => '#4CAF50',
                                default => '#6C757D',
                            };
                        @endphp

                        <div style="
                            width: 38px;
                            height: 38px;
                            border-radius: 50%;
                            background: {{ $bgColor }};
                            color: #fff;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: 600;
                            font-size: 13px;
                            flex-shrink: 0;
                            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                        ">
                            {{ $initials }}
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 220px; padding: 6px 0; border-radius: 12px; border: 1px solid #eef0f3; box-shadow: 0 8px 24px rgba(15,23,42,0.08);">
                        <li style="padding: 12px 16px; border-bottom: 1px solid #f4f6f9;">
                            <div style="font-size: 13px; font-weight: 600; color: #1a2330;">
                                Hai, {{ auth()->user()->name }}
                            </div>
                            <div style="font-size: 11px; color: #8a94a6; margin-top: 2px;">
                                {{ auth()->user()->email }}
                            </div>
                        </li>

                        @if(auth()->user()->role == 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}" style="padding: 9px 16px; font-size: 13px;">
                                    <i class="bi bi-person me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.settings') }}" style="padding: 9px 16px; font-size: 13px;">
                                    <i class="bi bi-gear me-2"></i> Pengaturan
                                </a>
                            </li>
                        @elseif(auth()->user()->role == 'mitra')
                            <li>
                                <a class="dropdown-item" href="{{ route('mitra.profile') }}" style="padding: 9px 16px; font-size: 13px;">
                                    <i class="bi bi-person me-2"></i> Profil Mitra
                                </a>
                            </li>
                        @elseif(auth()->user()->role == 'warga')
                            <li>
                                <a class="dropdown-item" href="{{ route('user.dashboard') }}" style="padding: 9px 16px; font-size: 13px;">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                        @endif

                        <li><hr class="dropdown-divider mx-3 my-2"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="padding: 9px 16px; font-size: 13px;">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>