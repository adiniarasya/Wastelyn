@extends('template.layout')

@section('title', 'Detail Reward - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Detail Reward</h3>
                    <p class="text-subtitle text-muted mb-0">Informasi lengkap reward</p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Reward #{{ $reward->reward_id ?? $reward->id }}</h5>

                    <a href="{{ route('admin.rewards.index') }}"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="card-body pt-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">

                            <div class="text-center mb-4">
                                @if($reward->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($reward->image))
                                    <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}"
                                        class="rounded-4"
                                        style="width:120px;height:120px;object-fit:cover;border:3px solid #f1f3f5;">
                                @else
                                    <div class="rounded-4 d-inline-flex justify-content-center align-items-center"
                                        style="width:120px;height:120px;background:#E6F7EE;color:#2E7D32;border:3px solid #f1f3f5;">
                                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 12 20 22 4 22 4 12" />
                                            <rect x="2" y="7" width="20" height="5" />
                                            <line x1="12" y1="22" x2="12" y2="7" />
                                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                                        </svg>
                                    </div>
                                @endif

                                <h3 class="fw-bold mt-3 mb-1">{{ $reward->name ?? '-' }}</h3>

                                @if(($reward->status ?? '') === 'available')
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary">Habis</span>
                                @endif
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="text-center p-3 rounded-3" style="background:#E6F7EE;">
                                        <div class="fw-bold fs-5" style="color:#2E7D32;">
                                            {{ number_format($reward->point_required ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">Poin</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 rounded-3" style="background:#E7F1FF;">
                                        <div class="fw-bold fs-5" style="color:#435EBE;">
                                            {{ number_format($reward->stock ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">Stok</div>
                                    </div>
                                </div>
                            </div>

                            @if($reward->description)
                                <div class="border-top pt-3 mb-3">
                                    <h6 class="fw-bold mb-3">Deskripsi</h6>
                                    <div class="alert alert-light border small text-muted mb-0">
                                        {{ $reward->description }}
                                    </div>
                                </div>
                            @endif

                            <div class="border-top pt-3 mb-3">
                                <h6 class="fw-bold mb-3">Informasi Tambahan</h6>

                                <div class="row mb-2">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">Dibuat</div>
                                    <div class="col-8 col-md-9">
                                        {{ $reward->created_at?->format('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">Diupdate</div>
                                    <div class="col-8 col-md-9">
                                        {{ $reward->updated_at?->format('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.rewards.index') }}" class="btn btn-light border px-4">
                                    Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection