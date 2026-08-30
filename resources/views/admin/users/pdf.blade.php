<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data User WasteLyn</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #198754;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #198754;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 11px;
        }

        .info {
            margin-bottom: 15px;
            font-size: 9px;
            color: #666;
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
            padding: 8px;
            border: 1px solid #ddd;
        }

        table td {
            padding: 7px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .photo {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
        }

        .no-photo {
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            border-radius: 50%;
            background-color: #e9ecef;
            font-weight: bold;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 8px;
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

        .empty {
            text-align: center;
            padding: 20px;
            color: #777;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #777;
        }
    </style>

</head>

<body>
    <div class="header">
        <h1>LAPORAN DATA USER</h1>
        <p>WasteLyn - Sistem Pengelolaan Sampah</p>
    </div>

    <div class="info">
        <strong>Total User:</strong> {{ $users->count() }}<br>
        <strong>Tanggal Export:</strong> {{ now()->format('d F Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="9%" class="text-center">Foto</th>
                <th width="18%">Nama</th>
                <th width="23%">Email</th>
                <th width="12%" class="text-center">Role</th>
                <th width="10%" class="text-center">XP</th>
                <th width="10%" class="text-center">Point</th>
                <th width="13%" class="text-center">Level</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    {{-- Foto --}}
                    <td class="text-center">
                        @if($user->photo && Storage::disk('public')->exists($user->photo))
                            <img src="{{ public_path('storage/' . $user->photo) }}" class="photo" alt="{{ $user->name }}">
                        @else
                            <div class="no-photo">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>

                    {{-- Nama --}}
                    <td>
                        {{ $user->name }}
                    </td>

                    {{-- Email --}}
                    <td>
                        {{ $user->email }}
                    </td>

                    {{-- Role --}}
                    <td class="text-center">
                        @if($user->role === 'admin')
                            <span class="badge role-admin">
                                Admin
                            </span>
                        @elseif($user->role === 'mitra')
                            <span class="badge role-mitra">
                                Mitra
                            </span>
                        @else
                            <span class="badge role-warga">
                                Warga
                            </span>
                        @endif
                    </td>

                    {{-- XP --}}
                    <td class="text-center">
                        {{ number_format($user->xp ?? 0, 0, ',', '.') }}
                    </td>

                    {{-- Point --}}
                    <td class="text-center">
                        {{ number_format($user->points ?? 0, 0, ',', '.') }}
                    </td>

                    {{-- Level --}}
                    <td class="text-center">
                        {{ $user->level_name ?? 'Green Newbie' }}
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" class="empty">
                        Tidak ada data user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>

</html>