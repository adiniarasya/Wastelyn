@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="fw-bold">Detail Setoran</h3>
                <p class="text-subtitle text-muted mb-0">
                    Setoran #{{ $pickupRequest->pickup_request_id }}
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.pickup-requests.index') }}">Riwayat Setoran</a>
                        </li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">

        {{-- ALERT --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- STATUS BANNER --}}
        @php
        $statusConfig = [
        'pending' => ['bg-warning', 'hourglass-split', 'Menunggu Konfirmasi'],
        'accepted' => ['bg-info', 'check2-circle', 'Diterima Mitra'],
        'scheduled' => ['bg-primary', 'calendar-check', 'Dijadwalkan'],
        'in_progress' => ['bg-primary', 'truck', 'Sedang Dijemput'],
        'waiting_verification' => ['bg-secondary', 'hourglass', 'Menunggu Verifikasi'],
        'completed' => ['bg-success', 'check-circle-fill', 'Selesai'],
        'rejected' => ['bg-danger', 'x-circle-fill', 'Ditolak'],
        'cancelled' => ['bg-dark', 'slash-circle', 'Dibatalkan'],
        ];
        $sc = $statusConfig[$pickupRequest->status] ?? ['bg-secondary', 'circle', $pickupRequest->status];
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="{{ $sc[0] }} text-white rounded-3 p-3 d-flex align-items-center justify-content-center"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-{{ $sc[1] }} fs-3"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Status Setoran</small>
                    <h5 class="mb-0">{{ $sc[2] }}</h5>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- INFORMASI SETORAN --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-info-circle text-success me-1"></i> Informasi Setoran
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="140">Bank Sampah</td>
                                <td>: <strong>{{ $pickupRequest->bank?->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Metode</td>
                                <td>:
                                    @if($pickupRequest->pickup_method === 'pickup')
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-truck"></i> Dijemput
                                    </span>
                                    @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-box-arrow-in-down"></i> Antar Sendiri
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kategori</td>
                                <td>: {{ $pickupRequest->wasteCategory->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Estimasi Berat</td>
                                <td>: {{ $pickupRequest->estimasi_berat ?? $pickupRequest->weight_kg ?? '-' }} kg</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jadwal</td>
                                <td>:
                                    {{ $pickupRequest->pickup_date ? \Carbon\Carbon::parse($pickupRequest->pickup_date)->format('d M Y') : '-' }}
                                    {{ $pickupRequest->pickup_time }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat</td>
                                <td>: {{ $pickupRequest->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Catatan</td>
                                <td>: {{ $pickupRequest->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- HASIL VERIFIKASI --}}
            <div class="col-md-6">
                @if ($pickupRequest->status === 'completed')
                <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-4">
                    <div class="card-header bg-white border-0 pt-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-patch-check-fill text-success me-1"></i> Hasil Verifikasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="140">Berat Aktual</td>
                                <td>: <strong>{{ $pickupRequest->berat_aktual ?? 0 }} kg</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Harga</td>
                                <td>:
                                    <strong class="text-success">
                                        Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">XP Didapat</td>
                                <td>: <span class="badge bg-primary">+{{ $pickupRequest->xp_earned ?? 0 }} XP</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Poin Didapat</td>
                                <td>: <span class="badge bg-warning text-dark">+{{ $pickupRequest->points_earned ?? 0 }} Poin</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">CO₂ Saved</td>
                                <td>: {{ $pickupRequest->co2_saved ?? 0 }} kg</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Diverifikasi</td>
                                <td>:
                                    {{ $pickupRequest->verified_at ? \Carbon\Carbon::parse($pickupRequest->verified_at)->format('d M Y H:i') : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @else
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-hourglass text-muted me-1"></i> Hasil Verifikasi
                        </h6>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center text-center">
                        <div>
                            <i class="bi bi-hourglass-split fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">
                                Hasil verifikasi akan muncul di sini setelah setoran selesai.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- TOMBOL AKSI (DROP OFF) --}}
            @if ($pickupRequest->pickup_method === 'dropoff' && $pickupRequest->status === 'accepted')
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 border-start border-primary border-4">
                    <div class="card-body text-center">
                        <i class="bi bi-geo-alt-fill text-primary fs-1"></i>
                        <h5 class="mt-2">Sudah sampai di bank sampah?</h5>
                        <p class="text-muted">Klik tombol di bawah biar mitra tau kamu udah sampai.</p>
                        <form action="{{ route('user.pickup-requests.arrive', $pickupRequest->pickup_request_id) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Saya Sudah Sampai
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- INFO STATUS --}}
            @if ($pickupRequest->pickup_method === 'dropoff' && $pickupRequest->status === 'waiting_verification')
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Terima kasih! Mitra akan segera memverifikasi setoranmu.
                </div>
            </div>
            @endif

            @if ($pickupRequest->pickup_method === 'pickup' && $pickupRequest->status === 'in_progress')
            <div class="col-12">
                <div class="alert alert-primary">
                    <i class="bi bi-truck me-2"></i>
                    Kurir sedang menuju lokasimu. Mohon standby ya!
                </div>
            </div>
            @endif

        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="d-flex justify-content-start mt-4">
            <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

    </section>
</div>
@endsection