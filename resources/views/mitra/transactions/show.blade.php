@extends('template.layout')

@section('title', 'Detail Transaksi - WasteLyn')

@section('content')
    <h4 class="fw-bold mb-1">Detail Transaksi</h4>
    <p class="text-muted small mb-4">Informasi lengkap transaksi poin warga</p>

    <div class="card border-0 shadow-sm" style="max-width: 640px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <div class="text-muted small mb-1">Warga</div>
                    <div class="fw-semibold">{{ $transaction->user->name ?? '-' }}</div>
                </div>
                <div class="text-end">
                    <div class="text-muted small mb-1">Tanggal</div>
                    <div class="small">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
            <div class="p-3 rounded mb-4" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small mb-1">
                            {{ $transaction->type === 'earn' ? 'Poin Didapat' : 'Poin Ditukar' }}
                        </div>
                        <div class="fs-4 fw-bold text-{{ $transaction->type === 'earn' ? 'success' : 'danger' }}">
                            {{ $transaction->type === 'earn' ? '+' : '-' }}{{ number_format($transaction->points) }}
                        </div>
                    </div>
                    @if(isset($transaction->xp_earned) && $transaction->xp_earned > 0)
                        <div class="text-end">
                            <div class="text-muted small mb-1">XP</div>
                            <div class="fw-semibold text-primary">+{{ $transaction->xp_earned }} XP</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Tipe Transaksi</span>
                    <span class="small fw-semibold text-capitalize">
                        {{ $transaction->type === 'earn' ? 'Dapat Poin' : 'Tukar Poin' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Deskripsi</span>
                    <span class="small text-end" style="max-width: 60%;">
                        {{ $transaction->description ?? '-' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted small">Status</span>
                    <span class="small text-success fw-semibold">Selesai</span>
                </div>
            </div>
            @if ($transaction->pickupRequest)
                <div class="mt-4">
                    <div class="text-muted small fw-semibold mb-2 text-uppercase" style="letter-spacing: 0.5px;">
                        Setoran Terkait
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Jenis Sampah</span>
                        <span class="small fw-semibold">
                            {{ $transaction->pickupRequest->wasteCategory->name ?? $transaction->pickupRequest->jenis_sampah ?? '-' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Berat</span>
                        <span class="small">{{ $transaction->pickupRequest->berat_aktual ?? 0 }} kg</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Harga per Kg</span>
                        <span class="small">
                            Rp {{ number_format($transaction->pickupRequest->price_per_kg ?? 0, 0, ',', '.') }}
                            @if($transaction->pickupRequest->price_per_kg == $transaction->pickupRequest->wasteCategory->price_per_kg)
                                <span class="text-muted">(Default)</span>
                            @else
                                <span class="text-success">(Harga Mitra)</span>
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 mt-1">
                        <span class="text-muted small fw-semibold">Total Harga</span>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($transaction->pickupRequest->total_harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endif
            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('mitra.transactions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>
@endsection