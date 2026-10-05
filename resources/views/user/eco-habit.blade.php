@extends('template.layout')

@section('title', 'Eco Habit Score - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <h3 class="fw-bold">🌱 Eco Habit Score</h3>
                    <p class="text-subtitle text-muted">
                        Konsistensi kebiasaan ramah lingkunganmu
                    </p>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            @php
                                $xp = $userXp->xp ?? 0;
                                $level = $userXp->level ?? 1;
                                $levelName = $userXp->level_name ?? 'Green Newbie';
                                $nextXp = $userXp->next_level_xp ?? null;
                                $progress = $userXp->progress_percent ?? 0;
                            @endphp

                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                                    <i class="bi bi-trophy-fill fs-2"></i>
                                </div>
                                <div>
                                    <div class="text-uppercase small fw-semibold text-secondary">
                                        Level Kamu
                                    </div>
                                    <div class="fs-4 fw-bold">{{ $levelName }}</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <div>
                                    <div class="fs-1 fw-bold">{{ number_format($xp) }}</div>
                                    <small class="text-muted">Total Eco Habit Score</small>
                                </div>
                                @if($nextXp)
                                    <div class="text-end">
                                        <div class="fs-5 fw-bold text-success">{{ $progress }}%</div>
                                        <small class="text-muted">
                                            {{ number_format($nextXp - $xp) }} XP lagi
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>

                            @if($nextXp)
                                <small class="text-muted mt-2 d-block">
                                    {{ number_format($xp) }} / {{ number_format($nextXp) }} XP
                                    menuju level berikutnya
                                </small>
                            @else
                                <small class="text-muted mt-2 d-block">
                                    🎉 Level maksimal tercapai
                                </small>
                            @endif

                            @if($user->wasteBank)
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-shop"></i>
                                        XP dari <strong>{{ $user->wasteBank->name }}</strong>
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">📊 Cara Dapat XP</h6>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-truck text-success"></i>
                                <span class="small">Setor sampah = <strong>+20 XP/kg</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-bullseye text-success"></i>
                                <span class="small">Selesaikan misi = <strong>+10 XP</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-fire text-success"></i>
                                <span class="small">Konsisten 4 minggu = <strong>+50 XP</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-book text-success"></i>
                                <span class="small">Baca artikel EcoTips = <strong>+5 XP</strong></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-trophy text-success"></i>
                                <span class="small">Ikut tantangan = <strong>+15 XP</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history"></i> Riwayat XP</span>
                    <span class="badge bg-secondary">{{ $logs->total() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50"></th>
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
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            Belum ada riwayat XP. Yuk mulai setor sampah atau ikuti misi!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($logs->hasPages())
                    <div class="card-footer bg-white">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
@endsection