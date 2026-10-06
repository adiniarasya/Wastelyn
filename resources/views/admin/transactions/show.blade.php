@extends('template.layout')

@section('title', 'Detail Transaksi - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Detail Transaksi</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Informasi lengkap transaksi #{{ $transaction->transaction_id }}
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Transaksi #{{ $transaction->transaction_id }}</h5>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.transactions.pdf', $transaction->transaction_id) }}"
                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="12" y1="18" x2="12" y2="12"/>
                                <polyline points="9 15 12 12 15 15"/>
                            </svg>
                            Download PDF
                        </a>

                        <a href="{{ route('admin.transactions.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12 19 5 12 12 5"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body pt-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10">

                            {{-- Header Poin --}}
                            <div class="text-center mb-4">
                                @if($transaction->type == 'earn')
                                    <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                        style="width:90px;height:90px;background:#E6F7EE;color:#2E7D32;">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                            <polyline points="19 12 12 5 5 12"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                        style="width:90px;height:90px;background:#FFEBEE;color:#C62828;">
                                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                            <polyline points="5 12 12 19 19 12"/>
                                        </svg>
                                    </div>
                                @endif

                                <h3 class="fw-bold mt-3 mb-1 {{ $transaction->type == 'earn' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type == 'earn' ? '+' : '-' }}{{ number_format($transaction->points ?? 0, 0, ',', '.') }} Poin
                                </h3>
                                <p class="text-muted small mb-0">
                                    {{ $transaction->type == 'earn' ? 'Poin Masuk' : 'Poin Keluar' }}
                                </p>
                            </div>

                            {{-- Stat Cards --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-4">
                                    <div class="text-center p-3 rounded-3" style="background:#E7F1FF;">
                                        <div class="fw-bold fs-5" style="color:#435EBE;">
                                            {{ $transaction->xp_earned ?? 0 }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">XP</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="text-center p-3 rounded-3" style="background:#E6F7EE;">
                                        <div class="fw-bold fs-5" style="color:#2E7D32;">
                                            {{ number_format($transaction->points ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">Poin</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="text-center p-3 rounded-3" style="background:#FFF4E0;">
                                        <div class="fw-bold fs-6" style="color:#D97706;">
                                            {{ $transaction->created_at?->format('d M Y') ?? '-' }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">Tanggal</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Info User --}}
                            <div class="border-top pt-3 mb-3">
                                <h6 class="fw-bold mb-3">Informasi User</h6>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">Nama</div>
                                    <div class="col-8 col-md-9">
                                        <span class="fw-semibold">{{ $transaction->user->name ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">Email</div>
                                    <div class="col-8 col-md-9 text-muted">{{ $transaction->user->email ?? '-' }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">No HP</div>
                                    <div class="col-8 col-md-9 text-muted">{{ $transaction->user->phone ?? '-' }}</div>
                                </div>
                            </div>

                            {{-- Info Setoran --}}
                            @if($transaction->pickupRequest)
                                <div class="border-top pt-3 mb-3">
                                    <h6 class="fw-bold mb-3">Informasi Setoran</h6>

                                    <div class="row mb-3">
                                        <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                            Jenis Sampah
                                        </div>
                                        <div class="col-8 col-md-9">
                                            <span class="fw-semibold">
                                                {{ $transaction->pickupRequest->wasteCategory->name ?? '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                            Berat
                                        </div>
                                        <div class="col-8 col-md-9">
                                            <span class="fw-semibold text-success">
                                                {{ number_format($transaction->pickupRequest->weight_kg ?? 0, 2, ',', '.') }} Kg
                                            </span>
                                        </div>
                                    </div>

                                    @if($transaction->pickupRequest->co2_saved)
                                        <div class="row mb-3">
                                            <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                                CO₂ Tersimpan
                                            </div>
                                            <div class="col-8 col-md-9 text-muted">
                                                {{ number_format($transaction->pickupRequest->co2_saved, 2, ',', '.') }} Kg
                                            </div>
                                        </div>
                                    @endif

                                    @if($transaction->pickupRequest->verified_at)
                                        <div class="row mb-3">
                                            <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                                Diverifikasi
                                            </div>
                                            <div class="col-8 col-md-9 text-muted">
                                                {{ $transaction->pickupRequest->verified_at->format('d F Y, H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($transaction->pickupRequest->bank)
                                    <div class="border-top pt-3 mb-3">
                                        <h6 class="fw-bold mb-3">Bank Sampah / Mitra</h6>

                                        <div class="row mb-3">
                                            <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                                Nama Bank
                                            </div>
                                            <div class="col-8 col-md-9">
                                                <span class="fw-semibold">
                                                    {{ $transaction->pickupRequest->bank->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                                Alamat
                                            </div>
                                            <div class="col-8 col-md-9 text-muted">
                                                {{ $transaction->pickupRequest->bank->address ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                                Telepon
                                            </div>
                                            <div class="col-8 col-md-9 text-muted">
                                                {{ $transaction->pickupRequest->bank->phone ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- Deskripsi --}}
                            @if($transaction->description)
                                <div class="border-top pt-3 mb-3">
                                    <h6 class="fw-bold mb-3">Deskripsi</h6>
                                    <div class="alert alert-light border small text-muted mb-0">
                                        {{ $transaction->description }}
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.transactions.index') }}"
                                    class="btn btn-light border px-4">
                                    Kembali
                                </a>
                                <a href="{{ route('admin.transactions.pdf', $transaction->transaction_id) }}"
                                    class="btn btn-danger px-4 d-inline-flex align-items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download PDF
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection