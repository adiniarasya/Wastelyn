@extends('template.layout')

@section('title', 'Dashboard Warga - WasteLyn')

@php
    $user = $user ?? auth()->user();
    $id = fn($n, $d = 0) => number_format((float) $n, $d, ',', '.');

    $xp = $userXp->xp ?? 0;
    $levelName = $userXp->level_name ?? 'Green Newbie';
    $nextXp = $userXp->next_level_xp ?? null;
    $progress = max(0, min(100, $userXp->progress_percent ?? 0));

    $streakCycle = 4;
    $streakBonusXp = 50;
    $streak = $user->current_streak ?? 0;
    $streakInCycle = $streak % $streakCycle;
    if ($streak > 0 && $streakInCycle === 0) {
        $streakInCycle = $streakCycle;
    }
    $sisa = $streakCycle - $streakInCycle;

    $stats = [
        ['icon' => 'bi-wallet2', 'color' => 'wl-leaf', 'label' => 'Uang diperoleh', 'value' => 'Rp ' . $id($totalUang ?? 0), 'note' => 'Total dari setoran sampahmu'],
        ['icon' => 'bi-cloud-minus-fill', 'color' => 'wl-teal', 'label' => 'CO₂ berkurang', 'value' => $id($totalCo2 ?? 0, 2) . ' kg', 'note' => 'Estimasi dampak positifmu'],
        ['icon' => 'bi-recycle', 'color' => 'wl-olive', 'label' => 'Sampah terkelola', 'value' => $id($totalBerat ?? 0, 1) . ' kg', 'note' => 'Total sampah yang kamu setor'],
    ];

    $missionItems = collect($userMissions ?? [])
        ->filter(fn($um) => $um->mission)
        ->map(function ($um) {
            $target = $um->mission->target ?? 0;
            $um->percent = $target > 0 ? min(100, round(($um->progress / $target) * 100)) : 0;
            return $um;
        });

    $showOnboarding = !$user->onboarding_completed || !$user->waste_bank_id;
    $bonus = $streakBonus ?? null;
@endphp

