@extends('template.layout')

@section('title', 'Kelola Transaksi - WasteLyn')

@push('styles')
    <style>
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 1;
        }

        .btn-icon svg {
            display: block;
            pointer-events: none;
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-icon svg {
            display: block;
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

        .search-input {
            height: 40px;
            border-radius: 8px;
        }

        .filter-btn {
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border-radius: 8px;
            padding: 0 14px;
        }

        .filter-btn svg {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Kelola Transaksi</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Daftar semua transaksi di platform WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Daftar Transaksi</h5>
                </div>

                <div class="card-body pt-3">
                    {{-- Filter --}}
                    <form action="{{ route('admin.transactions.index') }}" method="GET">
                        <div class="row g-3 mb-4">
                            {{-- Search --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold small text-muted">Cari User</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control search-input"
                                        placeholder="Cari nama user..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary search-input px-3" title="Cari">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="display:block;">
                                            <circle cx="11" cy="11" r="8" />
                                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Tipe --}}
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small text-muted">Tipe Transaksi</label>
                                <select class="form-select" name="type" style="height:40px;">
                                    <option value="">Semua Tipe</option>
                                    <option value="earn" {{ request('type') == 'earn' ? 'selected' : '' }}>Earn</option>
                                    <option value="redeem" {{ request('type') == 'redeem' ? 'selected' : '' }}>Redeem</option>
                                </select>
                            </div>

                            {{-- Tombol --}}
                            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary filter-btn flex-grow-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                    </svg>
                                    Filter
                                </button>

                                <a href="{{ route('admin.transactions.index') }}"
                                    class="btn btn-outline-secondary filter-btn" title="Reset Filter">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10" />
                                        <polyline points="1 20 1 14 7 14" />
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                        <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel --}}
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
                                    <th class="text-center" style="width:80px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($transactions as $item)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold">
                                            {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">
                                                {{ $item->user->name ?? 'User' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if($item->type == 'earn')
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
                                            <span class="{{ $item->type == 'earn' ? 'points-earn' : 'points-redeem' }}">
                                                {{ $item->type == 'earn' ? '+' : '-' }}
                                                {{ number_format($item->points ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center text-muted">
                                            {{ number_format($item->xp_earned ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td class="text-muted small">
                                            {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            <div class="action-group justify-content-center">
                                                <a href="{{ route('admin.transactions.show', $item->transaction_id) }}"
                                                    class="btn btn-sm btn-outline-info btn-icon" title="Lihat Detail">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <path
                                                    d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z" />
                                                <line x1="8" y1="8" x2="16" y2="8" />
                                                <line x1="8" y1="12" x2="16" y2="12" />
                                                <line x1="8" y1="16" x2="12" y2="16" />
                                            </svg>
                                            <div class="mt-2">
                                                @if(request('search') || request('type'))
                                                    Tidak ada transaksi yang sesuai filter.
                                                @else
                                                    Belum ada transaksi.
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
                        <div class="d-flex justify-content-end mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection