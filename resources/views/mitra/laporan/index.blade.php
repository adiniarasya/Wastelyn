@extends('template.layout')

@section('title', 'Laporan Bulanan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Bulanan
        </h3>
        <button onclick="window.print()" class="btn btn-outline-success">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>

    {{-- Filter Bulan & Tahun --}}
    <div class="card card-stat mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach ($listBulan as $num => $nama)
                            <option value="{{ $num }}" @selected($bulan == $num)>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach ($listTahun as $th)
                            <option value="{{ $th }}" @selected($tahun == $th)>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Judul Laporan --}}
    <div class="text-center mb-3">
        <h5 class="mb-0">Laporan Setoran — {{ $listBulan[$bulan] }} {{ $tahun }}</h5>
        <small class="text-muted">Mitra: {{ auth()->user()->name }}</small>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalTransaksi) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Berat</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalBerat, 1, ',', '.') }} Kg</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Poin Warga</div>
                    <div class="fs-3 fw-bold text-warning">{{ number_format($totalPoin) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Pendapatan</div>
                    <div class="fs-3 fw-bold text-success">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            Setoran Harian
        </div>
        <div class="card-body">
            <div style="position:relative; height:250px;">
                <canvas id="laporanChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            Detail Transaksi
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Warga</th>
                            <th>Kategori</th>
                            <th>Berat</th>
                            <th>Poin</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d M Y') }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->wasteCategory->name ?? '-' }}</td>
                                <td>{{ $trx->berat_aktual ?? 0 }} kg</td>
                                <td>+{{ $trx->points_earned ?? 0 }}</td>
                                <td>
                                    Rp {{ number_format($trx->total_harga ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada transaksi di bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transaksi->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>{{ number_format($totalBerat, 1, ',', '.') }} kg</th>
                                <th>+{{ number_format($totalPoin) }}</th>
                                <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
            <div class="p-3">{{ $transaksi->links() }}</div>
        </div>
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