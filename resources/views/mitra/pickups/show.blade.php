@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <a href="{{ route('mitra.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Kelola Setoran
    </a>

    <div class="row g-4">
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
                            <td>: {{ $pickupRequest->address ?? $pickupRequest->alamat ?? '-' }}</td>
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
                            <td class="text-muted">Jadwal</td>
                            <td>: {{ optional($pickupRequest->pickup_date)->format('d M Y') }} {{ $pickupRequest->pickup_time }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catatan</td>
                            <td>: {{ $pickupRequest->notes ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>: <span class="badge bg-info text-dark text-capitalize">{{ $pickupRequest->status }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if (is_null($pickupRequest->mitra_id))
                <div class="card card-stat mt-4">
                    <div class="card-body text-center py-4">
                        <p class="text-muted mb-3">Permintaan ini belum diambil siapapun.</p>
                        <form action="{{ route('mitra.pickup-requests.status', $pickupRequest->pickup_request_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-success">Ambil Permintaan Ini</button>
                        </form>
                    </div>
                </div>
            @elseif ($pickupRequest->mitra_id === auth()->id() && $pickupRequest->status !== 'completed')
                <div class="card card-stat mt-4">
                    <div class="card-header bg-white fw-semibold">Jadwalkan Ulang Penjemputan</div>
                    <div class="card-body">
                        <form action="{{ route('mitra.pickup-requests.status', $pickupRequest->pickup_request_id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="scheduled">
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="pickup_date" class="form-control" value="{{ optional($pickupRequest->pickup_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jam</label>
                                <input type="time" name="pickup_time" class="form-control" value="{{ $pickupRequest->pickup_time }}" required>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Simpan Jadwal</button>
                        </form>

                        <form action="{{ route('mitra.pickup-requests.status', $pickupRequest->pickup_request_id) }}" method="POST"
                              onsubmit="return confirm('Yakin tolak/lepas permintaan ini?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Tolak Permintaan</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            @if ($pickupRequest->status === 'completed')
                <div class="card card-stat">
                    <div class="card-header bg-white fw-semibold">Hasil Verifikasi</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="150">Jenis Sampah</td>
                                <td>: {{ $pickupRequest->jenis_sampah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Berat Aktual</td>
                                <td>: {{ $pickupRequest->berat_aktual ?? 0 }} kg</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Harga</td>
                                <td>: Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @elseif ($pickupRequest->mitra_id === auth()->id())
                <div class="card card-stat">
                    <div class="card-header bg-white fw-semibold">Verifikasi & Selesaikan Setoran</div>
                    <div class="card-body">
                        <form action="{{ route('mitra.transactions.store') }}" method="POST" id="verifyForm">
                            @csrf
                            <input type="hidden" name="pickup_request_id" value="{{ $pickupRequest->pickup_request_id }}">

                            <div class="mb-3">
                                <label class="form-label">Jenis Sampah</label>
                                <input type="text" name="jenis_sampah" class="form-control" placeholder="Contoh: Plastik, Kertas, Logam" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Berat Aktual (kg)</label>
                                <input type="number" step="0.01" min="0.01" name="berat_aktual" id="berat_aktual" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga per Kg (Rp)</label>
                                <input type="number" step="1" min="0" name="harga_per_kg" id="harga_per_kg" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estimasi Total</label>
                                <input type="text" id="totalPreview" class="form-control" value="Rp 0" disabled>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Selesaikan & Catat Transaksi</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const beratInput = document.getElementById('berat_aktual');
    const hargaInput = document.getElementById('harga_per_kg');
    const totalPreview = document.getElementById('totalPreview');

    function updateTotal() {
        if (!beratInput || !hargaInput || !totalPreview) return;
        const berat = parseFloat(beratInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        totalPreview.value = 'Rp ' + (berat * harga).toLocaleString('id-ID');
    }

    if (beratInput && hargaInput) {
        beratInput.addEventListener('input', updateTotal);
        hargaInput.addEventListener('input', updateTotal);
    }
</script>
@endpush
