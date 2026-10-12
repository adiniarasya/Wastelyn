@extends('template.layout')

@section('title', 'Detail Mission - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $mission->title }}</h4>
            <p class="text-muted small mb-0">Detail lengkap mission</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mitra.missions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('mitra.missions.edit', $mission->mission_id) }}" class="btn btn-success btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Mission
            </a>
        </div>
    </div>

    <div class="row g-3">
        {{-- KOLOM KIRI --}}
        <div class="col-md-6">
            {{-- Info Mission --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Informasi Mission</h6>
                        @if($mission->status == 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Nonaktif</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Bank Sampah</span>
                        <span class="small fw-semibold">{{ $mission->bank->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Target</span>
                        <span class="small">{{ $mission->target }} {{ $mission->unit ?? 'item' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Tipe Misi</span>
                        <span class="small">
                            @if($mission->type == 'quantitative')
                                <span class="badge bg-info-subtle text-info border border-info-subtle">Kuantitatif</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Kualitatif</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Reward XP</span>
                        <span class="small fw-semibold">{{ $mission->reward_xp }} XP</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Reward Poin</span>
                        <span class="small fw-semibold">{{ number_format($mission->reward_points, 0, ',', '.') }} Poin</span>
                    </div>
                </div>
            </div>

            {{-- Waktu --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Waktu</h6>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Tanggal Mulai</span>
                        <span class="small">{{ \Carbon\Carbon::parse($mission->start_date)->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Tanggal Berakhir</span>
                        <span class="small">{{ \Carbon\Carbon::parse($mission->end_date)->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Dibuat</span>
                        <span class="small">{{ $mission->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Terakhir Update</span>
                        <span class="small">{{ $mission->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-md-6">
            {{-- Deskripsi --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Deskripsi Mission</h6>
                    <p class="small text-muted mb-0">{{ $mission->description ?? '-' }}</p>
                </div>
            </div>

            {{-- AI Prompt --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">AI Prompt</h6>
                    <div class="bg-light border rounded p-2">
                        <code class="small" style="white-space: pre-wrap;">{{ $mission->ai_prompt ?? '-' }}</code>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Warga (full width) --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Warga yang Mengikuti Misi</h6>
                    <span class="badge bg-light text-dark border">{{ $mission->userMissions->count() }} Warga</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Warga</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th class="pe-4">Kode Misi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mission->userMissions as $um)
                                @php
                                    $statusColor = [
                                        'ongoing' => 'warning',
                                        'ready_pickup' => 'info',
                                        'picked_up' => 'primary',
                                        'completed' => 'success',
                                    ][$um->status] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold small">{{ $um->user->name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="small">
                                            {{ $um->progress }} / {{ $mission->target }}
                                            {{ $mission->unit ?? 'item' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} border border-{{ $statusColor }}-subtle text-capitalize">
                                            {{ str_replace('_', ' ', $um->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <code class="small">{{ $um->unique_code ?? '-' }}</code>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        Belum ada warga yang mengikuti misi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection