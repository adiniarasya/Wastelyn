@extends('template.layout')

@section('title', 'Dashboard Admin - WasteLyn')

@php
    // Format angka kg: hilangkan nol desimal yang tidak perlu (12,50 -> 12,5 ; 12,00 -> 12)
    $formatKg = fn($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');

    $stats = [
        [
            'label' => 'Total User',
            'value' => number_format($totalUsers ?? 0, 0, ',', '.'),
            'bg' => '#E7F1FF',
            'fg' => '#435EBE',
            'svg' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        ],
        [
            'label' => 'Total Mitra',
            'value' => number_format($totalMitra ?? 0, 0, ',', '.'),
            'bg' => '#E6F7EE',
            'fg' => '#2E7D32',
            'svg' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        ],
        [
            'label' => 'Total Misi',
            'value' => number_format($totalMissions ?? 0, 0, ',', '.'),
            'bg' => '#FFF4E0',
            'fg' => '#D97706',
            'svg' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        ],
        [
            'label' => 'Setoran',
            'value' => $formatKg($totalSetoranKg ?? 0) . ' Kg',
            'bg' => '#E0F5FA',
            'fg' => '#0891B2',
            'svg' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
        ],
    ];

    $pendings = [
        [
            'label' => 'Mitra',
            'count' => $pendingMitra ?? 0,
            'bg' => '#FFF4E0',
            'fg' => '#D97706',
            'svg' => '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/>',
        ],
        [
            'label' => 'Setoran',
            'count' => $pendingSetoran ?? 0,
            'bg' => '#E7F1FF',
            'fg' => '#435EBE',
            'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
        ],
        [
            'label' => 'Reward',
            'count' => $pendingReward ?? 0,
            'bg' => '#E6F7EE',
            'fg' => '#2E7D32',
            'svg' => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
        ],
    ];

    // Warna eksplisit (tidak bergantung pada --bs-*-bg-subtle yang hanya ada di Bootstrap 5.3+)
    $activityColors = [
        'success' => ['bg' => '#E6F7EE', 'fg' => '#2E7D32'],
        'warning' => ['bg' => '#FFF4E0', 'fg' => '#D97706'],
        'primary' => ['bg' => '#E7F1FF', 'fg' => '#435EBE'],
    ];

    $activities = collect();

    foreach ($recentTransactions ?? [] as $tx) {
        $isEarn = $tx->type === 'earn';
        $activities->push([
            'text' => ($tx->user->name ?? 'User') . ' ' . ($isEarn ? 'mendapat' : 'menukarkan') . ' ' . number_format($tx->points ?? 0, 0, ',', '.') . ' poin',
            'color' => $isEarn ? 'success' : 'warning',
            'svg' => $isEarn
                ? '<circle cx="12" cy="12" r="10"/><polyline points="8 12 12 16 16 12"/><line x1="12" y1="8" x2="12" y2="16"/>'
                : '<circle cx="12" cy="12" r="10"/><polyline points="16 12 12 8 8 12"/><line x1="12" y1="16" x2="12" y2="8"/>',
            'created_at' => $tx->created_at,
        ]);
    }

    foreach ($recentPickups ?? [] as $pickup) {
        $activities->push([
            'text' => ($pickup->user->name ?? 'User') . ' melakukan pickup (' . ($pickup->status ?? '-') . ')',
            'color' => 'primary',
            'svg' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
            'created_at' => $pickup->created_at,
        ]);
    }

    $activities = $activities->filter(fn($a) => !empty($a['created_at']))->sortByDesc('created_at')->take(8);
@endphp

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

            {{-- Statistik --}}
            <div class="row g-3">
                @foreach ($stats as $stat)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                    style="width:56px;height:56px;flex-shrink:0;background:{{ $stat['bg'] }};color:{{ $stat['fg'] }};">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $stat['svg'] !!}
                                    </svg>
                                </div>
                                <div>
                                    <div class="fs-3 fw-bold lh-1">{{ $stat['value'] }}</div>
                                    <div class="text-uppercase small fw-semibold text-muted mt-1">{{ $stat['label'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Grafik --}}
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

                {{-- Aktivitas terbaru --}}
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
                        </div>
                        <div class="card-body pt-2">
                            @forelse($activities as $activity)
                                @php $c = $activityColors[$activity['color']] ?? $activityColors['primary']; @endphp
                                <div class="d-flex align-items-center gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:38px;height:38px;flex-shrink:0;background:{{ $c['bg'] }};color:{{ $c['fg'] }};">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $activity['svg'] !!}
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-dark small fw-medium">{{ $activity['text'] }}</div>
                                        <small class="text-muted" style="font-size:11px;">
                                            {{ \Illuminate\Support\Carbon::parse($activity['created_at'])->diffForHumans() }}
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

                {{-- Pending approval --}}
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
                            @foreach ($pendings as $p)
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                                    style="background:{{ $p['bg'] }};">
                                    <span class="fw-semibold d-flex align-items-center gap-2" style="color:{{ $p['fg'] }};">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $p['svg'] !!}
                                        </svg>
                                        {{ $p['label'] }}
                                    </span>
                                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $p['fg'] }};color:#fff;">
                                        {{ $p['count'] }}
                                    </span>
                                </div>
                            @endforeach

                            {{-- TODO: ganti '#' dengan route halaman approval, mis. route('admin.approvals.index') --}}
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
            if (!canvas || typeof Chart === 'undefined') return;

            const labels = @json($chartLabels ?? []);
            const data = @json($chartData ?? []);

            new Chart(canvas.getContext('2d'), {
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
                                    return ' ' + context.parsed.y.toLocaleString('id-ID') + ' Kg';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 12 } },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endpush