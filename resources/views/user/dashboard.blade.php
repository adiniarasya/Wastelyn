@extends('template.layout')

@section('title', 'Dashboard Warga - WasteLyn')

@section('content')
@dump(session('streak_bonus'))

<style>
    /* Warna ikon: satu keluarga hijau, poin kuning. Ukuran tetap bawaan Mazer. */
    .dashboard-warga .stats-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Reset posisi ikon bawaan Mazer supaya pas di tengah kotak */
    .dashboard-warga .stats-icon i {
        display: flex;
        align-items: center;
        justify-content: center;
        width: auto;
        height: auto;
        margin: 0;
        padding: 0;
        line-height: 1;
        color: #fff;
    }

    .dashboard-warga .stats-icon i::before {
        display: block;
        line-height: 1;
        vertical-align: 0;
    }

    .dashboard-warga .stats-icon.wl-green {
        background-color: #2E7D32;
    }

    .dashboard-warga .stats-icon.wl-leaf {
        background-color: #43A047;
    }

    .dashboard-warga .stats-icon.wl-teal {
        background-color: #00897B;
    }

    .dashboard-warga .stats-icon.wl-olive {
        background-color: #558B2F;
    }

    .dashboard-warga .stats-icon.wl-yellow {
        background-color: #FFC107;
    }
</style>

@php
$xp = $userXp->xp ?? 0;
$level = $userXp->level ?? 1;
$levelName = $userXp->level_name ?? 'Green Newbie';
$nextXp = $userXp->next_level_xp ?? null;
$progress = $userXp->progress_percent ?? 0;
@endphp

<div class="page-heading dashboard-warga">
    <div class="page-title">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Selamat datang, {{ auth()->user()->name }}</h3>
                <p class="text-subtitle text-muted">
                    Lanjutkan kebiasaan baikmu untuk lingkungan hari ini.
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

        {{-- Ringkasan: XP dan poin --}}
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stats-icon wl-green mb-0 flex-shrink-0">
                                <i class="bi bi-lightning-charge-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1 d-flex justify-content-between align-items-end">
                                <div>
                                    <h6 class="text-muted font-semibold mb-1">Eco Habit Score</h6>
                                    <h4 class="font-extrabold mb-0">
                                        {{ number_format($xp) }}
                                        <small class="text-muted fw-normal fs-6">XP</small>
                                    </h4>
                                </div>
                                <div class="text-end">
                                    <h5 class="font-bold mb-0">{{ $progress }}%</h5>
                                    <small class="text-muted">menuju level berikutnya</small>
                                </div>
                            </div>
                        </div>

                        <div class="progress progress-success mt-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                            <span class="badge bg-light-success">
                                <i class="bi bi-trophy-fill me-1"></i>{{ $levelName }}
                            </span>

                            @if($nextXp)
                            <small class="text-muted">
                                {{ number_format($xp) }} / {{ number_format($nextXp) }} XP
                            </small>
                            @else
                            <small class="text-muted">Level maksimal tercapai</small>
                            @endif
                        </div>

                        @if(auth()->user()->wasteBank)
                        <div class="border-top mt-3 pt-3">
                            <small class="text-muted">
                                <i class="bi bi-shop me-1"></i>
                                XP dari <strong>{{ auth()->user()->wasteBank->name }}</strong>
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-4 d-flex justify-content-start">
                                <div class="stats-icon wl-yellow mb-2">
                                    <i class="bi bi-coin fs-4"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <h6 class="text-muted font-semibold">Poin kamu</h6>
                                <h6 class="font-extrabold mb-0">
                                    {{ number_format(auth()->user()->points ?? 0) }}
                                </h6>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Tukarkan dengan reward yang tersedia.
                        </small>
                    </div>
                </div>
            </div>
        </div>


        {{-- Streak konsisten --}}
        @php
        $streak = auth()->user()->current_streak ?? 0;
        $streakInCycle = $streak % 4;
        if ($streak > 0 && $streakInCycle === 0) {
        $streakInCycle = 4;
        }
        $sisa = 4 - $streakInCycle;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Judul dan jumlah streak --}}
                        <div class="d-flex align-items-center gap-3">
                            <div class="stats-icon bg-danger mb-0 flex-shrink-0">
                                <i class="bi bi-fire fs-4"></i>
                            </div>
                            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted font-semibold mb-1">Streak konsisten</h6>
                                    <h4 class="font-extrabold mb-0">
                                        {{ $streak }}
                                        <small class="text-muted fw-normal fs-6">minggu</small>
                                    </h4>
                                </div>
                                <span class="badge bg-light-danger">
                                    {{ $streakInCycle }}/4 menuju bonus
                                </span>
                            </div>
                        </div>

                        {{-- Empat segmen = empat minggu dalam satu siklus --}}
                        <div class="row g-2 mt-4">
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="col-3">
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ $i <= $streakInCycle ? 100 : 0 }}%"></div>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Minggu {{ $i }}@if ($i === 4) &middot; +50 XP @endif
                                </small>
                        </div>
                        @endfor
                    </div>

                    {{-- Keterangan --}}
                    <div class="border-top mt-3 pt-3">
                        <small class="text-muted">
                            @if ($streak >= 4)
                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                            Konsisten {{ $streak }} minggu. Terus pertahankan.
                            @elseif ($streak > 0)
                            <i class="bi bi-info-circle me-1"></i>
                            Aktif 1x seminggu untuk mempertahankan streak.
                            Kurang {{ $sisa }} minggu lagi untuk bonus <strong>+50 XP</strong>.
                            @else
                            <i class="bi bi-info-circle me-1"></i>
                            Aktif minggu ini untuk memulai streak.
                            @endif
                        </small>
                    </div>

                </div>
            </div>
        </div>
