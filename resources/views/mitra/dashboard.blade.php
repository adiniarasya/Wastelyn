@extends('template.layout')

@section('title', 'Dashboard Mitra - WasteLyn')

@section('content')
<div class="page-heading">
    <div class="page-title mb-3">
        <h4 class="fw-bold mb-1">Dashboard</h4>
        <p class="text-muted small mb-0">Pantau aktivitas bank sampah Anda</p>
    </div>

    <section class="section">

        {{-- ===== STATISTIK UTAMA ===== --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <span class="badge bg-light text-secondary fw-normal">All time</span>
                        </div>
                        <div class="stat-value">{{ number_format($totalPickups ?? 0) }}</div>
                        <div class="stat-label">Total Setoran</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-weight-scale"></i>
                            </div>
                            <span class="badge bg-light text-secondary fw-normal">All time</span>
                        </div>
                        <div class="stat-value">{{ number_format($totalBerat ?? 0, 0, ',', '.') }}<small class="fs-6 fw-normal text-muted ms-1">kg</small></div>
                        <div class="stat-label">Total Berat</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <span class="badge bg-light text-secondary fw-normal">All time</span>
                        </div>
                        <div class="stat-value">
                            <span class="fs-6 fw-normal text-muted">Rp</span>
                            {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people"></i>
                            </div>
                            <span class="badge bg-light text-secondary fw-normal">All time</span>
                        </div>
                        <div class="stat-value">{{ number_format($totalNasabah ?? 0) }}</div>
                        <div class="stat-label">Total Nasabah</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== PERBANDINGAN BULAN ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small mb-1">Pendapatan Bulan Ini</div>
                                <div class="fs-3 fw-bold text-dark mb-1">
                                    <span class="fs-5 fw-normal text-muted">Rp</span>
                                    {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                                </div>
                                <div class="small text-muted">
                                    Bulan lalu: Rp {{ number_format($pendapatanBulanLalu ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            @php $isUp = ($persenPendapatan ?? 0) >= 0; @endphp
                            <div class="trend-badge {{ $isUp ? 'trend-up' : 'trend-down' }}">
                                <i class="bi bi-arrow-{{ $isUp ? 'up' : 'down' }}-right"></i>
                                {{ number_format(abs($persenPendapatan ?? 0), 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small mb-1">Setoran Bulan Ini</div>
                                <div class="fs-3 fw-bold text-dark mb-1">
                                    {{ number_format($setoranBulanIni ?? 0) }}
                                    <small class="fs-6 fw-normal text-muted">transaksi</small>
                                </div>
                                <div class="small text-muted">
                                    Bulan lalu: {{ number_format($setoranBulanLalu ?? 0) }} transaksi
                                </div>
                            </div>
                            @php $isUpSetoran = ($persenSetoran ?? 0) >= 0; @endphp
                            <div class="trend-badge {{ $isUpSetoran ? 'trend-up' : 'trend-down' }}">
                                <i class="bi bi-arrow-{{ $isUpSetoran ? 'up' : 'down' }}-right"></i>
                                {{ number_format(abs($persenSetoran ?? 0), 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== GRAFIK ===== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Setoran 7 Hari Terakhir</h6>
                            <small class="text-muted">Berat (kg)</small>
                        </div>
                        <div style="position:relative; height:240px;">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Pendapatan 12 Bulan</h6>
                            <small class="text-muted">Rupiah</small>
                        </div>
                        <div style="position:relative; height:240px;">
                            <canvas id="pendapatanChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== PERMINTAAN & RIWAYAT ===== --}}
        <div class="row g-3">

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Permintaan Masuk</h6>
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">{{ count($pendingPickups ?? []) }}</span>
                        </div>
                        @forelse($pendingPickups ?? [] as $pickup)
                            <div class="list-item-clean">
                                <div class="flex-grow-1">
                                    <div class="fw-medium small">{{ $pickup->user->name ?? 'User' }}</div>
                                    <div class="text-muted" style="font-size: 12px;">
                                        {{ $pickup->wasteCategory->name ?? 'Belum ditentukan' }} · {{ $pickup->estimasi_berat ?? 0 }}kg
                                    </div>
                                </div>
                                <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}"
                                   class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    Proses
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 32px;"></i>
                                <p class="text-muted small mb-0 mt-2">Tidak ada permintaan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Riwayat Setoran Terbaru</h6>
                        </div>
                        @forelse($recentPickups ?? [] as $pickup)
                            <div class="list-item-clean">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium small">{{ $pickup->user->name ?? 'User' }}</span>
                                        <span class="status-dot {{ $pickup->status === 'completed' ? 'bg-success' : ($pickup->status === 'accepted' ? 'bg-warning' : 'bg-secondary') }}"></span>
                                        <small class="text-muted">{{ ucfirst($pickup->status) }}</small>
                                    </div>
                                    <div class="text-muted" style="font-size: 12px;">
                                        {{ $pickup->wasteCategory->name ?? 'Belum ditentukan' }} · {{ $pickup->berat_aktual ?? $pickup->estimasi_berat ?? 0 }}kg
                                        @if($pickup->total_harga)
                                            · Rp {{ number_format($pickup->total_harga, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <small class="text-muted">{{ $pickup->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-clock-history text-muted" style="font-size: 32px;"></i>
                                <p class="text-muted small mb-0 mt-2">Belum ada riwayat</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </section>
</div>
@endsection

@push('styles')
<style>
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1a2330;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        color: #6b7a8c;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    .trend-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 2px;
    }
    .trend-up {
        background: #e8f5e9;
        color: #2E7D32;
    }
    .trend-down {
        background: #ffebee;
        color: #c62828;
    }
    .list-item-clean {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }
    .list-item-clean:last-child {
        border-bottom: none;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .card {
        transition: box-shadow 0.2s;
    }
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart Setoran 7 Hari
        new Chart(document.getElementById('transactionChart'), {
            type: 'bar',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    data: @json($chartData ?? []),
                    backgroundColor: '#2E7D32',
                    borderRadius: 6,
                    barPercentage: 0.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 11 }, color: '#8a94a6' },
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#8a94a6' }
                    }
                }
            }
        });

        // Chart Pendapatan 12 Bulan
        new Chart(document.getElementById('pendapatanChart'), {
            type: 'line',
            data: {
                labels: @json($pendapatanLabels ?? []),
                datasets: [{
                    data: @json($pendapatanData ?? []),
                    borderColor: '#FF9800',
                    backgroundColor: 'rgba(255, 152, 0, 0.08)',
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#FF9800',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 11 }, color: '#8a94a6',
                            callback: (v) => v >= 1000 ? (v/1000) + 'k' : v
                        },
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 }, color: '#8a94a6' }
                    }
                }
            }
        });
    });
</script>
@endpush