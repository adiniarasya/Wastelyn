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
                            <li class="breadcrumb-item active" aria-current="page">
                                Dashboard
                            </li>
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
                                {{-- ICON --}}
                                <div class="col-auto">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                        <i class="bi bi-leaf-fill fs-1"></i>
                                    </div>
                                </div>

                                {{-- XP --}}
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-uppercase small fw-semibold text-secondary">
                                                Eco Habit Score
                                            </div>
                                            <div class="fs-1 fw-bold">
                                                {{ number_format($totalXp ?? 0) }}
                                                <span class="fs-6 text-secondary">XP</span>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <div class="fs-5 fw-bold text-success">
                                                {{ $xpProgress ?? 0 }}%
                                            </div>
                                            <small class="text-secondary">progress</small>
                                        </div>
                                    </div>

                                    {{-- PROGRESS BAR --}}
                                    <div class="progress mt-3" style="height: 9px;">
                                        <div
                                            class="progress-bar bg-success"
                                            role="progressbar"
                                            style="width: {{ $xpProgress ?? 0 }}%"
                                            aria-valuenow="{{ $xpProgress ?? 0 }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    {{-- LEVEL --}}
                                    <div class="mt-3">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                            <i class="bi bi-trophy-fill me-1"></i>
                                            {{ $levelName ?? 'Eco Beginner' }}
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
                                    {{ number_format($totalPoints ?? 0) }}
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
            {{-- STATISTIK LINGKUNGAN --}}
            {{-- ========================= --}}
            <div class="row mt-4 g-3">
                {{-- STREAK --}}
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                                <i class="bi bi-fire fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">
                                    {{ $streak ?? 0 }}
                                    <span class="fs-6 text-secondary">Hari</span>
                                </div>
                                <div class="text-uppercase small fw-semibold text-secondary">
                                    Streak
                                </div>
                                <small class="text-muted">
                                    Pertahankan konsistensimu 🔥
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SAMPAH --}}
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3">
                                <i class="bi bi-recycle fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">
                                    {{ number_format($totalWaste ?? 0, 1, ',', '.') }}
                                    <span class="fs-6 text-secondary">Kg</span>
                                </div>
                                <div class="text-uppercase small fw-semibold text-secondary">
                                    Sampah Terkelola
                                </div>
                                <small class="text-muted">
                                    Total sampah yang kamu setorkan
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CO2 --}}
                <div class="col-12 col-md-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                <i class="bi bi-globe-asia-australia fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-2 fw-bold">
                                    {{ number_format($totalCo2 ?? 0, 1, ',', '.') }}
                                    <span class="fs-6 text-secondary">Kg</span>
                                </div>
                                <div class="text-uppercase small fw-semibold text-secondary">
                                    CO₂ Berkurang
                                </div>
                                <small class="text-muted">
                                    Estimasi dampak positifmu
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- WASTE MISSION PREVIEW --}}
            {{-- ========================= --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        {{-- HEADER --}}
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">
                                    🎯 Waste Mission
                                </h5>
                                <small class="text-muted">
                                    Tantang dirimu dengan misi ramah lingkungan
                                </small>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-success rounded-pill">
                                Lihat Semua
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                        {{-- BODY --}}
                        <div class="card-body">
                            {{-- MISSION 1 --}}
                            <div class="d-flex align-items-center gap-3 p-3 mb-3 bg-light rounded-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                    <i class="bi bi-recycle fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">
                                        Pilah Sampah Rumah Tangga
                                    </div>
                                    <small class="text-muted">
                                        Pisahkan sampah sesuai jenisnya
                                    </small>

                                    {{-- PROGRESS --}}
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 60%;"></div>
                                    </div>
                                    <small class="text-muted">
                                        Progress 60%
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success">
                                        +50 XP
                                    </span>
                                </div>
                            </div>

                            {{-- MISSION 2 --}}
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                    <i class="bi bi-bottle fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">
                                        Daur Ulang Botol Plastik
                                    </div>
                                    <small class="text-muted">
                                        Kumpulkan dan daur ulang botol plastik
                                    </small>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: 30%;"></div>
                                    </div>
                                    <small class="text-muted">
                                        Progress 30%
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">
                                        +100 XP
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection