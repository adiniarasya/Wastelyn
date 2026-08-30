@extends('template.layout')

@section('title', 'Dashboard Admin - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 class="fw-bold">Dashboard</h3>
                    <p class="text-subtitle text-muted">Pantau aktivitas platform WasteLyn</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- STATISTIK UTAMA --}}
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-people-fill fs-2"></i>
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
                                <i class="bi bi-handshake fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalMitra ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Mitra</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
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
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-box-seam fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalSetoranKg ?? 0, 0, ',', '.') }} Kg</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Setoran</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRAFIK 7 HARI --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Grafik Setoran 7 Hari</h5>
                            <span class="badge bg-light text-dark">Update otomatis</span>
                        </div>
                        <div class="card-body">
                            <div style="position:relative; height:250px;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4 g-3">

                {{-- Aktivitas Terbaru --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                            <span class="badge bg-light text-dark">Real-time</span>
                        </div>
                        <div class="card-body">
                            @php
                                $activities = collect();
                                foreach ($recentTransactions ?? [] as $tx) {
                                    $activities->push([
                                        'text' => $tx->user->name ?? 'User' . ' ' . ($tx->type == 'earn' ? 'mendapat' : 'menukarkan') . ' ' . number_format($tx->points) . ' poin',
                                        'time' => $tx->created_at->diffForHumans(),
                                    ]);
                                }
                                foreach ($recentPickups ?? [] as $pickup) {
                                    $activities->push([
                                        'text' => ($pickup->user->name ?? 'User') . ' melakukan pickup ' . $pickup->status,
                                        'time' => $pickup->created_at->diffForHumans(),
                                    ]);
                                }
                                $activities = $activities->sortByDesc('time')->take(10);
                            @endphp

                            @forelse($activities as $activity)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span>{{ $activity['text'] }}</span>
                                    <small class="text-secondary">{{ $activity['time'] }}</small>
                                </div>
                            @empty
                                <p class="text-center text-secondary py-4">Belum ada aktivitas</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Pending Approval --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">⏳ Pending Approval</h5>
                        </div>
                        <div class="card-body d-flex flex-column gap-3">
                            <div
                                class="d-flex justify-content-between align-items-center p-3 bg-warning bg-opacity-10 rounded-3">
                                <span class="fw-semibold">Mitra</span>
                                <span class="badge bg-warning rounded-pill fs-6 px-3 py-2">{{ $pendingMitra ?? 0 }}</span>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center p-3 bg-primary bg-opacity-10 rounded-3">
                                <span class="fw-semibold">Setoran</span>
                                <span class="badge bg-primary rounded-pill fs-6 px-3 py-2">{{ $pendingSetoran ?? 0 }}</span>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center p-3 bg-success bg-opacity-10 rounded-3">
                                <span class="fw-semibold">Reward</span>
                                <span class="badge bg-success rounded-pill fs-6 px-3 py-2">{{ $pendingReward ?? 0 }}</span>
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
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels);
            const data = @json($chartData);

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