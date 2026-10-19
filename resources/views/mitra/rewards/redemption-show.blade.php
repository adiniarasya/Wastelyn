@extends('template.layout')

@section('title', 'Bukti Penukaran Reward - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="bi bi-receipt"></i> Bukti Penukaran Reward
            </h3>
            <p class="text-muted small mb-0">Detail transaksi penukaran poin</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-success">
                <i class="bi bi-printer"></i> Cetak
            </button>
            <a href="{{ route('mitra.rewards.redemptions') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 800px;">
        <div class="card-body p-4">
            <div class="text-center border-bottom pb-3 mb-4">
                <h2 class="fw-bold text-success mb-1">
                    <i class="bi bi-recycle"></i> WasteLyn
                </h2>
                <p class="text-muted small mb-0">Platform Pembentukan Kebiasaan Pengelolaan Sampah</p>
                <h5 class="fw-bold mt-3 mb-1">BUKTI PENUKARAN REWARD</h5>
                <code class="text-muted small">#{{ str_pad($redemption->redemption_id, 6, '0', STR_PAD_LEFT) }}</code>
            </div>
            @if($redemption->status === 'completed')
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                    <div>
                        <strong>Transaksi Selesai</strong>
                        <div class="small">Reward telah berhasil diproses.</div>
                    </div>
                </div>
            @elseif($redemption->status === 'pending')
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-hourglass-split fs-3"></i>
                    <div>
                        <strong>Menunggu Pengambilan</strong>
                        <div class="small">Warga belum mengambil barang ini di bank sampah.</div>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-info-circle-fill fs-3"></i>
                    <div>
                        <strong>Status: {{ ucfirst($redemption->status) }}</strong>
                    </div>
                </div>
            @endif
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Penerima</h6>
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-bold">{{ $redemption->user->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $redemption->user->email ?? '-' }}</div>
                        @if($redemption->user->phone ?? null)
                            <div class="small text-muted">
                                <i class="bi bi-telephone"></i> {{ $redemption->user->phone }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-2">Waktu Transaksi</h6>
                    <div class="border rounded p-3 bg-light">
                        <div class="small">
                            <strong>Ditukar:</strong>
                            {{ $redemption->redeemed_at?->format('d M Y, H:i') ?? '-' }}
                        </div>
                        @if($redemption->processed_at)
                            <div class="small">
                                <strong>Diproses:</strong>
                                {{ $redemption->processed_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                        @if($redemption->picked_up_at)
                            <div class="small">
                                <strong>Diambil:</strong>
                                {{ $redemption->picked_up_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <h6 class="fw-bold text-muted small text-uppercase mb-2">Detail Reward</h6>
            <table class="table table-bordered mb-4">
                <tr>
                    <td class="bg-light" width="35%">Nama Reward</td>
                    <td>
                        <strong>{{ $redemption->reward->name ?? '-' }}</strong>
                        @if(($redemption->reward->type ?? 'instant') === 'manual')
                            <span class="badge bg-info-subtle text-info border border-info-subtle ms-1">Manual</span>
                        @elseif(($redemption->reward->type ?? 'instant') === 'pickup')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1">Pickup</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">Instant</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="bg-light">Deskripsi</td>
                    <td class="small text-muted">{{ $redemption->reward->description ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Poin Dibutuhkan</td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            ⭐ {{ number_format($redemption->reward->point_required ?? 0) }}
                        </span>
                    </td>
                </tr>
            </table>
            @if($redemption->delivery_note)
                <h6 class="fw-bold text-muted small text-uppercase mb-2">Info Pengiriman</h6>
                <div class="alert alert-success small mb-4">
                    <i class="bi bi-check-circle-fill"></i> {{ $redemption->delivery_note }}
                </div>
            @endif

            @if($redemption->voucher_code)
                <h6 class="fw-bold text-muted small text-uppercase mb-2">Kode Voucher</h6>
                <div class="alert alert-success text-center mb-4">
                    <code class="fs-4 fw-bold text-success">{{ $redemption->voucher_code }}</code>
                    <div class="small text-muted mt-2">Tukarkan kode ini di kasir / aplikasi terkait.</div>
                </div>
            @endif

            @if($redemption->pickup_code)
                <h6 class="fw-bold text-muted small text-uppercase mb-2">Kode Pengambilan</h6>
                <div class="alert alert-primary text-center mb-4">
                    <code class="fs-3 fw-bold text-primary">{{ $redemption->pickup_code }}</code>
                    @if($redemption->pickup_expires_at)
                        <div class="small text-danger mt-2">
                            <i class="bi bi-clock"></i>
                            Berlaku s.d. <strong>{{ \Carbon\Carbon::parse($redemption->pickup_expires_at)->format('d M Y') }}</strong>
                        </div>
                    @endif
                    @if($redemption->pickup_date)
                        <div class="small text-muted mt-1">
                            <i class="bi bi-calendar"></i>
                            Rencana ambil: <strong>{{ \Carbon\Carbon::parse($redemption->pickup_date)->format('d M Y') }}</strong>
                        </div>
                    @endif
                </div>
            @endif
            <div class="border-top pt-3 mt-4 text-center small text-muted">
                <p class="mb-1">Terima kasih telah menggunakan WasteLyn!</p>
                <p class="mb-0">
                    <em>Bukti ini dicetak otomatis pada {{ now()->format('d M Y H:i') }}</em>
                </p>
            </div>

        </div>
    </div>

    <style>
        @media print {
            .no-print, nav, aside, .sidebar, header, footer {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
@endsection