@extends('template.layout')

@section('title', 'Waste Mission - WasteLyn')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <h3 class="fw-bold">🎯 Waste Mission</h3>
                <p class="text-subtitle text-muted">
                    Ikuti berbagai misi untuk membangun kebiasaan ramah lingkungan 🌱
                </p>
            </div>
            <div class="col-12 col-md-4">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Waste Mission
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @forelse($missions as $mission)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-recycle fs-1"></i>
                            </div>
                        </div>

                        <div class="col">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $mission->title }}
                                    </h5>
                                    <p class="text-muted mb-3">
                                        {{ $mission->description }}
                                    </p>
                                </div>

                                <span class="badge bg-success">
                                    Aktif
                                </span>
                            </div>

                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Target</small>
                                    <span class="fw-semibold">
                                        {{ $mission->target }}
                                    </span>
                                </div>

                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Reward XP</small>
                                    <span class="fw-semibold text-success">
                                        +{{ $mission->reward_xp }} XP
                                    </span>
                                </div>

                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Reward Poin</small>
                                    <span class="fw-semibold text-warning">
                                        +{{ $mission->reward_points }} Poin
                                    </span>
                                </div>

                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Periode</small>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($mission->start_date)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($mission->end_date)->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-auto mt-3 mt-md-0">
                            <a href="#" class="btn btn-success rounded-pill px-4">
                                Lihat Detail
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-5">
                    <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                    <h5 class="fw-bold mt-3">
                        Belum Ada Mission
                    </h5>
                    <p class="text-muted mb-0">
                        Saat ini belum ada Waste Mission yang tersedia.
                    </p>
                </div>
            </div>
        @endforelse
    </section>
</div>
@endsection