</div>

{{-- Statistik lingkungan --}}
<div class="row">
    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-4 d-flex justify-content-start">
                        <div class="stats-icon wl-leaf mb-2">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h6 class="text-muted font-semibold">Uang diperoleh</h6>
                        <h6 class="font-extrabold mb-0">
                            Rp {{ number_format($totalUang ?? 0, 0, ',', '.') }}
                        </h6>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Total dari setoran sampahmu</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-4 d-flex justify-content-start">
                        <div class="stats-icon wl-teal mb-2">
                            <i class="bi bi-cloud-minus-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h6 class="text-muted font-semibold">CO₂ berkurang</h6>
                        <h6 class="font-extrabold mb-0">
                            {{ number_format($totalCo2 ?? 0, 2, ',', '.') }} kg
                        </h6>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Estimasi dampak positifmu</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-4 d-flex justify-content-start">
                        <div class="stats-icon wl-olive mb-2">
                            <i class="bi bi-recycle fs-4"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <h6 class="text-muted font-semibold">Sampah terkelola</h6>
                        <h6 class="font-extrabold mb-0">
                            {{ number_format($totalBerat ?? 0, 1, ',', '.') }} kg
                        </h6>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Total sampah yang kamu setor</small>
            </div>
        </div>
    </div>
</div>

{{-- Waste mission --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-1">
                        <i class="bi bi-bullseye text-success me-2"></i>Waste Mission
                    </h4>
                    <small class="text-muted">Selesaikan misi untuk mendapat XP dan poin</small>
                </div>
                <a href="{{ route('user.user-missions.index') }}" class="btn btn-sm btn-outline-success">
                    Lihat semua
                </a>
            </div>

            <div class="card-body">
                @if(($userMissions ?? collect())->isNotEmpty())
                <div class="list-group list-group-flush">
                    @foreach($userMissions as $um)
                    @php
                    $mission = $um->mission;
                    $percent = $mission && $mission->target > 0
                    ? min(100, round(($um->progress / $mission->target) * 100))
                    : 0;
                    @endphp

                    @if($mission)
                    <a href="{{ route('user.user-missions.show', $mission->mission_id) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                        <div class="stats-icon wl-green mb-0 flex-shrink-0">
                            <i class="bi bi-flag-fill fs-4"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $mission->title }}</div>
                            <small class="text-muted d-block">
                                {{ \Illuminate\Support\Str::limit($mission->description, 60) }}
                            </small>

                            <div class="progress progress-success mt-2">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $percent }}%"
                                    aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">
                                {{ $um->progress }} / {{ $mission->target }}
                                {{ $mission->unit ?? 'item' }} ({{ $percent }}%)
                            </small>
                        </div>

                        <div class="text-end flex-shrink-0">
                            <span class="badge bg-light-success d-block mb-1">
                                +{{ $mission->reward_xp }} XP
                            </span>
                            <span class="badge bg-light-warning d-block">
                                +{{ $mission->reward_points }} Poin
                            </span>
                        </div>
                    </a>
                    @endif
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-flag text-muted fs-1"></i>
                    <p class="text-muted my-2">Kamu belum mengikuti misi apa pun.</p>
                    <a href="{{ route('user.user-missions.index') }}" class="btn btn-sm btn-success">
                        Lihat misi tersedia
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

</section>
</div>

@if(!$user->onboarding_completed || !$user->waste_bank_id)
@include('user.onboarding.popup')
@endif

{{-- Modal streak bonus --}}
@if(session('streak_bonus'))
@php $bonus = session('streak_bonus'); @endphp

<style>
    /* Header modal memakai hijau utama WasteLyn */
    #streakBonusModal .streak-header {
        background-color: #2E7D32;
    }

    #streakBonusModal .streak-header-icon {
        width: 72px;
        height: 72px;
    }
</style>

<div class="modal fade" id="streakBonusModal" tabindex="-1" aria-labelledby="streakBonusTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 text-center overflow-hidden">

            {{-- Header --}}
            <div class="streak-header py-4 px-3">
                <div class="streak-header-icon bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-fire text-danger fs-1"></i>
                </div>
                <h4 id="streakBonusTitle" class="text-white mb-1">Selamat!</h4>
                <p class="text-white mb-0">Kamu konsisten menjaga kebiasaan baikmu.</p>
            </div>

            {{-- Isi --}}
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="rounded-3 bg-light-danger p-3 h-100">
                            <i class="bi bi-fire text-danger fs-3 d-block mb-1"></i>
                            <div class="fw-bold fs-4">{{ $bonus['streak'] }}</div>
                            <small class="text-muted">Minggu streak</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-3 bg-light-success p-3 h-100">
                            <i class="bi bi-lightning-charge-fill text-success fs-3 d-block mb-1"></i>
                            <div class="fw-bold fs-4 text-success">+{{ $bonus['xp'] }} XP</div>
                            <small class="text-muted">Bonus didapat</small>
                        </div>
                    </div>
                </div>

                <p class="text-muted mb-4">
                    Kamu berhasil konsisten selama <strong>{{ $bonus['streak'] }} minggu</strong>
                    berturut-turut. Bonus XP sudah ditambahkan ke Eco Habit Score kamu.
                </p>

                <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">
                    <i class="bi bi-check-circle me-1"></i>Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Tampilkan modal otomatis --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('streakBonusModal')).show();
    });
</script>

@php session()->forget('streak_bonus'); @endphp
@endif

@endsection