@extends('template.layout')

@section('title', 'Kelola Misi - WasteLyn')

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

    .reward-xp { color: #435EBE; font-weight: 700; }
    .reward-points { color: #2E7D32; font-weight: 700; }

    .date-text {
        font-size: 12px;
        color: #6c757d;
        white-space: nowrap;
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
    .filter-btn svg { display: block; }

    .participant-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 8px;
        background: #E7F1FF;
        color: #435EBE;
        font-weight: 700;
        font-size: 13px;
    }
    .participant-badge svg { display: block; }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Kelola Misi</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Pantau semua misi dari setiap bank sampah
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Daftar Misi</h5>

                    <a href="{{ route('admin.missions.export.pdf', request()->query()) }}"
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
                    {{-- Filter --}}
                    <form action="{{ route('admin.missions.index') }}" method="GET">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small text-muted">Cari Judul Misi</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control search-input"
                                        placeholder="Cari judul misi..." value="{{ request('search') }}">
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

                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small text-muted">Bank Sampah</label>
                                <select class="form-select" name="bank_id" style="height:40px;">
                                    <option value="">Semua Bank Sampah</option>
                                    @foreach($wasteBanks as $bank)
                                        <option value="{{ $bank->bank_id }}"
                                            {{ request('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2">
                                <label class="form-label fw-semibold small text-muted">Status</label>
                                <select class="form-select" name="status" style="height:40px;">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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

                                <a href="{{ route('admin.missions.index') }}"
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

                    {{-- Tabel --}}
                    <div class="table-responsive">
                        <table class="table table-clean table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">No</th>
                                    <th>Judul Misi</th>
                                    <th>Bank Sampah</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Reward</th>
                                    <th class="text-center">Peserta</th>
                                    <th>Periode</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width:80px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($missions as $item)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">
                                                {{ $item->title ?? '-' }}
                                            </span>
                                            @if($item->description)
                                                <small class="text-muted d-block">
                                                    {{ \Illuminate\Support\Str::limit($item->description, 50) }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="text-muted">{{ $item->bank->name ?? '-' }}</span>
                                        </td>

                                        <td class="text-center">
                                            @if($item->type == 'quantitative')
                                                <span class="badge bg-info badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="18" y1="20" x2="18" y2="10"/>
                                                        <line x1="12" y1="20" x2="12" y2="4"/>
                                                        <line x1="6" y1="20" x2="6" y2="14"/>
                                                    </svg>
                                                    Kuantitatif
                                                </span>
                                            @else
                                                <span class="badge bg-primary badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                                    </svg>
                                                    Kualitatif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-semibold">
                                                {{ number_format($item->target ?? 0, 0, ',', '.') }}
                                            </span>
                                            <small class="text-muted d-block">{{ $item->unit ?? '' }}</small>
                                        </td>

                                        <td class="text-center">
                                            <div><span class="reward-xp">{{ number_format($item->reward_xp ?? 0, 0, ',', '.') }} XP</span></div>
                                            <div><span class="reward-points">{{ number_format($item->reward_points ?? 0, 0, ',', '.') }} Poin</span></div>
                                        </td>

                                        <td class="text-center">
                                            <span class="participant-badge">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="9" cy="7" r="4"/>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                </svg>
                                                {{ $item->user_missions_count ?? 0 }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="date-text">
                                                {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }}
                                                <br>
                                                <span class="text-muted">s/d</span>
                                                {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M Y') : '-' }}
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            @if($item->status == 'active')
                                                <span class="badge bg-success badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                    </svg>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="action-group justify-content-center">
                                                <a href="{{ route('admin.missions.show', $item->mission_id ?? $item->id) }}"
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
                                        <td colspan="10" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <circle cx="12" cy="12" r="10"/>
                                                <circle cx="12" cy="12" r="6"/>
                                                <circle cx="12" cy="12" r="2"/>
                                            </svg>
                                            <div class="mt-2">
                                                @if(request('search') || request('bank_id') || request('status'))
                                                    Tidak ada misi yang sesuai filter.
                                                @else
                                                    Belum ada misi dari mitra.
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