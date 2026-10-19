@extends('template.layout')

@section('title', 'Detail Reward - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $reward->name }}</h4>
            <p class="text-muted small mb-0">Detail lengkap reward</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mitra.rewards.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('mitra.rewards.edit', $reward->reward_id) }}" class="btn btn-success btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Reward
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Informasi</h6>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Nama Reward</span>
                        <span class="small fw-semibold">{{ $reward->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Poin Dibutuhkan</span>
                        <span class="small fw-semibold">{{ number_format($reward->point_required, 0, ',', '.') }} Poin</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Stok</span>
                        <span class="small">{{ $reward->stock }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Status</span>
                        <span>
                            @if($reward->status == 'available')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Tersedia</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Tidak Tersedia</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Deskripsi & Gambar</h6>

                    <p class="small text-muted">{{ $reward->description ?? 'Tidak ada deskripsi.' }}</p>

                    @if($reward->image)
                        <div class="mt-3">
                            <img src="{{ asset('assets/images/' . $reward->image) }}"
                                 alt="{{ $reward->name }}"
                                 class="img-fluid rounded border"
                                 style="max-height: 200px;">
                        </div>
                    @else
                        <p class="small text-muted mb-0">Tidak ada gambar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection