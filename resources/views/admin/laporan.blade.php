@extends('template.layout')

@section('title', 'Laporan - WasteLyn')

@push('styles')
    <style>
        .badge-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-icon svg {
            display: block;
        }

        .stat-card {
            text-align: center;
            padding: 18px 12px;
            border-radius: 12px;
            transition: transform 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 600;
            color: #6c757d;
            margin-top: 6px;
        }

        .stat-card .value {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
        }

        .table-clean thead th {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6c757d;
            background: #f8f9fa;
            padding: 12px 14px;
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .table-clean tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-clean tbody tr:last-child td {
            border-bottom: 0;
        }

        .points-earn {
            color: #2E7D32;
            font-weight: 700;
        }

        .points-redeem {
            color: #C62828;
            font-weight: 700;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <h3 class="fw-bold mb-1">Laporan</h3>
                            <p class="text-subtitle text-muted mb-0">
                                Rekapitulasi data WasteLyn
                            </p>
                        </div>

                        <a href="{{ route('admin.laporan.export.pdf') }}"
                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="12" y1="18" x2="12" y2="12" />
                                <polyline points="9 15 12 12 15 15" />
                            </svg>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- Statistik --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="background:#E7F1FF;">
                        <div class="value" style="color:#435EBE;">{{ number_format($totalUsers ?? 0) }}</div>
                        <div class="label">Total User</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="background:#E6F7EE;">
                        <div class="value" style="color:#2E7D32;">{{ number_format($totalTransactions ?? 0) }}</div>
                        <div class="label">Transaksi</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="background:#FFF4E0;">
                        <div class="value" style="color:#D97706;">{{ number_format($totalPickups ?? 0) }}</div>
                        <div class="label">Setoran</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="background:#E0F5FA;">
                        <div class="value" style="color:#0891B2;">{{ number_format($totalMissions ?? 0) }}</div>
                        <div class="label">Mission</div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg">
                    <div class="stat-card" style="background:#F3E8FF;">
                        <div class="value" style="color:#7E22CE;">{{ number_format($totalRewards ?? 0) }}</div>
                        <div class="label">Reward</div>
                    </div>
                </div>
            </div>

            {{-- Transaksi Terbaru --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Transaksi Terbaru</h5>
                    <span class="badge bg-light text-muted fw-normal">
                        {{ $recentTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $recentTransactions->total() : count($recentTransactions) }}
                        total
                    </span>
                </div>

                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-clean table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No</th>
                                    <th>User</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-end">Poin</th>
                                    <th class="text-center">XP</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $index => $transaction)
                                                        <tr>
                                                            <td class="text-center text-muted fw-semibold">
                                                                {{ $recentTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator
                                    ? $index + 1 + ($recentTransactions->currentPage() - 1) * $recentTransactions->perPage()
                                    : $index + 1 }}
                                                            </td>
                                                            <td>
                                                                <span class="fw-semibold text-dark">
                                                                    {{ $transaction->user->name ?? '-' }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if($transaction->type == 'earn')
                                                                    <span class="badge bg-success badge-icon">
                                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <line x1="12" y1="5" x2="12" y2="19" />
                                                                            <polyline points="19 12 12 5 5 12" />
                                                                        </svg>
                                                                        Earn
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger badge-icon">
                                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <line x1="12" y1="5" x2="12" y2="19" />
                                                                            <polyline points="5 12 12 19 19 12" />
                                                                        </svg>
                                                                        Redeem
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                <span class="{{ $transaction->type == 'earn' ? 'points-earn' : 'points-redeem' }}">
                                                                    {{ $transaction->type == 'earn' ? '+' : '-' }}{{ number_format($transaction->points ?? 0, 0, ',', '.') }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center text-muted">
                                                                {{ number_format($transaction->xp_earned ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-muted small">
                                                                {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : '-' }}
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <path
                                                    d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z" />
                                            </svg>
                                            <div class="mt-2">Belum ada transaksi</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Transaksi --}}
                    @if($recentTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $recentTransactions->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $recentTransactions->appends(request()->except('transactions_page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- User Terbaru --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">User Terbaru</h5>
                    <span class="badge bg-light text-muted fw-normal">
                        {{ $recentUsers instanceof \Illuminate\Pagination\LengthAwarePaginator ? $recentUsers->total() : count($recentUsers) }}
                        total
                    </span>
                </div>

                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-clean table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Status</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers ?? [] as $index => $user)
                                                        <tr>
                                                            <td class="text-center text-muted fw-semibold">
                                                                {{ $recentUsers instanceof \Illuminate\Pagination\LengthAwarePaginator
                                    ? $index + 1 + ($recentUsers->currentPage() - 1) * $recentUsers->perPage()
                                    : $index + 1 }}
                                                            </td>
                                                            <td>
                                                                <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                                            </td>
                                                            <td class="text-muted small">{{ $user->email }}</td>
                                                            <td class="text-center">
                                                                @if($user->role === 'admin')
                                                                    <span class="badge bg-danger">Admin</span>
                                                                @elseif($user->role === 'mitra')
                                                                    <span class="badge bg-success">Mitra</span>
                                                                @else
                                                                    <span class="badge bg-primary">Warga</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($user->status == 'active')
                                                                    <span class="badge bg-success badge-icon">
                                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <polyline points="20 6 9 17 4 12" />
                                                                        </svg>
                                                                        Aktif
                                                                    </span>
                                                                @elseif($user->status == 'pending')
                                                                    <span class="badge bg-warning text-dark badge-icon">
                                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <circle cx="12" cy="12" r="10" />
                                                                            <polyline points="12 6 12 12 16 14" />
                                                                        </svg>
                                                                        Pending
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-secondary">{{ ucfirst($user->status ?? 'unknown') }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-muted small">
                                                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                                                            </td>
                                                        </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                            </svg>
                                            <div class="mt-2">Belum ada user</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination User --}}
                    @if($recentUsers instanceof \Illuminate\Pagination\LengthAwarePaginator && $recentUsers->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $recentUsers->appends(request()->except('users_page'))->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </section>
    </div>
@endsection