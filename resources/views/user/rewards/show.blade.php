@extends('template.layout')

@section('title', 'Detail Reward')

@section('content')
    <a href="{{ route('user.rewards.index') }}" class="btn btn-sm btn-link ps-0 mb-3 text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Reward Center
    </a>

    <div class="row g-4">
        {{-- Kolom Kiri: Gambar Reward --}}
        <div class="col-md-5">
            <div class="card card-stat border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-center p-4">
                    @if($reward->image)
                        <img src="{{ asset('assets/images/' . $reward->image) }}"
                             class="img-fluid rounded"
                             style="max-height: 280px; object-fit: contain;"
                             alt="{{ $reward->name }}">
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                            <p class="mt-2 mb-0">Tidak ada gambar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Info & Tombol --}}
        <div class="col-md-7">
            <div class="card card-stat border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    {{-- Badge Status Stok --}}
                    <div class="mb-3">
                        @if($reward->stock > 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-check-circle"></i> Tersedia
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                <i class="bi bi-x-circle"></i> Stok Habis
                            </span>
                        @endif
                    </div>

                    {{-- Nama & Deskripsi --}}
                    <h3 class="fw-bold mb-2">{{ $reward->name }}</h3>
                    <p class="text-muted mb-4">
                        {{ $reward->description ?? 'Tidak ada deskripsi.' }}
                    </p>

                    {{-- Info Poin & Stok --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center bg-light">
                                <div class="small text-muted mb-1">Poin Dibutuhkan</div>
                                <div class="fs-4 fw-bold text-warning">
                                    ⭐ {{ number_format($reward->point_required) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center bg-light">
                                <div class="small text-muted mb-1">Stok Tersedia</div>
                                <div class="fs-4 fw-bold text-primary">
                                    {{ $reward->stock }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Poin User --}}
                    <div class="alert {{ $user->points >= $reward->point_required ? 'alert-success' : 'alert-warning' }} d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="small">Poin Kamu Saat Ini</div>
                            <div class="fw-bold">⭐ {{ number_format($user->points) }}</div>
                        </div>
                        <div class="text-end">
                            <div class="small">Sisa Setelah Tukar</div>
                            <div class="fw-bold">
                                ⭐ {{ number_format(max(0, $user->points - $reward->point_required)) }}
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    @if($user->points >= $reward->point_required && $reward->stock > 0)
                        <form action="{{ route('user.rewards.redeem', $reward->reward_id) }}"
                              method="POST"
                              onsubmit="return confirm('Tukar {{ number_format($reward->point_required) }} poin dengan {{ $reward->name }}?\n\nPoin kamu akan berkurang dan tidak bisa dikembalikan.');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="bi bi-gift"></i> Tukar Sekarang
                            </button>
                        </form>
                    @elseif($reward->stock <= 0)
                        <button class="btn btn-secondary w-100 py-2" disabled>
                            <i class="bi bi-x-circle"></i> Stok Habis
                        </button>
                    @else
                        <button class="btn btn-outline-secondary w-100 py-2" disabled>
                            <i class="bi bi-exclamation-circle"></i>
                            Poin Kurang {{ number_format($reward->point_required - $user->points) }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection