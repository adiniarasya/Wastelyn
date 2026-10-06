<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Misi WasteLyn</title>
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

        .badge-active {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-inactive {
            background: #e2e3e5;
            color: #41464b;
        }

        .badge-quant {
            background: #cff4fc;
            color: #055160;
        }

        .badge-qual {
            background: #cfe2ff;
            color: #084298;
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
        <h1>LAPORAN DATA MISI</h1>
        <p>WasteLyn — Platform Pengelolaan Sampah</p>
    </div>

    <div class="info">
        <strong>Total Misi:</strong> {{ $missions->count() }} &nbsp;|&nbsp;
        <strong>Tanggal Export:</strong> {{ now()->format('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="20%">Judul Misi</th>
                <th width="14%">Bank Sampah</th>
                <th width="8%" class="text-center">Tipe</th>
                <th width="8%" class="text-center">Target</th>
                <th width="10%" class="text-center">Reward</th>
                <th width="7%" class="text-center">Peserta</th>
                <th width="14%">Periode</th>
                <th width="8%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($missions as $index => $m)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $m->title ?? '-' }}</strong>
                        @if($m->description)
                            <div style="font-size:8px;color:#777;margin-top:2px;">
                                {{ \Illuminate\Support\Str::limit($m->description, 60) }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $m->bank->name ?? '-' }}</td>
                    <td class="text-center">
                        @if($m->type == 'quantitative')
                            <span class="badge badge-quant">Kuantitatif</span>
                        @else
                            <span class="badge badge-qual">Kualitatif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ number_format($m->target ?? 0, 0, ',', '.') }} {{ $m->unit ?? '' }}
                    </td>
                    <td class="text-center">
                        <div>{{ number_format($m->reward_xp ?? 0, 0, ',', '.') }} XP</div>
                        <div>{{ number_format($m->reward_points ?? 0, 0, ',', '.') }} Poin</div>
                    </td>
                    <td class="text-center">
                        <strong>{{ $m->user_missions_count ?? 0 }}</strong>
                    </td>
                    <td>
                        {{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d M Y') : '-' }}
                        <br>s/d<br>
                        {{ $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('d M Y') : '-' }}
                    </td>
                    <td class="text-center">
                        @if($m->status == 'active')
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Nonaktif</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding:20px;color:#777;">
                        Tidak ada data misi.
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