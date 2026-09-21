@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h3 class="mb-4">
        <i class="bi bi-file-earmark-text"></i>
        Detail Setoran #{{ $pickupRequest->pickup_request_id }}
    </h3>

    <div class="row g-4">
        {{-- Info --}}
        <div class="col-md-6">
            <div class="card card-stat">
                <div class="card-header bg-white fw-semibold">Informasi Setoran</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="140">Bank Sampah</td>
                            <td>: {{ $pickupRequest->wasteBank->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode</td>
                            <td>: <span class="text-capitalize">{{ $pickupRequest->pickup_method ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kategori</td>
                            <td>: {{ $pickupRequest->wasteCategory->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estimasi Berat</td>
                            <td>: {{ $pickupRequest->weight_kg ?? $pickupRequest->estimasi_berat ?? 0 }} kg</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jadwal</td>
                            <td>: {{ optional($pickupRequest->pickup_date)->format('d M Y') }} {{ $pickupRequest->pickup_time }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>: {{ $pickupRequest->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catatan</td>
                            <td>: {{ $pickupRequest->notes ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>:
                                <span class="badge {{ $pickupRequest->status_badge }} text-capitalize">
                                    {{ $pickupRequest->status_label }}
                                </span>
                            </td>
                        </tr>
                        @if($pickupRequest->mitra)
                            <tr>
                                <td class="text-muted">Mitra</td>
                                <td>: {{ $pickupRequest->mitra->name ?? '-' }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- TOMBOL AKSI WARGA (Drop Off) --}}
            @if ($pickupRequest->isDropOff() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_ACCEPTED)
                <div class="card card-stat mt-4 border-primary">
                    <div class="card-body text-center">
                        <p class="mb-3">
                            <i class="bi bi-geo-alt-fill text-primary fs-3"></i>
                            <br>
                            Sudah sampai di bank sampah?
                        </p>
                        <form action="{{ route('user.pickup-requests.arrive', $pickupRequest->pickup_request_id) }}"
                              method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> Saya Sudah Sampai
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if ($pickupRequest->isDropOff() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_WAITING_VERIFICATION)
                <div class="alert alert-info mt-4">
                    <i class="bi bi-hourglass-split"></i>
                    Terima kasih! Mitra akan segera memverifikasi setoranmu.
                </div>
            @endif

            @if ($pickupRequest->isPickup() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_IN_PROGRESS)
                <div class="alert alert-primary mt-4">
                    <i class="bi bi-truck"></i>
                    Kurir sedang menuju lokasimu. Mohon standby ya!
                </div>
            @endif
        </div>

        {{-- Hasil Verifikasi --}}
        <div class="col-md-6">
            @if ($pickupRequest->status === \App\Models\PickupRequest::STATUS_COMPLETED)
                <div class="card card-stat border-success">
                    <div class="card-header bg-success text-white fw-semibold">Hasil Verifikasi</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="140">Berat Aktual</td>
                                <td>: {{ $pickupRequest->berat_aktual ?? 0 }} kg</td>
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
                                <td>: {{ optional($pickupRequest->verified_at)->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <div class="card card-stat">
                    <div class="card-header bg-white fw-semibold">Info</div>
                    <div class="card-body">
                        <p class="text-muted mb-0">
                            Hasil verifikasi akan muncul di sini setelah setoran selesai.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection