@extends('template.layout')

@section('title', 'Dashboard Mitra - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 class="fw-bold">Dashboard Mitra</h3>
                    <p class="text-subtitle text-muted">Pantau aktivitas bank sampah Anda</p>
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
                                <i class="bi bi-box-seam fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalPickups ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Setoran</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-weight-scale fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalBerat ?? 0, 0, ',', '.') }} Kg</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Berat</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-cash-stack fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Pendapatan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-people fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">{{ number_format($totalNasabah ?? 0) }}</div>
                                <div class="text-uppercase small fw-semibold text-secondary">Total Nasabah</div>
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

                {{-- Permintaan Masuk (Pending) --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">⏳ Permintaan Masuk</h5>
                            <span class="badge bg-warning rounded-pill">{{ count($pendingPickups ?? []) }}</span>
                        </div>
                        <div class="card-body">
                            @forelse($pendingPickups ?? [] as $pickup)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <span class="fw-semibold">{{ $pickup->user->name ?? 'User' }}</span>
                                        <div class="small text-secondary">
                                            {{ $pickup->jenis_sampah ?? 'Belum ditentukan' }} • 
                                            {{ $pickup->estimasi_berat ?? 0 }}kg
                                        </div>
                                    </div>
                                    <a href="{{ route('mitra.detail-setoran', $pickup->pickup_request_id) }}" class="btn btn-sm btn-primary">
                                        Proses
                                    </a>
                                </div>
                            @empty
                                <p class="text-center text-secondary py-4">Tidak ada permintaan masuk</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Aktivitas Terbaru --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Riwayat Setoran Terbaru</h5>
                            <span class="badge bg-light text-dark">Real-time</span>
                        </div>
                        <div class="card-body">
                            @forelse($recentPickups ?? [] as $pickup)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <span class="fw-semibold">{{ $pickup->user->name ?? 'User' }}</span>
                                        <span class="badge bg-{{ $pickup->status == 'completed' ? 'success' : ($pickup->status == 'accepted' ? 'warning' : 'secondary') }} ms-2">
                                            {{ ucfirst($pickup->status) }}
                                        </span>
                                        <div class="small text-secondary">
                                            {{ $pickup->jenis_sampah ?? 'Belum ditentukan' }} • 
                                            {{ $pickup->berat_aktual ?? $pickup->estimasi_berat ?? 0 }}kg
                                            @if($pickup->total_harga)
                                                • Rp {{ number_format($pickup->total_harga, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                    <small class="text-secondary">{{ $pickup->created_at->diffForHumans() }}</small>
                                </div>
                            @empty
                                <p class="text-center text-secondary py-4">Belum ada riwayat setoran</p>
                            @endforelse
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
            const ctx = document.getElementById('transactionChart').getContext('2d');
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