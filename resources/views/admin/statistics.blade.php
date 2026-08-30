@extends('template.layout')

@section('title', 'Statistik - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 class="fw-bold">Statistik</h3>
                    <p class="text-subtitle text-muted">Data visual dan analitik platform WasteLyn</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Statistik</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- 1. STATISTIK UTAMA --}}
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-people fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalUsers ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total User</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-arrow-left-right fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalTransactions ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-truck fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalPickups ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Pickup</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-gift fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalRewards ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Reward</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. STATISTIK KEDUA --}}
            <div class="row g-3 mt-2">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                                <i class="bi bi-bullseye fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalMissions ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Misi</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-purple bg-opacity-10 text-purple rounded-3 p-3">
                                <i class="bi bi-shop fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalWasteBanks ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Bank Sampah</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-teal bg-opacity-10 text-teal rounded-3 p-3">
                                <i class="bi bi-coin fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalPoints ?? 0, 0, ',', '.') }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Poin</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-3">
                                <i class="bi bi-person fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalWarga ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Warga Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. GRAFIK TRANSAKSI 7 HARI --}}
            <div class="row mt-4">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">📈 Transaksi 7 Hari Terakhir</h5>
                            <span class="badge bg-light text-dark">Update otomatis</span>
                        </div>
                        <div class="card-body">
                            <div style="position:relative; height:250px;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. KOMPOSISI USER --}}
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Komposisi User</h5>
                        </div>
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center p-3 bg-primary bg-opacity-10 rounded-3">
                                <span class="fw-semibold"><i class="bi bi-person me-2"></i>Warga</span>
                                <span class="badge bg-primary rounded-pill fs-6 px-3 py-2">{{ number_format($totalWarga ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 bg-success bg-opacity-10 rounded-3">
                                <span class="fw-semibold"><i class="bi bi-handshake me-2"></i>Mitra</span>
                                <span class="badge bg-success rounded-pill fs-6 px-3 py-2">{{ number_format($totalMitra ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 bg-danger bg-opacity-10 rounded-3">
                                <span class="fw-semibold"><i class="bi bi-shield-lock me-2"></i>Admin</span>
                                <span class="badge bg-danger rounded-pill fs-6 px-3 py-2">{{ number_format($totalAdmin ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 bg-secondary bg-opacity-10 rounded-3">
                                <span class="fw-semibold"><i class="bi bi-people me-2"></i>Total User</span>
                                <span class="badge bg-secondary rounded-pill fs-6 px-3 py-2">{{ number_format($totalUsers ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. USER TERBARU --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">User Terbaru</h5>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentUsers ?? [] as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge 
                                                                @if($user->role == 'admin') bg-danger
                                                                @elseif($user->role == 'mitra') bg-success
                                                                @else bg-primary @endif">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Belum ada user</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transactionChart').getContext('2d');
            const labels = @json($chartLabels);
            const data = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transaksi',
                        data: data,
                        backgroundColor: 'rgba(46, 125, 50, 0.6)',
                        borderColor: '#2E7D32',
                        borderWidth: 2,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#2E7D32',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            cornerRadius: 8,
                            padding: 10,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 12 } },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endpush