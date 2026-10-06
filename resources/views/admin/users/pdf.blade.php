<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data User WasteLyn</title>

    <style>
        @page {
            margin: 20px 25px;
        }

        * {
            font-family: DejaVu Sans, sans-serif;
        }

        body {
            font-size: 10px;
            color: #333;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #198754;
            padding-bottom: 12px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #198754;
            letter-spacing: 1px;
        }

        .header p {
            margin: 4px 0 0;
            color: #666;
            font-size: 10px;
        }

        .info {
            margin-bottom: 12px;
            font-size: 9px;
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
            background-color: #198754;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 7px 6px;
            border: 1px solid #146c43;
            font-size: 9px;
            text-transform: uppercase;
        }

        table td {
            padding: 6px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
            font-size: 9px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .photo {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
        }

        .no-photo {
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border-radius: 50%;
            background-color: #e9ecef;
            font-weight: bold;
            color: #435EBE;
            font-size: 13px;
        }

        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .role-admin {
            background-color: #f8d7da;
            color: #842029;
        }

        .role-mitra {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .role-warga {
            background-color: #cfe2ff;
            color: #084298;
        }

        .status-active {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #664d03;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        .status-inactive {
            background-color: #e2e3e5;
            color: #41464b;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
            color: #888;
        }

        .footer-left {
            float: left;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN DATA USER</h1>
        <p>WasteLyn — Sistem Pengelolaan Sampah</p>
    </div>

    <div class="info">
        <strong>Total User:</strong> {{ $users->count() }} &nbsp;|&nbsp;
        <strong>Tanggal Export:</strong> {{ now()->format('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="8%" class="text-center">Foto</th>
                <th width="16%">Nama</th>
                <th width="22%">Email</th>
                <th width="10%" class="text-center">Role</th>
                <th width="8%" class="text-center">XP</th>
                <th width="8%" class="text-center">Point</th>
                <th width="12%" class="text-center">Status</th>
                <th width="12%" class="text-center">Level</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    <td class="text-center">
                        @php
                            $photoPath = storage_path('app/public/' . $user->photo);
                        @endphp

                        @if($user->photo && file_exists($photoPath))
                            <img src="{{ $photoPath }}" class="photo" alt="{{ $user->name }}">
                        @else
                            <div class="no-photo">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td class="text-center">
                        @if($user->role === 'admin')
                            <span class="badge role-admin">Admin</span>
                        @elseif($user->role === 'mitra')
                            <span class="badge role-mitra">Mitra</span>
                        @else
                            <span class="badge role-warga">Warga</span>
                        @endif
                    </td>

                    <td class="text-center">{{ number_format($user->xp ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($user->points ?? 0, 0, ',', '.') }}</td>

                    <td class="text-center">
                        @if($user->status === 'active')
                            <span class="badge status-active">Aktif</span>
                        @elseif($user->status === 'pending')
                            <span class="badge status-pending">Pending</span>
                        @elseif($user->status === 'rejected')
                            <span class="badge status-rejected">Ditolak</span>
                        @elseif($user->status === 'inactive')
                            <span class="badge status-inactive">Nonaktif</span>
                        @else
                            <span class="badge status-inactive">-</span>
                        @endif
                    </td>

                    <td class="text-center">{{ $user->level_name ?? 'Green Newbie' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="empty">
                        Tidak ada data user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span class="footer-left">
            WasteLyn — Data User
        </span>
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>

</html>