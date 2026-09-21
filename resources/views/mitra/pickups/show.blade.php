@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <a href="{{ route('mitra.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Kelola Setoran
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: INFO --}}
        <div class="col-md-6">
            <div class="card card-stat">
                <div class="card-header bg-white fw-semibold">Informasi Permintaan</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="150">Nama Warga</td>
                            <td>: {{ $pickupRequest->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>: {{ $pickupRequest->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode</td>
                            <td>: <span class="text-capitalize">{{ $pickupRequest->pickup_method ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Bank Sampah</td>
                            <td>: {{ $pickupRequest->wasteBank->name ?? '-' }}</td>
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
                        @if($pickupRequest->courier_name)
                            <tr>
                                <td class="text-muted">Kurir</td>
                                <td>: {{ $pickupRequest->courier_name }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- AKSI DINAMIS --}}
            <div class="card card-stat mt-4">
                <div class="card-header bg-white fw-semibold">Aksi</div>
                <div class="card-body">

                    @if (is_null($pickupRequest->mitra_id))
                        <form action="{{ route('mitra.pickup-requests.take', $pickupRequest->pickup_request_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Ambil Permintaan Ini</button>
                        </form>

                    @elseif ($pickupRequest->mitra_id === auth()->id())

                        @if ($pickupRequest->isPickup() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_ACCEPTED)
                            <form action="{{ route('mitra.pickup-requests.start', $pickupRequest->pickup_request_id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Kurir (Opsional)</label>
                                    <input type="text" name="courier_name" class="form-control" placeholder="Misal: Budi">
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="pickup_date" class="form-control"
                                               value="{{ optional($pickupRequest->pickup_date)->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">Jam</label>
                                        <input type="time" name="pickup_time" class="form-control"
                                               value="{{ $pickupRequest->pickup_time }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Mulai Penjemputan</button>
                            </form>

                        @elseif ($pickupRequest->isPickup() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_IN_PROGRESS)
                            <form action="{{ route('mitra.pickup-requests.receive', $pickupRequest->pickup_request_id) }}" method="POST">
                                @csrf
                                <p class="text-muted">Kurir sedang menuju lokasi. Klik tombol di bawah jika sampah sudah diterima.</p>
                                <button type="submit" class="btn btn-primary w-100">Sampah Diterima</button>
                            </form>

                        @elseif ($pickupRequest->isDropOff() && $pickupRequest->status === \App\Models\PickupRequest::STATUS_ACCEPTED)
                            <div class="alert alert-info mb-0">
                                Menunggu warga tiba di bank sampah. Warga akan konfirmasi lewat aplikasi.
                            </div>

                        @elseif ($pickupRequest->status === \App\Models\PickupRequest::STATUS_WAITING_VERIFICATION)
                            <form action="{{ route('mitra.pickup-requests.verify', $pickupRequest->pickup_request_id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Berat Aktual (kg)</label>
                                    <input type="number" step="0.01" min="0.1" name="berat_aktual"
                                           class="form-control"
                                           value="{{ $pickupRequest->weight_kg ?? $pickupRequest->estimasi_berat }}"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        Harga per Kg (Rp)
                                        @php
                                            $hargaEfektif = $pickupRequest->harga_efektif;
                                        @endphp
                                        @if($hargaEfektif['source'] === 'mitra')
                                            <span class="badge bg-success">Harga Kamu</span>
                                        @else
                                            <span class="badge bg-secondary">Harga Default Admin</span>
                                        @endif
                                    </label>
                                    <input type="number" step="1" min="0" name="price_per_kg"
                                           class="form-control"
                                           value="{{ $hargaEfektif['price_per_kg'] }}">
                                    <small class="text-muted">
                                        Atur harga khusus di menu "Kelola Harga Sampah".
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Verifikasi & Selesaikan
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Tolak --}}
                        @if (in_array($pickupRequest->status, [
                            \App\Models\PickupRequest::STATUS_ACCEPTED,
                        ]))
                            <hr>
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

        {{-- KOLOM KANAN: HASIL VERIFIKASI --}}
        <div class="col-md-6">
            @if ($pickupRequest->status === \App\Models\PickupRequest::STATUS_COMPLETED)
                <div class="card card-stat border-success">
                    <div class="card-header bg-success text-white fw-semibold">Hasil Verifikasi</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="150">Berat Aktual</td>
                                <td>: {{ $pickupRequest->berat_aktual ?? 0 }} kg</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga per Kg</td>
                                <td>: Rp {{ number_format($pickupRequest->price_per_kg ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Harga</td>
                                <td>: <strong class="text-success">Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">XP</td>
                                <td>: <span class="badge bg-primary">+{{ $pickupRequest->xp_earned ?? 0 }} XP</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Poin</td>
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
                            Hasil verifikasi akan muncul di sini setelah setoran diselesaikan.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection