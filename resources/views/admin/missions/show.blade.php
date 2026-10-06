@extends('template.layout')

@section('title', 'Detail Misi - WasteLyn')

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
            padding: 16px 12px;
            border-radius: 12px;
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
            font-size: 20px;
            font-weight: 700;
            line-height: 1.1;
        }

        .info-label {
            text-align: right;
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            padding-top: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #212529;
        }

        .participant-row {
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .participant-row:last-child {
            border-bottom: 0;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
            background: #E7F1FF;
            color: #435EBE;
            flex-shrink: 0;
        }

        .progress-mini {
            height: 6px;
            border-radius: 6px;
            background: #f1f3f5;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-mini-bar {
            height: 100%;
            background: #2E7D32;
            border-radius: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Detail Misi</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Informasi lengkap misi dan pesertanya
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Misi #{{ $mission->mission_id }}</h5>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.missions.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12" />
                                <polyline points="12 19 5 12 12 5" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body pt-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10">

                            {{-- Header: Judul + Status --}}
                            <div class="text-center mb-4">
                                <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                    style="width:90px;height:90px;background:#E6F7EE;color:#2E7D32;">
                                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <circle cx="12" cy="12" r="6" />
                                        <circle cx="12" cy="12" r="2" />
                                    </svg>
                                </div>

                                <h3 class="fw-bold mt-3 mb-2">{{ $mission->title ?? '-' }}</h3>

                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    @if($mission->type == 'quantitative')
                                        <span class="badge bg-info badge-icon">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="20" x2="18" y2="10" />
                                                <line x1="12" y1="20" x2="12" y2="4" />
                                                <line x1="6" y1="20" x2="6" y2="14" />
                                            </svg>
                                            Kuantitatif
                                        </span>
                                    @else
                                        <span class="badge bg-primary badge-icon">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                            </svg>
                                            Kualitatif
                                        </span>
                                    @endif

                                    @if($mission->status == 'active')
                                        <span class="badge bg-success badge-icon">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary badge-icon">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Stat Cards --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="stat-card" style="background:#E7F1FF;">
                                        <div class="value" style="color:#435EBE;">
                                            {{ number_format($mission->target ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="label">Target ({{ $mission->unit ?? '-' }})</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-card" style="background:#E6F7EE;">
                                        <div class="value" style="color:#2E7D32;">
                                            {{ number_format($mission->reward_xp ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="label">Reward XP</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-card" style="background:#FFF4E0;">
                                        <div class="value" style="color:#D97706;">
                                            {{ number_format($mission->reward_points ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="label">Reward Poin</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="stat-card" style="background:#F3E8FF;">
                                        <div class="value" style="color:#7E22CE;">
                                            {{ $mission->user_missions_count ?? $mission->userMissions->count() }}
                                        </div>
                                        <div class="label">Peserta</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            @if($mission->description)
                                <div class="border-top pt-3 mb-3">
                                    <h6 class="fw-bold mb-3">Deskripsi</h6>
                                    <div class="alert alert-light border small text-muted mb-0">
                                        {{ $mission->description }}
                                    </div>
                                </div>
                            @endif

                            {{-- Info Misi --}}
                            <div class="border-top pt-3 mb-3">
                                <h6 class="fw-bold mb-3">Informasi Misi</h6>

                                <div class="row mb-2">
                                    <div class="col-4 col-md-3 info-label">Bank Sampah</div>
                                    <div class="col-8 col-md-9 info-value">
                                        <span class="fw-semibold">{{ $mission->bank->name ?? '-' }}</span>
                                        @if($mission->bank->address ?? false)
                                            <small class="text-muted d-block">{{ $mission->bank->address }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-4 col-md-3 info-label">Periode</div>
                                    <div class="col-8 col-md-9 info-value">
                                        {{ $mission->start_date ? \Carbon\Carbon::parse($mission->start_date)->format('d F Y') : '-' }}
                                        <span class="text-muted mx-1">s/d</span>
                                        {{ $mission->end_date ? \Carbon\Carbon::parse($mission->end_date)->format('d F Y') : '-' }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-4 col-md-3 info-label">Dibuat</div>
                                    <div class="col-8 col-md-9 info-value">
                                        {{ $mission->created_at?->format('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Daftar Peserta --}}
                            <div class="border-top pt-3 mb-3">
                                <h6 class="fw-bold mb-3">
                                    Peserta Misi
                                    <span class="badge bg-light text-muted fw-normal ms-2">
                                        {{ $mission->userMissions->count() ?? 0 }} orang
                                    </span>
                                </h6>

                                @forelse($mission->userMissions as $um)
                                    <div class="participant-row">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($um->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold text-dark">
                                                    {{ $um->user->name ?? 'User' }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $um->user->email ?? '-' }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                @if(isset($um->status))
                                                    @if($um->status == 'completed')
                                                        <span class="badge bg-success badge-icon">
                                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12" />
                                                            </svg>
                                                            Selesai
                                                        </span>
                                                    @elseif($um->status == 'in_progress')
                                                        <span class="badge bg-warning text-dark badge-icon">
                                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <circle cx="12" cy="12" r="10" />
                                                                <polyline points="12 6 12 12 16 14" />
                                                            </svg>
                                                            Berjalan
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst($um->status) }}
                                                        </span>
                                                    @endif
                                                @endif

                                                @if(isset($um->progress))
                                                    <div class="text-muted small mt-1">
                                                        {{ $um->progress }} / {{ $mission->target ?? '-' }}
                                                        {{ $mission->unit ?? '' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            style="opacity:.4;">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                        <div class="mt-2 small">Belum ada peserta yang ikut misi ini.</div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.missions.index') }}" class="btn btn-light border px-4">
                                    Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection