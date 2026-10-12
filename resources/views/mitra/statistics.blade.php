@extends('template.layout')

@section('title', 'Dashboard Statistik - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Dashboard Statistik</h4>
            <p class="text-muted small mb-0">Ringkasan aktivitas setoran sampah</p>
        </div>
    </div>

    {{-- Filter Periode --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted">Periode</label>
                <select name="periode" class="form-select form-select-sm">
                    <option value="all" @selected($periode === 'all')>Semua Waktu</option>
                    @foreach ($listPeriode as $val => $label)
                        <option value="{{ $val }}" @selected($periode === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-sm w-100">Terapkan</button>
            </div>
        </div>
    </form>

    {{-- Ringkasan Total --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Berat</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalSemuaBerat, 1, ',', '.') }} <span class="fs-6 fw-normal text-muted">Kg</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Transaksi</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalSemuaTransaksi) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Poin</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalSemuaPoin) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pendapatan</div>
                    <div class="fs-4 fw-bold text-success">
                        Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Per Jenis Sampah --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Setoran per Jenis Sampah</h6>
            <div style="position:relative; height:300px;">
                <canvas id="chartJenis"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Per Jenis Sampah --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body pb-0">
            <h6 class="fw-bold mb-3">Detail per Jenis Sampah</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kategori</th>
                        <th class="text-end">Total Berat</th>
                        <th class="text-end">Transaksi</th>
                        <th class="text-end">Poin</th>
                        <th class="text-end pe-4">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sampahPerJenis as $item)
                        <tr>
                            <td class="ps-4">{{ $item->wasteCategory->name ?? 'Tidak diketahui' }}</td>
                            <td class="text-end">{{ number_format($item->total_berat, 1, ',', '.') }} kg</td>
                            <td class="text-end">{{ number_format($item->total_transaksi) }}</td>
                            <td class="text-end">+{{ number_format($item->total_poin) }}</td>
                            <td class="text-end pe-4">
                                Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                Belum ada data setoran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($sampahPerJenis->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th class="ps-4">Total</th>
                            <th class="text-end">{{ number_format($totalSemuaBerat, 1, ',', '.') }} kg</th>
                            <th class="text-end">{{ number_format($totalSemuaTransaksi) }}</th>
                            <th class="text-end">+{{ number_format($totalSemuaPoin) }}</th>
                            <th class="text-end pe-4">Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Statistik Status --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Menunggu</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Diambil Mitra</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['accepted'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Selesai</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['completed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Ditolak</div>
                    <div class="fs-4 fw-bold">{{ $statusStats['rejected'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Bulanan --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Setoran 12 Bulan Terakhir</h6>
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