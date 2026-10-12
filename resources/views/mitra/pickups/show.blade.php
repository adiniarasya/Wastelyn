@extends('template.layout')

@section('title', 'Detail Setoran - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Detail Setoran</h4>
            <span class="badge {{ $pickupRequest->status_badge }} fw-normal mt-1">
                {{ $pickupRequest->status_label }}
            </span>
        </div>
        <a href="{{ route('mitra.pickup-requests.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Informasi Permintaan</h6>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Nama Warga</span>
                        <span class="small fw-semibold">{{ $pickupRequest->user->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Alamat</span>
                        <span class="small text-end" style="max-width: 60%;">{{ $pickupRequest->address ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Metode</span>
                        <span class="small text-capitalize">{{ $pickupRequest->pickup_method ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Bank Sampah</span>
                        <span class="small">{{ $pickupRequest->wasteBank->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Kategori</span>
                        <span class="small">{{ $pickupRequest->wasteCategory->name ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Estimasi Berat</span>
                        <span class="small">{{ $pickupRequest->weight_kg ?? $pickupRequest->estimasi_berat ?? 0 }} kg</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Jadwal</span>
                        <span class="small">
                            {{ optional($pickupRequest->pickup_date)->format('d M Y') }} {{ $pickupRequest->pickup_time }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Catatan</span>
                        <span class="small text-end" style="max-width: 60%;">{{ $pickupRequest->notes ?? '-' }}</span>
                    </div>
                    @if($pickupRequest->courier_name)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Kurir</span>
                            <span class="small">{{ $pickupRequest->courier_name }}</span>
                        </div>
                    @endif

                    {{-- AKSI --}}
                    <div class="mt-4 pt-3 border-top">

                        @if (is_null($pickupRequest->mitra_id))
                            <form action="{{ route('mitra.pickup-requests.take', $pickupRequest->pickup_request_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">Ambil Permintaan</button>
                            </form>

                        @elseif ($pickupRequest->mitra_id === auth()->id())

                            @if ($pickupRequest->status === \App\Models\PickupRequest::STATUS_PENDING)
                                <form action="{{ route('mitra.pickup-requests.accept', $pickupRequest->pickup_request_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">Terima Setoran</button>
                                </form>

                            @elseif ($pickupRequest->isPickup() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_ACCEPTED)
                                <form action="{{ route('mitra.pickup-requests.start', $pickupRequest->pickup_request_id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Nama Kurir (Opsional)</label>
                                        <input type="text" name="courier_name" class="form-control form-control-sm" placeholder="Misal: Budi">
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Tanggal</label>
                                            <input type="date" name="pickup_date" class="form-control form-control-sm"
                                                   value="{{ optional($pickupRequest->pickup_date)->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Jam</label>
                                            <input type="time" name="pickup_time" class="form-control form-control-sm"
                                                   value="{{ $pickupRequest->pickup_time }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Mulai Penjemputan</button>
                                </form>

                            @elseif ($pickupRequest->isPickup() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_IN_PROGRESS)
                                <form action="{{ route('mitra.pickup-requests.receive', $pickupRequest->pickup_request_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">Sampah Diterima</button>
                                </form>

                            @elseif ($pickupRequest->isDropOff() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_ACCEPTED)
                                <p class="small text-muted mb-0 text-center">
                                    Menunggu warga tiba di bank sampah.
                                </p>

                            @elseif ($pickupRequest->status === \App\Models\PickupRequest::STATUS_WAITING_VERIFICATION)
                                <form action="{{ route('mitra.pickup-requests.verify', $pickupRequest->pickup_request_id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Berat Aktual (kg)</label>
                                        <input type="number" step="0.01" min="0.1" name="berat_aktual"
                                               class="form-control form-control-sm"
                                               value="{{ $pickupRequest->weight_kg ?? $pickupRequest->estimasi_berat }}"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted d-flex justify-content-between">
                                            <span>Harga per Kg (Rp)</span>
                                            @php $hargaEfektif = $pickupRequest->harga_efektif; @endphp
                                            <span class="{{ $hargaEfektif['source'] === 'mitra' ? 'text-success' : 'text-muted' }}">
                                                {{ $hargaEfektif['source'] === 'mitra' ? 'Harga Kamu' : 'Harga Default' }}
                                            </span>
                                        </label>
                                        <input type="number" step="1" min="0" name="price_per_kg"
                                               class="form-control form-control-sm"
                                               value="{{ $hargaEfektif['price_per_kg'] }}">
                                        <small class="text-muted">Atur harga khusus di menu "Kelola Harga Sampah".</small>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Verifikasi & Selesaikan</button>
                                </form>
                            @endif

                            @if (in_array($pickupRequest->status, [\App\Models\PickupRequest::STATUS_ACCEPTED]))
                                <hr class="my-3">
                                <form action="{{ route('mitra.pickup-requests.reject', $pickupRequest->pickup_request_id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin tolak permintaan ini?');">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="text" name="rejection_reason"
                                               class="form-control form-control-sm"
                                               placeholder="Alasan penolakan (opsional)">
                                    </div>
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        Tolak Permintaan
                                    </button>
                                </form>
                            @endif

                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-md-6">
            @if ($pickupRequest->status === \App\Models\PickupRequest::STATUS_COMPLETED)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-1 text-success">Hasil Verifikasi</h6>
                        <p class="text-muted small mb-3">Setoran ini sudah selesai diproses.</p>

                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Berat Aktual</span>
                            <span class="small fw-semibold">{{ $pickupRequest->berat_aktual ?? 0 }} kg</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Harga per Kg</span>
                            <span class="small">Rp {{ number_format($pickupRequest->price_per_kg ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Total Harga</span>
                            <span class="small fw-bold text-success">
                                Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">XP</span>
                            <span class="small">+{{ $pickupRequest->xp_earned ?? 0 }} XP</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Poin</span>
                            <span class="small">+{{ $pickupRequest->points_earned ?? 0 }} Poin</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">CO₂ Saved</span>
                            <span class="small">{{ $pickupRequest->co2_saved ?? 0 }} kg</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted small">Diverifikasi</span>
                            <span class="small">{{ optional($pickupRequest->verified_at)->format('d M Y H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <p class="text-muted small mb-0">
                            Hasil verifikasi akan muncul di sini setelah setoran diselesaikan.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection