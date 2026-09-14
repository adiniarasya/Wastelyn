@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <a href="{{ route('mitra.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Kelola Setoran
    </a>

    <div class="row g-4">
        {{-- KOLOM KIRI: INFO & AKSI --}}
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
                                @php
                                    $badge = match($pickupRequest->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'accepted' => 'bg-info text-dark',
                                        'scheduled' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }} text-capitalize">{{ $pickupRequest->status }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Aksi: Ambil / Jadwalkan / Tolak --}}
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

        {{-- KOLOM KANAN: ITEM & VERIFIKASI --}}
        <div class="col-md-6">
            {{-- Daftar item yang diajukan warga --}}
            <div class="card card-stat mb-4">
                <div class="card-header bg-white fw-semibold">
                    Item yang Diajukan Warga
                    <span class="badge bg-secondary">{{ $pickupRequest->items->count() ?? 0 }}</span>
                </div>
                <div class="card-body">
                    @forelse ($pickupRequest->items as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $item->category->name ?? 'Sampah' }}</strong>
                                <br>
                                <small class="text-muted">
                                    Estimasi: {{ $item->weight ?? 0 }} kg
                                    @if($item->category->price_per_kg)
                                        · Rp {{ number_format($item->category->price_per_kg, 0, ',', '.') }}/kg
                                    @endif
                                </small>
                            </div>
                            <span class="badge bg-light text-dark">#{{ $item->pickup_item_id }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Tidak ada item.</p>
                    @endforelse
                </div>
            </div>

            {{-- Hasil Verifikasi (jika sudah completed) --}}
            @if ($pickupRequest->status === 'completed')
                <div class="card card-stat border-success">
                    <div class="card-header bg-success text-white fw-semibold">Hasil Verifikasi</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="150">Berat Aktual</td>
                                <td>: {{ $pickupRequest->berat_aktual ?? 0 }} kg</td>
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
                                <td class="text-muted">Diverifikasi</td>
                                <td>: {{ optional($pickupRequest->verified_at)->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @elseif ($pickupRequest->mitra_id === auth()->id())
                {{-- FORM VERIFIKASI: timbang per item --}}
                <div class="card card-stat">
                    <div class="card-header bg-white fw-semibold">Verifikasi & Timbang Setoran</div>
                    <div class="card-body">
                        <form action="{{ route('mitra.transactions.store') }}" method="POST" id="verifyForm">
                            @csrf
                            <input type="hidden" name="pickup_request_id" value="{{ $pickupRequest->pickup_request_id }}">

                            @foreach ($pickupRequest->items as $index => $item)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>{{ $item->category->name ?? 'Sampah' }}</strong>
                                        <span class="text-muted small">Estimasi: {{ $item->weight }} kg</span>
                                    </div>
                                    <input type="hidden" name="items[{{ $index }}][pickup_item_id]" value="{{ $item->pickup_item_id }}">
                                    
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small">Berat Aktual (kg)</label>
                                            <input type="number" step="0.01" min="0.01"
                                                   name="items[{{ $index }}][weight]"
                                                   class="form-control form-control-sm item-weight"
                                                   data-price="{{ $item->category->price_per_kg ?? 0 }}"
                                                   value="{{ $item->weight }}"
                                                   required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Harga per Kg (Rp)</label>
                                            <input type="number" step="1" min="0"
                                                   name="items[{{ $index }}][price_per_kg]"
                                                   class="form-control form-control-sm item-price"
                                                   value="{{ $item->category->price_per_kg ?? 0 }}"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total Estimasi</label>
                                <input type="text" id="totalPreview" class="form-control" value="Rp 0" disabled>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Selesaikan & Catat Transaksi
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const totalPreview = document.getElementById('totalPreview');

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.item-weight').forEach((weightInput, i) => {
            const priceInput = document.querySelectorAll('.item-price')[i];
            const w = parseFloat(weightInput.value) || 0;
            const p = parseFloat(priceInput.value) || 0;
            total += w * p;
        });
        if (totalPreview) {
            totalPreview.value = 'Rp ' + total.toLocaleString('id-ID');
        }
    }

    document.querySelectorAll('.item-weight, .item-price').forEach(el => {
        el.addEventListener('input', updateTotal);
    });

    updateTotal();
</script>
@endpush