<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Transaksi #{{ $transaction->transaction_id }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 11px;
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

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #888;
            margin-bottom: 20px;
        }

        .poin-box {
            text-align: center;
            padding: 15px;
            background: #E6F7EE;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .poin-box .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .poin-box .value {
            font-size: 26px;
            font-weight: bold;
            color: #2E7D32;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #198754;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 4px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        table.detail {
            width: 100%;
            border-collapse: collapse;
        }

        table.detail td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 10px;
        }

        table.detail td.label {
            width: 35%;
            color: #666;
            font-weight: 600;
        }

        table.detail td.value {
            color: #333;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            text-align: center;
            font-size: 9px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>BUKTI TRANSAKSI</h1>
        <p>WasteLyn — Platform Pengelolaan Sampah</p>
    </div>

    <div class="title">Transaksi #{{ $transaction->transaction_id }}</div>
    <div class="subtitle">{{ $transaction->created_at?->format('d F Y, H:i') ?? '-' }}</div>

    <div class="poin-box">
        <div class="label">{{ $transaction->type == 'earn' ? 'Poin Didapat' : 'Poin Ditukarkan' }}</div>
        <div class="value">
            {{ $transaction->type == 'earn' ? '+' : '-' }}{{ number_format($transaction->points ?? 0, 0, ',', '.') }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informasi User</div>
        <table class="detail">
            <tr>
                <td class="label">Nama</td>
                <td class="value">{{ $transaction->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{ $transaction->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No HP</td>
                <td class="value">{{ $transaction->user->phone ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($transaction->pickupRequest)
        <div class="section">
            <div class="section-title">Informasi Setoran</div>
            <table class="detail">
                <tr>
                    <td class="label">Jenis Sampah</td>
                    <td class="value">{{ $transaction->pickupRequest->wasteCategory->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Berat</td>
                    <td class="value">{{ number_format($transaction->pickupRequest->weight_kg ?? 0, 2, ',', '.') }} Kg</td>
                </tr>
                @if($transaction->pickupRequest->co2_saved)
                    <tr>
                        <td class="label">CO₂ Tersimpan</td>
                        <td class="value">{{ number_format($transaction->pickupRequest->co2_saved, 2, ',', '.') }} Kg</td>
                    </tr>
                @endif
                @if($transaction->pickupRequest->verified_at)
                    <tr>
                        <td class="label">Diverifikasi</td>
                        <td class="value">{{ $transaction->pickupRequest->verified_at->format('d F Y, H:i') }}</td>
                    </tr>
                @endif
            </table>
        </div>

        @if($transaction->pickupRequest->bank)
            <div class="section">
                <div class="section-title">Bank Sampah</div>
                <table class="detail">
                    <tr>
                        <td class="label">Nama Bank</td>
                        <td class="value">{{ $transaction->pickupRequest->bank->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="value">{{ $transaction->pickupRequest->bank->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Telepon</td>
                        <td class="value">{{ $transaction->pickupRequest->bank->phone ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        @endif
    @endif

    @if($transaction->description)
        <div class="section">
            <div class="section-title">Deskripsi</div>
            <div style="font-size:10px; color:#555;">{{ $transaction->description }}</div>
        </div>
    @endif

    <div class="footer">
        Dokumen ini dicetak otomatis oleh sistem WasteLyn<br>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>