@extends('template.layout')

@section('title', 'Dashboard Statistik')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-graph-up"></i> Dashboard Statistik</h3>
    </div>

    {{-- Filter Periode --}}
    <div class="card card-stat mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <select name="periode" class="form-select">
                        <option value="all" @selected($periode === 'all')>Semua Waktu</option>
                        @foreach ($listPeriode as $val => $label)
                            <option value="{{ $val }}" @selected($periode === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Total --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Berat</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalSemuaBerat, 1, ',', '.') }} Kg</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalSemuaTransaksi) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Poin</div>
                    <div class="fs-3 fw-bold text-warning">{{ number_format($totalSemuaPoin) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Pendapatan</div>
                    <div class="fs-3 fw-bold text-success">
                        Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Per Jenis Sampah --}}
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-bar-chart"></i> Grafik Setoran per Jenis Sampah
        </div>
        <div class="card-body">
            <div style="position:relative; height:300px;">
                <canvas id="chartJenis"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Per Jenis Sampah --}}
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-list-ul"></i> Detail per Jenis Sampah
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th class="text-end">Total Berat (Kg)</th>
                            <th class="text-end">Transaksi</th>
                            <th class="text-end">Poin</th>
                            <th class="text-end">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sampahPerJenis as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->wasteCategory->name ?? 'Tidak diketahui' }}</strong>
                                </td>
                                <td class="text-end">{{ number_format($item->total_berat, 1, ',', '.') }} kg</td>
                                <td class="text-end">{{ number_format($item->total_transaksi) }}</td>
                                <td class="text-end">+{{ number_format($item->total_poin) }}</td>
                                <td class="text-end">
                                    Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada data setoran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($sampahPerJenis->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th class="text-end">Total</th>
                                <th class="text-end">{{ number_format($totalSemuaBerat, 1, ',', '.') }} kg</th>
                                <th class="text-end">{{ number_format($totalSemuaTransaksi) }}</th>
                                <th class="text-end">+{{ number_format($totalSemuaPoin) }}</th>
                                <th class="text-end">Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Statistik Status --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Menunggu</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Diambil Mitra</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['accepted'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Selesai</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['completed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Ditolak</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['rejected'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Bulanan --}}
    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-graph-up"></i> Grafik Setoran 12 Bulan Terakhir
        </div>
        <div class="card-body">
            <div style="position:relative; height:250px;">
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // GRAFIK PER JENIS SAMPAH
            const ctxJenis = document.getElementById('chartJenis').getContext('2d');
            const labelsJenis = @json($sampahPerJenis->pluck('wasteCategory.name')->map(fn($n) => $n ?? 'Lainnya'));
            const dataJenis = @json($sampahPerJenis->pluck('total_berat'));

            new Chart(ctxJenis, {
                type: 'bar',
                data: {
                    labels: labelsJenis,
                    datasets: [{
                        label: 'Total Berat (Kg)',
                        data: dataJenis,
                        backgroundColor: 'rgba(46, 125, 50, 0.7)',
                        borderColor: '#2E7D32',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { grid: { display: false } }
                    }
                }
            });

            // GRAFIK BULANAN
            const ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
            const labelsBulanan = @json($bulanLabels);
            const dataBulanan = @json($bulanData);

            new Chart(ctxBulanan, {
                type: 'line',
                data: {
                    labels: labelsBulanan,
                    datasets: [{
                        label: 'Setoran (Kg)',
                        data: dataBulanan,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#2E7D32',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
@endpush