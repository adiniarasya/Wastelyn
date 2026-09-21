<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Setoran - WasteLyn</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2E7D32; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { color: #2E7D32; margin: 0; font-size: 20px; }
        .header h3 { margin: 5px 0 0 0; color: #666; font-weight: normal; font-size: 13px; }
        .meta { margin-bottom: 15px; font-size: 11px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .filter-info { background: #f8f9fa; padding: 8px; border-radius: 4px; margin-bottom: 10px; font-size: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #2E7D32; color: white; padding: 6px 8px; text-align: left; font-size: 10px; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        table.data tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; color: white; }
        .badge-pickup { background: #4CAF50; }
        .badge-dropoff { background: #1A237E; }
    </style>
</head>
<body>

    <div class="header">
        <h1>WasteLyn</h1>
        <h3>Laporan Setoran Mitra</h3>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="50%">
                    <strong>Nama Mitra:</strong> {{ $mitra->name }}<br>
                    <strong>Email:</strong> {{ $mitra->email }}<br>
                    <strong>No. Telepon:</strong> {{ $mitra->phone ?? '-' }}
                </td>
                <td width="50%" class="text-right">
                    <strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }}<br>
                    <strong>Total Setoran:</strong> {{ $setoran->count() }}<br>
                    <strong>Total Berat:</strong> {{ number_format($setoran->sum('berat_aktual'), 1, ',', '.') }} kg
                </td>
            </tr>
        </table>
    </div>

    @if($filterInfo['tanggal_dari'] || $filterInfo['tanggal_sampai'] || $filterInfo['kategori'] || $filterInfo['metode'])
        <div class="filter-info">
            <strong>Filter Aktif:</strong>
            @if($filterInfo['tanggal_dari']) Dari: {{ $filterInfo['tanggal_dari'] }} @endif
            @if($filterInfo['tanggal_sampai']) Sampai: {{ $filterInfo['tanggal_sampai'] }} @endif
            @if($filterInfo['kategori']) Kategori: {{ $filterInfo['kategori'] }} @endif
            @if($filterInfo['metode']) Metode: {{ ucfirst($filterInfo['metode']) }} @endif
        </div>
    @endif

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Warga</th>
                <th width="15%">Kategori</th>
                <th width="8%">Berat</th>
                <th width="10%">Metode</th>
                <th width="10%" class="text-right">Poin</th>
                <th width="12%" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($setoran as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->created_at->format('d M Y') }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>{{ $s->wasteCategory->name ?? '-' }}</td>
                    <td>{{ $s->berat_aktual ?? 0 }} kg</td>
                    <td>
                        <span class="badge badge-{{ $s->pickup_method === 'pickup' ? 'pickup' : 'dropoff' }}">
                            {{ ucfirst($s->pickup_method ?? '-') }}
                        </span>
                    </td>
                    <td class="text-right">+{{ number_format($s->points_earned ?? 0) }}</td>
                    <td class="text-right">Rp {{ number_format($s->total_harga ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #999;">
                        Belum ada setoran.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($setoran->count() > 0)
            <tfoot>
                <tr style="background: #f0f0f0; font-weight: bold;">
                    <td colspan="4" class="text-right">Total</td>
                    <td>{{ number_format($setoran->sum('berat_aktual'), 1, ',', '.') }} kg</td>
                    <td></td>
                    <td class="text-right">+{{ number_format($setoran->sum('points_earned')) }}</td>
                    <td class="text-right">Rp {{ number_format($setoran->sum('total_harga'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} WasteLyn — Platform Pembentukan Kebiasaan Pengelolaan Sampah
    </div>

</body>
</html>