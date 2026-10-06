@extends('template.layout')

@section('title', 'Dashboard Admin - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Dashboard</h3>
                    <p class="text-subtitle text-muted mb-0">Pantau aktivitas platform WasteLyn</p>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:56px;height:56px;flex-shrink:0;background:#E7F1FF;color:#435EBE;">
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
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:56px;height:56px;flex-shrink:0;background:#E6F7EE;color:#2E7D32;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalMitra ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Total Mitra</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:56px;height:56px;flex-shrink:0;background:#FFF4E0;color:#D97706;">
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
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width:56px;height:56px;flex-shrink:0;background:#E0F5FA;color:#0891B2;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                    <line x1="12" y1="22.08" x2="12" y2="12" />
                                </svg>
                            </div>
                            <div>
                                <div class="fs-3 fw-bold lh-1">{{ number_format($totalSetoranKg ?? 0, 0, ',', '.') }} Kg
                                </div>
                                <div class="text-uppercase small fw-semibold text-muted mt-1">Setoran</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                                    <line x1="18" y1="20" x2="18" y2="10" />
                                    <line x1="12" y1="20" x2="12" y2="4" />
                                    <line x1="6" y1="20" x2="6" y2="14" />
                                </svg>
                                Grafik Setoran 7 Hari
                            </h5>
                            <span class="badge bg-light text-muted fw-normal d-flex align-items-center gap-1">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 4 23 10 17 10" />
                                    <polyline points="1 20 1 14 7 14" />
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                    <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                </svg>
                                Update otomatis
                            </span>
                        </div>
                        <div class="card-body">
                            <div style="position:relative; height:260px;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4 g-3">

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Aktivitas Terbaru
                            </h5>
                            <span class="badge bg-light text-muted fw-normal d-flex align-items-center gap-1">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="2" />
                                    <path
                                        d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14" />
                                </svg>
                                Real-time
                            </span>
                        </div>
                        <div class="card-body pt-2">
                            @php
                                $activities = collect();

                                foreach ($recentTransactions ?? [] as $tx) {
                                    $activities->push([
                                        'text' => ($tx->user->name ?? 'User') . ' ' .
                                            ($tx->type == 'earn' ? 'mendapat' : 'menukarkan') . ' ' .
                                            number_format($tx->points ?? 0) . ' poin',
                                        'icon' => $tx->type == 'earn' ? 'bi-arrow-down' : 'bi-arrow-up',
                                        'color' => $tx->type == 'earn' ? 'success' : 'warning',
                                        'svg' => $tx->type == 'earn'
                                            ? '<circle cx="12" cy="12" r="10"/><polyline points="8 12 12 16 16 12"/><line x1="12" y1="8" x2="12" y2="16"/>'
                                            : '<circle cx="12" cy="12" r="10"/><polyline points="16 12 12 8 8 12"/><line x1="12" y1="16" x2="12" y2="8"/>',
                                        'created_at' => $tx->created_at,
                                    ]);
                                }

                                foreach ($recentPickups ?? [] as $pickup) {
                                    $activities->push([
                                        'text' => ($pickup->user->name ?? 'User') . ' melakukan pickup ' . ($pickup->status ?? '-'),
                                        'color' => 'primary',
                                        'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                                        'created_at' => $pickup->created_at,
                                    ]);
                                }

                                $activities = $activities->sortByDesc('created_at')->take(8);
                            @endphp

                            @forelse($activities as $activity)
                                <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:38px;height:38px;flex-shrink:0;background:var(--bs-{{ $activity['color'] }}-bg-subtle);color:var(--bs-{{ $activity['color'] }});">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $activity['svg'] !!}
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-dark small fw-medium">{{ $activity['text'] }}</div>
                                        <small class="text-muted" style="font-size:11px;">
                                            {{ $activity['created_at']?->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                    </svg>
                                    <div class="mt-2 small">Belum ada aktivitas</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Pending Approval
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column gap-3 pt-2">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#FFF4E0;">
                                <span class="fw-semibold d-flex align-items-center gap-2" style="color:#D97706;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <polyline points="17 11 19 13 23 9" />
                                    </svg>
                                    Mitra
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#D97706;color:#fff;">
                                    {{ $pendingMitra ?? 0 }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#E7F1FF;">
                                <span class="fw-semibold d-flex align-items-center gap-2" style="color:#435EBE;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="3" width="15" height="13" />
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                        <circle cx="5.5" cy="18.5" r="2.5" />
                                        <circle cx="18.5" cy="18.5" r="2.5" />
                                    </svg>
                                    Setoran
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#435EBE;color:#fff;">
                                    {{ $pendingSetoran ?? 0 }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                style="background:#E6F7EE;">
                                <span class="fw-semibold d-flex align-items-center gap-2" style="color:#2E7D32;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 12 20 22 4 22 4 12" />
                                        <rect x="2" y="7" width="20" height="5" />
                                        <line x1="12" y1="22" x2="12" y2="7" />
                                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                                    </svg>
                                    Reward
                                </span>
                                <span class="badge rounded-pill px-3 py-2" style="background:#2E7D32;color:#fff;">
                                    {{ $pendingReward ?? 0 }}
                                </span>
                            </div>

                            <a href="#"
                                class="btn btn-outline-success btn-sm w-100 mt-auto d-flex align-items-center justify-content-center gap-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 16 16 12 12 8" />
                                    <line x1="8" y1="12" x2="16" y2="12" />
                                </svg>
                                Lihat Semua Approval
                            </a>
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
                        label: 'Setoran (Kg)',
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
                                    return ' ' + context.parsed.y + ' Kg';
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