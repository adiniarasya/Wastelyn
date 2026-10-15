@extends('template.layout')

@section('title', 'Waste Mission - WasteLyn')

@section('content')
    <style>
        /* Ukuran gambar / ikon misi dibuat seragam */
        .waste-mission .mission-thumb {
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            object-fit: cover;
        }

        .waste-mission .mission-thumb i {
            font-size: 2rem;
            line-height: 1;
        }

        /* Garis pemisah antar info di dalam kartu */
        .waste-mission .mission-meta {
            border-top: 1px solid #dee2e6;
        }
    </style>





    <div class="page-heading waste-mission">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h3>Waste Mission</h3>
                    <p class="text-subtitle text-muted">
                        Ikuti berbagai misi untuk membangun kebiasaan ramah lingkungan.
                    </p>
                </div>
                <div class="col-12 col-md-4">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Waste Mission</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            @forelse($missions as $mission)
                <div class="card">
                    <div class="card-body">
                        {{-- Judul, deskripsi, status --}}
                        <div class="d-flex align-items-start gap-3">
                            @if($mission->image)
                                <img src="{{ asset('storage/' . $mission->image) }}"
                                    alt="{{ $mission->title }}" class="mission-thumb rounded-3">
                            @else
                                <div class="mission-thumb d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-recycle"></i>
                                </div>
                            @endif

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <h5 class="mb-1">{{ $mission->title }}</h5>
                                    <span class="badge bg-success flex-shrink-0">Aktif</span>
                                </div>
                                <p class="text-muted mb-0">{{ $mission->description }}</p>
                            </div>
                        </div>

                        {{-- Detail misi --}}
                        <div class="mission-meta mt-3 pt-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-6 col-md-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-bullseye me-1"></i>Target
                                    </small>
                                    <span class="fw-semibold">{{ $mission->target }}</span>
                                </div>

                                <div class="col-6 col-md-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-lightning-charge-fill me-1"></i>Reward XP
                                    </small>
                                    <span class="fw-semibold text-success">+{{ $mission->reward_xp }} XP</span>
                                </div>

                                <div class="col-6 col-md-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-coin me-1"></i>Reward poin
                                    </small>
                                    <span class="fw-semibold text-warning">+{{ $mission->reward_points }} Poin</span>
                                </div>

                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-calendar-event me-1"></i>Periode
                                    </small>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($mission->start_date)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($mission->end_date)->format('d M Y') }}
                                    </span>
                                </div>

                                <div class="col-12 col-md-3 text-md-end">
                                    <a href="{{ route('user.user-missions.show', $mission->mission_id) }}"
                                        class="btn btn-success">
                                        Lihat detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-flag fs-1 text-muted"></i>
                        <h5 class="mt-3">Belum ada misi</h5>
                        <p class="text-muted mb-0">Saat ini belum ada Waste Mission yang tersedia.</p>
                    </div>
                </div>
            @endforelse
        </section>
    </div>
@endsection