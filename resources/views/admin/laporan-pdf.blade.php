<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan WasteLyn</title>
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
            margin-bottom: 18px;
            font-size: 10px;
            color: #555;
            text-align: right;
        }

        .info strong {
            color: #198754;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #198754;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 4px;
            margin-bottom: 8px;
            text-transform: uppercase;
            margin-top: 20px;
        }

        .stats-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin-bottom: 10px;
        }

        .stats-grid td {
            width: 20%;
            text-align: center;
            padding: 12px 6px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
        }

        .stats-grid .value {
            font-size: 18px;
            font-weight: bold;
            color: #198754;
            display: block;
        }

        .stats-grid .label {
            font-size: 9px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 4px;
            display: block;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #198754;
            color: #fff;
            padding: 8px 6px;
            border: 1px solid #146c43;
            font-size: 9px;
            text-transform: uppercase;
            text-align: left;
        }

        table.data td {
            padding: 7px 6px;
            border: 1px solid #e0e0e0;
            font-size: 9px;
            vertical-align: top;
        }

        table.data tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-earn {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-redeem {
            background: #f8d7da;
            color: #842029;
        }

        .badge-role-admin {
            background: #f8d7da;
            color: #842029;
        }

        .badge-role-mitra {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-role-warga {
            background: #cfe2ff;
            color: #084298;
        }

        .footer {
            margin-top: 25px;
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
        <h1>LAPORAN WASTELYN</h1>
        <p>Platform Pengelolaan Sampah</p>
    </div>

    <div class="info">
        <strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }}
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="section-title">Ringkasan Data</div>
    <table class="stats-grid">
        <tr>
            <td>
                <span class="value">{{ number_format($totalUsers) }}</span>
                <span class="label">Total User</span>
            </td>
            <td>
                <span class="value">{{ number_format($totalTransactions) }}</span>
                <span class="label">Transaksi</span>
            </td>
            <td>
                <span class="value">{{ number_format($totalPickups) }}</span>
                <span class="label">Setoran</span>
            </td>
            <td>
                <span class="value">{{ number_format($totalMissions) }}</span>
                <span class="label">Mission</span>
            </td>
            <td>
                <span class="value">{{ number_format($totalRewards) }}</span>
                <span class="label">Reward</span>
            </td>
        </tr>
    </table>

    {{-- Transaksi Terbaru --}}
    <div class="section-title">Transaksi Terbaru</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">User</th>
                <th width="10%" class="text-center">Tipe</th>
                <th width="12%" class="text-right">Poin</th>
                <th width="13%" class="text-center">Status</th>
                <th width="35%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTransactions as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td class="text-center">
                        @if($t->type == 'earn')
                            <span class="badge badge-earn">Earn</span>
                        @else
                            <span class="badge badge-redeem">Redeem</span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $t->type == 'earn' ? '+' : '-' }}{{ number_format($t->points ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-center">{{ ucfirst($t->status ?? '-') }}</td>
                    <td>{{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:15px;color:#777;">
                        Belum ada transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- User Terbaru --}}
    <div class="section-title">User Terbaru</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Nama</th>
                <th width="30%">Email</th>
                <th width="12%" class="text-center">Role</th>
                <th width="13%" class="text-center">Status</th>
                <th width="15%">Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers as $index => $u)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td class="text-center">
                        @if($u->role === 'admin')
                            <span class="badge badge-role-admin">Admin</span>
                        @elseif($u->role === 'mitra')
                            <span class="badge badge-role-mitra">Mitra</span>
                        @else
                            <span class="badge badge-role-warga">Warga</span>
                        @endif
                    </td>
                    <td class="text-center">{{ ucfirst($u->status ?? '-') }}</td>
                    <td>{{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:15px;color:#777;">
                        Belum ada user
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