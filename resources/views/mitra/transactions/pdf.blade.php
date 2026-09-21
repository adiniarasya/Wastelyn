<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Setoran - WasteLyn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            color: #2E7D32;
            margin: 0;
            font-size: 20px;
        }
        .header h3 {
            margin: 5px 0 0 0;
            color: #666;
            font-weight: normal;
            font-size: 13px;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data th {
            background: #2E7D32;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        table.data td {
            padding: 5px 8px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        table.data tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-earn { background: #d4edda; color: #155724; }
        .badge-redeem { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>

    <div class="header">
        <h1>WasteLyn</h1>
        <h3>Laporan Riwayat Setoran Mitra</h3>
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
                    <strong>Total Transaksi:</strong> {{ $transactions->count() }}<br>
                    <strong>Total Poin:</strong> {{ number_format($transactions->where('type', 'earn')->sum('points')) }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Warga</th>
                <th width="30%">Deskripsi</th>
                <th width="10%" class="text-right">Poin</th>
                <th width="10%" class="text-center">Tipe</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ $trx->description ?? '-' }}</td>
                    <td class="text-right">
                        {{ $trx->type === 'earn' ? '+' : '-' }}{{ number_format($trx->points) }}
                    </td>
                    <td class="text-center">
                        @if($trx->type === 'earn')
                            <span class="badge badge-earn">Dapat Poin</span>
                        @else
                            <span class="badge badge-redeem">Tukar Poin</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #999;">
                        Belum ada riwayat transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} WasteLyn — Platform Pembentukan Kebiasaan Pengelolaan Sampah
    </div>

</body>
</html>