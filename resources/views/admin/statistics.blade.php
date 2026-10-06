@extends('template.layout')

@section('title', 'Statistik - WasteLyn')

@push('styles')
    <style>
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 1;
        }

        .btn-icon svg {
            display: block;
            pointer-events: none;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            display: block;
        }

        .badge-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-icon svg {
            display: block;
        }

        .table-clean thead th {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6c757d;
            background: #f8f9fa;
            padding: 12px 14px;
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .table-clean tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-clean tbody tr:last-child td {
            border-bottom: 0;
        }

        .chart-wrapper {
            position: relative;
            height: 260px;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Statistik</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Data visual dan analitik platform WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#E7F1FF;color:#435EBE;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalUsers ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total User</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#E6F7EE;color:#2E7D32;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 1 21 5 17 9" />
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                                    <polyline points="7 23 3 19 7 15" />
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalTransactions ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#FFF4E0;color:#D97706;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13" />
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                    <circle cx="5.5" cy="18.5" r="2.5" />
                                    <circle cx="18.5" cy="18.5" r="2.5" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalPickups ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Pickup</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#E0F5FA;color:#0891B2;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 12 20 22 4 22 4 12" />
                                    <rect x="2" y="7" width="20" height="5" />
                                    <line x1="12" y1="22" x2="12" y2="7" />
                                    <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                                    <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalRewards ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Reward</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#FFEBEE;color:#C62828;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <circle cx="12" cy="12" r="6" />
                                    <circle cx="12" cy="12" r="2" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalMissions ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Misi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#F3E8FF;color:#7E22CE;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalWasteBanks ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Bank Sampah</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#E0F2F1;color:#0F766E;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalPoints ?? 0, 0, ',', '.') }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Poin</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:#F1F3F5;color:#495057;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalWarga ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Warga Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4 g-3">
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:inline-block;vertical-align:-2px;margin-right:6px;" class="text-success">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                </svg>
                                Transaksi 7 Hari Terakhir
                            </h5>
                            <span class="badge bg-light text-muted fw-normal">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:inline-block;vertical-align:-1px;margin-right:4px;">
                                    <polyline points="23 4 23 10 17 10" />
                                    <polyline points="1 20 1 14 7 14" />
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                    <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                </svg>
                                Update otomatis
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="chart-wrapper">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:inline-block;vertical-align:-2px;margin-right:6px;" class="text-success">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                Komposisi User
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column gap-3 pt-1">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#E7F1FF;">
                                <span class="fw-semibold" style="color:#435EBE;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        style="display:inline-block;vertical-align:-2px;margin-right:6px;">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    Warga
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#435EBE;color:#fff;">
                                    {{ number_format($totalWarga ?? 0) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#E6F7EE;">
                                <span class="fw-semibold" style="color:#2E7D32;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        style="display:inline-block;vertical-align:-2px;margin-right:6px;">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                    </svg>
                                    Mitra
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#2E7D32;color:#fff;">
                                    {{ number_format($totalMitra ?? 0) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#FFEBEE;">
                                <span class="fw-semibold" style="color:#C62828;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        style="display:inline-block;vertical-align:-2px;margin-right:6px;">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    Admin
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#C62828;color:#fff;">
                                    {{ number_format($totalAdmin ?? 0) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border-top pt-3 mt-1"
                                style="background:#F1F3F5;">
                                <span class="fw-semibold text-dark">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        style="display:inline-block;vertical-align:-2px;margin-right:6px;">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    Total User
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#495057;color:#fff;">
                                    {{ number_format($totalUsers ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="display:inline-block;vertical-align:-2px;margin-right:6px;" class="text-success">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                User Terbaru
                            </h5>
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-2">
                                Lihat Semua
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </a>
                        </div>
                        <div class="card-body pt-3">
                            <div class="table-responsive">
                                <table class="table table-clean table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:60px;">No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th class="text-center">Role</th>
                                            <th>Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentUsers ?? [] as $user)
                                            <tr>
                                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                                </td>
                                                <td class="text-muted small">{{ $user->email }}</td>
                                                <td class="text-center">
                                                    @if($user->role === 'admin')
                                                        <span class="badge bg-danger">Admin</span>
                                                    @elseif($user->role === 'mitra')
                                                        <span class="badge bg-success">Mitra</span>
                                                    @else
                                                        <span class="badge bg-primary">Warga</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5">
                                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" class="mb-3"
                                                        style="opacity:.4;display:block;margin:0 auto;">
                                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                        <circle cx="9" cy="7" r="4" />
                                                    </svg>
                                                    <div class="mt-2">Belum ada user</div>
                                                </td>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('transactionChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const labels = @json($chartLabels ?? []);
            const data = @json($chartData ?? []);

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
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return ' ' + context.parsed.y + ' transaksi';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 12 } },
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
                        },
                        x: {
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });
        });
    </script>
@endpush