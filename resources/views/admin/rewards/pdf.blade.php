<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Reward WasteLyn</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #198754;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #198754;
            letter-spacing: 1px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 10px;
            color: #666;
        }

        .info {
            margin-bottom: 12px;
            font-size: 10px;
            color: #555;
        }

        .info strong {
            color: #198754;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #198754;
            color: #fff;
            padding: 8px 6px;
            border: 1px solid #146c43;
            font-size: 9px;
            text-transform: uppercase;
            text-align: left;
        }

        table td {
            padding: 7px 6px;
            border: 1px solid #e0e0e0;
            font-size: 9px;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-available {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-unavailable {
            background: #e2e3e5;
            color: #41464b;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            text-align: center;
            font-size: 8px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN DATA REWARD</h1>
        <p>WasteLyn — Platform Pengelolaan Sampah</p>
    </div>

    <div class="info">
        <strong>Total Reward:</strong> {{ $rewards->count() }} &nbsp;|&nbsp;
        <strong>Tanggal Export:</strong> {{ now()->format('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="22%">Nama Reward</th>
                <th width="30%">Deskripsi</th>
                <th width="12%" class="text-center">Poin</th>
                <th width="10%" class="text-center">Stok</th>
                <th width="12%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rewards as $index => $r)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $r->name ?? '-' }}</strong></td>
                <td>{{ \Illuminate\Support\Str::limit($r->description ?? '-', 80) }}</td>
                <td class="text-center">{{ number_format($r->point_required ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($r->stock ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if(($r->status ?? '') === 'available')
                    <span class="badge badge-available">Tersedia</span>
                    @else
                    <span class="badge badge-unavailable">Habis</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding:20px;color:#777;">
                    Tidak ada data reward.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak otomatis oleh sistem WasteLyn<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>