@section('content')

    <style>
        .dashboard-warga .stats-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

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

        .dashboard-warga .stats-icon.wl-red {
            background-color: #DC3545;
        }

        #streakBonusModal .modal-dialog {
            max-width: 440px;
        }

        #streakBonusModal .modal-content {
            border: none;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35), 0 10px 30px rgba(46, 125, 50, .15);
        }

        #streakBonusModal .streak-header {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 45%, #43A047 100%);
            padding: 2rem 1.5rem 1.75rem;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        #streakBonusModal .streak-header::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, .18) 0%, transparent 70%);
            border-radius: 50%;
        }

        #streakBonusModal .streak-header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -50px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, rgba(255, 255, 255, .12) 0%, transparent 70%);
            border-radius: 50%;
        }

        #streakBonusModal .streak-header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, .15);
            border: 3px solid rgba(255, 255, 255, .35);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto .85rem;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .25), inset 0 2px 6px rgba(255, 255, 255, .2);
            position: relative;
            z-index: 2;
            backdrop-filter: blur(6px);
            line-height: 0;
            font-size: 0;
        }

        #streakBonusModal .streak-header-icon i {
            font-size: 2.25rem;
            color: #fff;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, .3));
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 0;
            width: 1em;
            height: 1em;
        }

        #streakBonusModal .streak-header-icon i::before {
            display: block;
            line-height: 0;
            vertical-align: middle;
            position: relative;
            top: 0.02em;
        }

        #streakBonusModal .streak-header h4 {
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -.4px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, .2);
            position: relative;
            z-index: 2;
            margin-bottom: .25rem;
            line-height: 1.2;
        }

        #streakBonusModal .streak-header p {
            font-size: .85rem;
            position: relative;
            z-index: 2;
            margin-bottom: 0;
            line-height: 1.4;
        }

        #streakBonusModal .modal-body {
            padding: 1.75rem 1.5rem 1.5rem;
            background: #fff;
        }

        #streakBonusModal .bonus-card {
            border-radius: 1.1rem;
            padding: 1.35rem .75rem;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            transition: transform .25s ease, box-shadow .25s ease;
            border: 1.5px solid transparent;
        }

        #streakBonusModal .bonus-card.bg-light-danger {
            border-color: rgba(220, 53, 69, .18);
            box-shadow: 0 6px 18px rgba(220, 53, 69, .1), inset 0 1px 3px rgba(255, 255, 255, .8);
        }

        #streakBonusModal .bonus-card.bg-light-success {
            border-color: rgba(67, 160, 71, .2);
            box-shadow: 0 6px 18px rgba(67, 160, 71, .12), inset 0 1px 3px rgba(255, 255, 255, .8);
        }

        #streakBonusModal .bonus-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, .1);
        }

        #streakBonusModal .bonus-card i {
            font-size: 2rem;
            line-height: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, .12));
        }

        #streakBonusModal .bonus-card i::before {
            display: block;
            line-height: 0;
            vertical-align: middle;
            position: relative;
            top: 0.02em;
        }

        #streakBonusModal .bonus-card .bonus-value {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
            letter-spacing: -1.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2rem;
        }

        #streakBonusModal .bonus-card small {
            font-size: .68rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-weight: 700;
            opacity: .6;
            line-height: 1.2;
            display: block;
        }

        #streakBonusModal .modal-body>p {
            font-size: .875rem;
            line-height: 1.65;
        }

        #streakBonusModal .btn-success {
            border-radius: 1rem;
            padding: .95rem 1rem;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .02em;
            background: linear-gradient(135deg, #2E7D32 0%, #43A047 100%);
            border: none;
            box-shadow: 0 8px 22px rgba(46, 125, 50, .4), inset 0 1px 2px rgba(255, 255, 255, .25);
            transition: transform .2s ease, box-shadow .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #streakBonusModal .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(46, 125, 50, .5), inset 0 1px 2px rgba(255, 255, 255, .25);
            background: linear-gradient(135deg, #2E7D32 0%, #43A047 100%);
        }

        #streakBonusModal .btn-success:active {
            transform: translateY(0);
        }

        #streakBonusModal .btn-success i {
            font-size: 1.05rem;
            line-height: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: .35rem;
        }

        #streakBonusModal .btn-success i::before {
            display: block;
            line-height: 0;
            vertical-align: middle;
        }

        @media (max-width: 480px) {
            #streakBonusModal .streak-header-icon {
                width: 68px;
                height: 68px;
            }

            #streakBonusModal .streak-header-icon i {
                font-size: 1.9rem;
            }

            #streakBonusModal .bonus-card .bonus-value,
            #streakBonusModal .bonus-card i {
                font-size: 1.75rem;
                height: 1.75rem;
                width: 1.75rem;
            }
        }
    </style>

    <div class="page-heading dashboard-warga">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Selamat datang, {{ $user->name }}</h3>
                    <p class="text-subtitle text-muted">Lanjutkan kebiasaan baikmu untuk lingkungan hari ini.</p>
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
                                            {{ $id($xp) }}
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
                                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                                <span class="badge bg-light-success">
                                    <i class="bi bi-trophy-fill me-1"></i>{{ $levelName }}
                                </span>
                                <small class="text-muted">
                                    @if ($nextXp)
                                        {{ $id($xp) }} / {{ $id($nextXp) }} XP
                                    @else
                                        Level maksimal tercapai
                                    @endif
                                </small>
                            </div>

                            @if ($user->wasteBank)
                                <div class="border-top mt-3 pt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shop me-1"></i>
                                        XP dari <strong>{{ $user->wasteBank->name }}</strong>
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
                                    <h6 class="font-extrabold mb-0">{{ $id($user->points ?? 0) }}</h6>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Tukarkan dengan reward yang tersedia.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-center gap-3">
                                <div class="stats-icon wl-red mb-0 flex-shrink-0">
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
                                        {{ $streakInCycle }}/{{ $streakCycle }} menuju bonus
                                    </span>
                                </div>
                            </div>

                            <div class="row g-2 mt-4">
                                @for ($i = 1; $i <= $streakCycle; $i++)
                                    <div class="col-3">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: {{ $i <= $streakInCycle ? 100 : 0 }}%"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            Minggu {{ $i }}
                                            @if ($i === $streakCycle) &middot; +{{ $streakBonusXp }} XP @endif
                                        </small>
                                    </div>
                                @endfor
                            </div>

                            <div class="border-top mt-3 pt-3">
                                <small class="text-muted">
                                    @if ($streak >= $streakCycle)
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        Konsisten {{ $streak }} minggu. Terus pertahankan.
                                    @elseif ($streak > 0)
                                        <i class="bi bi-info-circle me-1"></i>
                                        Aktif 1x seminggu untuk mempertahankan streak.
                                        Kurang {{ $sisa }} minggu lagi untuk bonus <strong>+{{ $streakBonusXp }} XP</strong>.
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

            <div class="row">
                @foreach ($stats as $stat)
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-4 d-flex justify-content-start">
                                        <div class="stats-icon {{ $stat['color'] }} mb-2">
                                            <i class="bi {{ $stat['icon'] }} fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <h6 class="text-muted font-semibold">{{ $stat['label'] }}</h6>
                                        <h6 class="font-extrabold mb-0">{{ $stat['value'] }}</h6>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">{{ $stat['note'] }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

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
                            @if ($missionItems->isNotEmpty())
                                <div class="list-group list-group-flush">
                                    @foreach ($missionItems as $um)
                                        @php $mission = $um->mission; @endphp
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
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $um->percent }}%"
                                                        aria-valuenow="{{ $um->percent }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $um->progress }} / {{ $mission->target }}
                                                    {{ $mission->unit ?? 'item' }} ({{ $um->percent }}%)
                                                </small>
                                            </div>

                                            <div class="text-end flex-shrink-0">
                                                <span class="badge bg-light-success d-block mb-1">+{{ $mission->reward_xp }}
                                                    XP</span>
                                                <span class="badge bg-light-warning d-block">+{{ $mission->reward_points }}
                                                    Poin</span>
                                            </div>
                                        </a>
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

    @if ($showOnboarding)
        @include('user.onboarding.popup')
    @endif

    @if ($bonus)
        <div class="modal fade" id="streakBonusModal" tabindex="-1" aria-labelledby="streakBonusTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="streak-header text-white">
                        <div class="streak-header-icon">
                            <i class="bi bi-fire"></i>
                        </div>
                        <h4 id="streakBonusTitle" class="text-white">Selamat!</h4>
                        <p class="text-white" style="opacity: .85;">Kamu konsisten menjaga kebiasaan baikmu.</p>
                    </div>

                    <div class="modal-body">

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bonus-card bg-light-danger">
                                    <i class="bi bi-fire text-danger"></i>
                                    <div class="bonus-value text-danger">{{ $bonus['streak'] }}</div>
                                    <small class="text-muted">Minggu streak</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bonus-card bg-light-success">
                                    <i class="bi bi-lightning-charge-fill text-success"></i>
                                    <div class="bonus-value text-success">+{{ $bonus['xp'] }}</div>
                                    <small class="text-muted">XP bonus</small>
                                </div>
                            </div>
                        </div>

                        <p class="text-muted text-center mb-4">
                            Kamu berhasil konsisten selama
                            <strong>{{ $bonus['streak'] }} minggu</strong> berturut-turut.
                            Bonus XP sudah ditambahkan ke Eco Habit Score kamu.
                        </p>

                        <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal">
                            <i class="bi bi-check-circle"></i>Lanjutkan
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('streakBonusModal');
                if (!el) return;

                if (window.bootstrap && typeof bootstrap.Modal === 'function' && bootstrap.Modal.prototype.getOrCreateInstance) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                } else if (window.jQuery && jQuery.fn.modal) {
                    jQuery(el).modal('show');
                } else if (window.bootstrap && typeof bootstrap.Modal === 'function') {
                    new bootstrap.Modal(el).show();
                } else {
                    el.style.display = 'block';
                    el.classList.add('show');
                    el.removeAttribute('aria-hidden');
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
            });
        </script>
    @endif

@endsection