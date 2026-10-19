@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <style>

        .pickup-detail .stats-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pickup-detail .stats-icon i {
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

        .pickup-detail .stats-icon i::before {
            display: block;
            line-height: 1;
            vertical-align: 0;
        }

        .pickup-detail .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            padding: .75rem 0;
            border-bottom: 1px solid #eef0f2;
        }

        .pickup-detail .detail-item:first-child {
            padding-top: 0;
        }

        .pickup-detail .detail-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .pickup-detail .detail-label {
            color: #6c757d;
            flex-shrink: 0;
        }

        .pickup-detail .detail-value {
            text-align: right;
            word-break: break-word;
        }
    </style>

    @php
        $statusConfig = [
            'pending' => ['bg-warning', 'hourglass-split', 'Menunggu konfirmasi'],
            'accepted' => ['bg-info', 'check2-circle', 'Diterima mitra'],
            'scheduled' => ['bg-primary', 'calendar-check', 'Dijadwalkan'],
            'in_progress' => ['bg-primary', 'truck', 'Sedang dijemput'],
            'waiting_verification' => ['bg-secondary', 'hourglass', 'Menunggu verifikasi'],
            'completed' => ['bg-success', 'check-circle-fill', 'Selesai'],
            'rejected' => ['bg-danger', 'x-circle-fill', 'Ditolak'],
            'cancelled' => ['bg-dark', 'slash-circle', 'Dibatalkan'],
        ];
        $sc = $statusConfig[$pickupRequest->status] ?? ['bg-secondary', 'circle', $pickupRequest->status];
    @endphp

    <div class="page-heading pickup-detail">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Setoran</h3>
                    
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.pickup-requests.index') }}">Riwayat Setoran</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon {{ $sc[0] }} mb-0 flex-shrink-0">
                        <i class="bi bi-{{ $sc[1] }} fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Status setoran</small>
                        <h5 class="mb-0">{{ $sc[2] }}</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <div class="card h-100 mb-0">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle text-success me-2"></i>Informasi setoran
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="detail-list">
                                <div class="detail-item"><span class="detail-label">Bank sampah</span><div class="detail-value"><strong>{{ $pickupRequest->bank?->name ?? '-' }}</strong></div></div>
                                <div class="detail-item"><span class="detail-label">Metode</span><div class="detail-value">@if($pickupRequest->pickup_method === 'pickup')
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-truck me-1"></i>Dijemput
                                            </span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="bi bi-box-arrow-in-down me-1"></i>Antar sendiri
                                            </span>
                                        @endif</div></div>
                                <div class="detail-item"><span class="detail-label">Kategori</span><div class="detail-value">{{ $pickupRequest->wasteCategory->name ?? '-' }}</div></div>
                                <div class="detail-item"><span class="detail-label">Estimasi berat</span><div class="detail-value">{{ $pickupRequest->estimasi_berat ?? $pickupRequest->weight_kg ?? '-' }} kg</div></div>
                                <div class="detail-item"><span class="detail-label">Jadwal</span><div class="detail-value">{{ $pickupRequest->pickup_date ? \Carbon\Carbon::parse($pickupRequest->pickup_date)->format('d M Y') : '-' }}
                                        {{ $pickupRequest->pickup_time }}</div></div>
                                <div class="detail-item"><span class="detail-label">Alamat</span><div class="detail-value">{{ $pickupRequest->address ?? '-' }}</div></div>
                                <div class="detail-item"><span class="detail-label">Catatan</span><div class="detail-value">{{ $pickupRequest->notes ?? '-' }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    @if ($pickupRequest->status === 'completed')
                        <div class="card h-100 mb-0 border-start border-success border-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-patch-check-fill text-success me-2"></i>Hasil verifikasi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="detail-list">
                                    <div class="detail-item"><span class="detail-label">Berat aktual</span><div class="detail-value"><strong>{{ $pickupRequest->berat_aktual ?? 0 }} kg</strong></div></div>
                                    <div class="detail-item"><span class="detail-label">Total harga</span><div class="detail-value"><strong class="text-success">
                                                Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}
                                            </strong></div></div>
                                    <div class="detail-item"><span class="detail-label">XP didapat</span><div class="detail-value"><span class="badge bg-primary">+{{ $pickupRequest->xp_earned ?? 0 }} XP</span></div></div>
                                    <div class="detail-item"><span class="detail-label">Poin didapat</span><div class="detail-value"><span class="badge bg-warning text-dark">+{{ $pickupRequest->points_earned ?? 0 }} Poin</span></div></div>
                                    <div class="detail-item"><span class="detail-label">CO₂ dihemat</span><div class="detail-value">{{ $pickupRequest->co2_saved ?? 0 }} kg</div></div>
                                    <div class="detail-item"><span class="detail-label">Diverifikasi</span><div class="detail-value">{{ $pickupRequest->verified_at ? \Carbon\Carbon::parse($pickupRequest->verified_at)->format('d M Y H:i') : '-' }}</div></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card h-100 mb-0">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    Hasil verifikasi
                                </h5>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center text-center">
                                <div>
                                   
                                    <p class="text-muted mb-0">
                                        Hasil verifikasi akan muncul di sini setelah setoran selesai.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($pickupRequest->pickup_method === 'dropoff' && $pickupRequest->status === 'accepted')
                    <div class="col-12 mb-4">
                        <div class="card mb-0 border-start border-primary border-4">
                            <div class="card-body text-center">
                                <i class="bi bi-geo-alt-fill text-primary fs-1"></i>
                                <h5 class="mt-2">Sudah sampai di bank sampah?</h5>
                                <p class="text-muted">
                                    Klik tombol di bawah agar mitra tahu kamu sudah sampai.
                                </p>
                                <form action="{{ route('user.pickup-requests.arrive', $pickupRequest->pickup_request_id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Saya sudah sampai
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($pickupRequest->pickup_method === 'dropoff' && $pickupRequest->status === 'waiting_verification')
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center gap-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                            <div>Terima kasih! Mitra akan segera memverifikasi setoranmu.</div>
                        </div>
                    </div>
                @endif

                @if ($pickupRequest->pickup_method === 'pickup' && $pickupRequest->status === 'in_progress')
                    <div class="col-12">
                        <div class="alert alert-primary d-flex align-items-center gap-3">
                            <i class="bi bi-truck fs-4"></i>
                            <div>Kurir sedang menuju lokasimu. Mohon bersiap di lokasi.</div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="mt-2">
                <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke riwayat
                </a>
            </div>

        </section>
    </div>
@endsection