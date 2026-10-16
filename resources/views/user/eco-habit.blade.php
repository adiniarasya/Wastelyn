@extends('template.layout')

@section('title', 'Eco Habit Score - WasteLyn')

@section('content')
    <style>
        /* Kotak ikon: ikon pas di tengah, ukuran tetap bawaan Mazer */
        .eco-habit .stats-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .eco-habit .stats-icon i {
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

        .eco-habit .stats-icon i::before {
            display: block;
            line-height: 1;
            vertical-align: 0;
        }

        .eco-habit .stats-icon.wl-green {
            background-color: #2E7D32;
        }

        /* Lebar kolom ikon di tabel riwayat */
        .eco-habit .col-icon {
            width: 50px;
        }
    </style>

    @php
        $xp = $userXp->xp ?? 0;
        $level = $userXp->level ?? 1;
        $levelName = $userXp->level_name ?? 'Green Newbie';
        $nextXp = $userXp->next_level_xp ?? null;
        $progress = $userXp->progress_percent ?? 0;
    @endphp

    <div class="page-heading eco-habit">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Eco Habit Score</h3>
                    <p class="text-subtitle text-muted">
                        Konsistensi kebiasaan ramah lingkunganmu.
                    </p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Eco Habit Score</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="row">
                {{-- Level dan XP --}}
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card h-100 mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                <div class="stats-icon wl-green mb-0 flex-shrink-0">
                                    <i class="bi bi-trophy-fill fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted font-semibold mb-1">Level kamu</h6>
                                    <h4 class="font-extrabold mb-0">{{ $levelName }}</h4>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end mt-4">
                                <div>
                                    <h2 class="font-extrabold mb-0">{{ number_format($xp) }}</h2>
                                    <small class="text-muted">Total Eco Habit Score</small>
                                </div>
                                @if($nextXp)
                                    <div class="text-end">
                                        <h5 class="font-bold mb-0">{{ $progress }}%</h5>
                                        <small class="text-muted">
                                            {{ number_format($nextXp - $xp) }} XP lagi
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="progress progress-success mt-3">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"
                                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">
                                @if($nextXp)
                                    {{ number_format($xp) }} / {{ number_format($nextXp) }} XP menuju level berikutnya
                                @else
                                    Level maksimal tercapai
                                @endif
                            </small>

                            @if($user->wasteBank)
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

                {{-- Cara dapat XP --}}
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card h-100 mb-0">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cara dapat XP</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center justify-content-between px-0">
                                    <span>
                                        <i class="bi bi-truck text-success me-2"></i>Setor sampah
                                    </span>
                                    <span class="badge bg-light-success">+20 XP/kg</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center justify-content-between px-0">
                                    <span>
                                        <i class="bi bi-bullseye text-success me-2"></i>Selesaikan misi
                                    </span>
                                    <span class="badge bg-light-success">+1 s/d +10 XP</span>
                                </div>
                                <div class="list-group-item d-flex align-items-center justify-content-between px-0">
                                    <span>
                                        <i class="bi bi-fire text-success me-2"></i>Konsisten 4 minggu
                                    </span>
                                    <span class="badge bg-light-success">+50 XP</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat XP (gaya tabel tidak diubah) --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Riwayat XP
                    </h5>
                    <span class="badge bg-secondary">{{ $logs->total() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-icon"></th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th class="text-end">XP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="text-center">
                                            <i class="bi {{ $log->source_icon }} text-success fs-5"></i>
                                        </td>
                                        <td>
                                            {{ $log->description ?? $log->source_label }}
                                            <span class="badge bg-light text-secondary ms-1">
                                                {{ $log->source_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log->created_at->format('d M Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            +{{ number_format($log->xp) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada riwayat XP. Mulai setor sampah atau ikuti misi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                    <div class="card-footer">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
@endsection