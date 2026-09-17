@extends('template.layout')

@section('title', 'Dashboard Warga - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 class="fw-bold">
                        Selamat datang, {{ auth()->user()->name }}! 👋
                    </h3>
                    <p class="text-subtitle text-muted">
                        Yuk lanjutkan kebiasaan baikmu untuk lingkungan 🌱
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- ========================= --}}
            {{-- XP & POIN --}}
            {{-- ========================= --}}
            <div class="row g-3">
                {{-- ECO HABIT SCORE --}}
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                        <i class="bi bi-leaf-fill fs-1"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    @php
                                        $xp = auth()->user()->xp ?? 0;
                                        // max XP untuk hitung persen
                                        if ($xp >= 1000) $nextXp = 1000;
                                        elseif ($xp >= 801) $nextXp = 1000;
                                        elseif ($xp >= 501) $nextXp = 800;
                                        elseif ($xp >= 201) $nextXp = 500;
                                        else $nextXp = 200;
                                        $xpProgress = $nextXp > 0 ? min(100, round(($xp / $nextXp) * 100)) : 0;
                                    @endphp

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-uppercase small fw-semibold text-secondary">
                                                Eco Habit Score
                                            </div>
                                            <div class="fs-1 fw-bold">
                                                {{ number_format($xp) }}
                                                <span class="fs-6 text-secondary">XP</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fs-5 fw-bold text-success">
                                                {{ $xpProgress }}%
                                            </div>
                                            <small class="text-secondary">progress</small>
                                        </div>
                                    </div>

                                    <div class="progress mt-3" style="height: 9px;">
                                        <div class="progress-bar bg-success"
                                            role="progressbar"
                                            style="width: {{ $xpProgress }}%">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                            <i class="bi bi-trophy-fill me-1"></i>
                                            {{ auth()->user()->level_name ?? 'Green Newbie' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- POIN --}}
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                <i class="bi bi-coin fs-1"></i>
                            </div>
                            <div>
                                <div class="fs-1 fw-bold">
                                    {{ number_format(auth()->user()->points ?? 0) }}
                                </div>
                                <div class="text-uppercase small fw-semibold text-secondary">
                                    Poin
                                </div>
                                <small class="text-muted">
                                    Bisa digunakan untuk menukar reward
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- WASTE MISSION --}}
            {{-- ========================= --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">🎯 Waste Mission</h5>
                                <small class="text-muted">
                                    Tantang dirimu dengan misi ramah lingkungan
                                </small>
                            </div>
                            <a href="{{ route('user.user-missions.index') }}"
                                class="btn btn-sm btn-outline-success rounded-pill">
                                Lihat Semua
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                        <div class="card-body">
                            @forelse($userMissions ?? [] as $um)
                                @php
                                    $mission = $um->mission;
                                    $percent = $mission && $mission->target > 0
                                        ? min(100, round(($um->progress / $mission->target) * 100))
                                        : 0;
                                @endphp

                                @if($mission)
                                    <a href="{{ route('user.user-missions.show', $mission->mission_id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="d-flex align-items-center gap-3 p-3 mb-3 bg-light rounded-3">
                                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                                <i class="bi bi-recycle fs-3"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $mission->title }}</div>
                                                <small class="text-muted">
                                                    {{ \Illuminate\Support\Str::limit($mission->description, 50) }}
                                                </small>

                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar bg-success"
                                                        style="width: {{ $percent }}%"></div>
                                                </div>
                                                <small class="text-muted">
                                                    Progress {{ $um->progress }} / {{ $mission->target }}
                                                    {{ $mission->unit ?? 'item' }}
                                                    ({{ $percent }}%)
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">
                                                    +{{ $mission->reward_xp }} XP
                                                </span>
                                                <br>
                                                <span class="badge bg-warning text-dark mt-1">
                                                    +{{ $mission->reward_points }} Poin
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted mb-2">Kamu belum ikut misi apapun.</p>
                                    <a href="{{ route('user.user-missions.index') }}"
                                        class="btn btn-sm btn-success">
                                        Lihat Misi Tersedia
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection