@extends('template.layout')

@section('title', 'Kelola Reward - WasteLyn')

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
    .btn-icon svg { display: block; pointer-events: none; }

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
    .badge-icon svg { display: block; }

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
    .table-clean tbody tr:last-child td { border-bottom: 0; }

    .reward-image {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        display: block;
    }
    .reward-image-empty {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .point-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 8px;
        background: #E6F7EE;
        color: #2E7D32;
        font-weight: 700;
        font-size: 13px;
    }
    .point-badge svg { display: block; }

    .search-input { height: 40px; border-radius: 8px; }
    .filter-btn {
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border-radius: 8px;
        padding: 0 14px;
    }
    .filter-btn svg { display: block; }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Kelola Reward</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Pantau semua hadiah yang bisa ditukar poin
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Daftar Reward</h5>

                    <a href="{{ route('admin.rewards.export.pdf', request()->query()) }}"
                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <polyline points="9 15 12 12 15 15"/>
                        </svg>
                        Export PDF
                    </a>
                </div>

                <div class="card-body pt-3">
                    <form action="{{ route('admin.rewards.index') }}" method="GET">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-semibold small text-muted">Cari Nama Reward</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control search-input"
                                        placeholder="Cari reward..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary search-input px-3" title="Cari">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            style="display:block;">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small text-muted">Status</label>
                                <select class="form-select" name="status" style="height:40px;">
                                    <option value="">Semua Status</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Habis</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary filter-btn flex-grow-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('admin.rewards.index') }}"
                                    class="btn btn-outline-secondary filter-btn" title="Reset Filter">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10"/>
                                        <polyline points="1 20 1 14 7 14"/>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/>
                                        <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-clean table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No</th>
                                    <th style="width:80px;">Gambar</th>
                                    <th>Nama Reward</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Poin</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width:80px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($rewards as $item)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>

                                        <td>
                                            @if($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    alt="{{ $item->name }}" class="reward-image">
                                            @else
                                                <div class="reward-image-empty">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="20 12 20 22 4 22 4 12"/>
                                                        <rect x="2" y="7" width="20" height="5"/>
                                                        <line x1="12" y1="22" x2="12" y2="7"/>
                                                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
                                                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">{{ $item->name ?? '-' }}</span>
                                        </td>

                                        <td>
                                            <span class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit($item->description ?? '', 60) ?: '-' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span class="point-badge">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="M12 6v6l4 2"/>
                                                </svg>
                                                {{ number_format($item->point_required ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-semibold">
                                                {{ number_format($item->stock ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if(($item->status ?? '') === 'available')
                                                <span class="badge bg-success badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                    Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-secondary badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                    </svg>
                                                    Habis
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="action-group justify-content-center">
                                                <a href="{{ route('admin.rewards.show', $item->reward_id ?? $item->id) }}"
                                                    class="btn btn-sm btn-outline-info btn-icon" title="Lihat Detail">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <polyline points="20 12 20 22 4 22 4 12"/>
                                                <rect x="2" y="7" width="20" height="5"/>
                                                <line x1="12" y1="22" x2="12" y2="7"/>
                                            </svg>
                                            <div class="mt-2">
                                                @if(request('search') || request('status'))
                                                    Tidak ada reward yang sesuai filter.
                                                @else
                                                    Belum ada reward.
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection