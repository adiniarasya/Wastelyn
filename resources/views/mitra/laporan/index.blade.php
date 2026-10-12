@extends('template.layout')

@section('title', 'Laporan Bulanan - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Bulanan</h4>
            <p class="text-muted small mb-0">Rekap setoran sampah per bulan</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline-success btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>

    {{-- Filter Bulan & Tahun --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted">Bulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    @foreach ($listBulan as $num => $nama)
                        <option value="{{ $num }}" @selected($bulan == $num)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Tahun</label>
                <select name="tahun" class="form-select form-select-sm">
                    @foreach ($listTahun as $th)
                        <option value="{{ $th }}" @selected($tahun == $th)>{{ $th }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-sm w-100">Tampilkan</button>
            </div>
        </div>
    </form>

    {{-- Judul Laporan --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Laporan Setoran — {{ $listBulan[$bulan] }} {{ $tahun }}</h5>
        <p class="text-muted small mb-0">Mitra: {{ auth()->user()->name }}</p>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Transaksi</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalTransaksi) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Berat</div>
                    <div class="fs-4 fw-bold">{{ number_format($totalBerat, 1, ',', '.') }} <span class="fs-6 fw-normal text-muted">Kg</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Poin Warga</div>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($totalPoin) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pendapatan</div>
                    <div class="fs-4 fw-bold text-success">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Setoran Harian</h6>
            <div style="position:relative; height:250px;">
                <canvas id="laporanChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body pb-0">
            <h6 class="fw-bold mb-3">Detail Transaksi</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Warga</th>
                        <th>Kategori</th>
                        <th>Berat</th>
                        <th>Poin</th>
                        <th class="text-end pe-4">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $trx)
                        <tr>
                            <td class="ps-4 small">{{ $trx->created_at->format('d M Y') }}</td>
                            <td class="small">{{ $trx->user->name ?? '-' }}</td>
                            <td class="small">{{ $trx->wasteCategory->name ?? '-' }}</td>
                            <td class="small">{{ $trx->berat_aktual ?? 0 }} kg</td>
                            <td class="small">+{{ $trx->points_earned ?? 0 }}</td>
                            <td class="text-end pe-4 small">
                                Rp {{ number_format($trx->total_harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Tidak ada transaksi di bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($transaksi->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end small">Total</th>
                            <th class="small">{{ number_format($totalBerat, 1, ',', '.') }} kg</th>
                            <th class="small">+{{ number_format($totalPoin) }}</th>
                            <th class="text-end pe-4 small">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        @if($transaksi->hasPages())
            <div class="p-3 border-top">{{ $transaksi->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('laporanChart').getContext('2d');
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
                        borderWidth: 1,
                        borderRadius: 4,
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
                            ticks: { font: { size: 11 } },
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        @media print {
            #sidebar, nav, header, footer, .btn, form { display: none !important; }
            #main { margin: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
@endpush