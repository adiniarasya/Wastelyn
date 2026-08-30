<nav class="navbar navbar-expand navbar-light">
    <div class="container-fluid">

        {{-- Toggle Sidebar --}}
        <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
        </a>

        {{-- Navbar Toggler Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                {{-- Notifikasi --}}
                <li class="nav-item dropdown me-1">
                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell bi-sub fs-4 text-gray-600"></i>
                        <span class="badge bg-danger badge-sm">{{ $unreadNotifications ?? 0 }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">Notifikasi</h6>
                        </li>
                        @forelse($notifications ?? [] as $notif)
                            <li>
                                <a class="dropdown-item" href="#">
                                    {{ $notif->message ?? 'Notifikasi baru' }}
                                </a>
                            </li>
                        @empty
                            <li><a class="dropdown-item" href="#">Belum ada notifikasi</a></li>
                        @endforelse
                    </ul>
                </li>

            </ul>

            {{-- Profil User --}}
            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-menu d-flex">
                        <div class="user-name text-end me-3">
                            <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                            <p class="mb-0 text-sm text-gray-600">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                        <div class="user-img d-flex align-items-center">
                            <div class="avatar avatar-md">
                                <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/images/faces/1.jpg') }}"
                                    alt="Profile Photo">
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                    </li>

                    {{-- Menu Profil (sesuai role) --}}
                    @if(auth()->user()->role == 'admin')
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.settings') }}">
                                <i class="icon-mid bi bi-gear me-2"></i> Pengaturan
                            </a>
                        </li>
                    @elseif(auth()->user()->role == 'mitra')
                        <li>
                            <a class="dropdown-item" href="{{ route('mitra.profile') }}">
                                <i class="icon-mid bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item" href="{{ route('user.profile') }}">
                                <i class="icon-mid bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                    @endif

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- Logout --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</nav>