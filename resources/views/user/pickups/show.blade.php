@extends('template.layout')

@section('title', 'Detail Setoran')

@section('content')
    <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <h3 class="mb-4"><i class="bi bi-file-earmark-text"></i> Detail Setoran #{{ $pickupRequest->pickup_request_id }}</h3>

    <div class="row g-4">
        {{-- Info Pickup --}}
        <div class="col-md-6">
            <div class="card card-stat">
                <div class="card-header bg-white fw-semibold">Informasi Penjemputan</div>
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
                        @if($pickupRequest->mitra)
                            <tr>
                                <td class="text-muted">Mitra</td>
                                <td>: {{ $pickupRequest->mitra->name ?? '-' }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Item & Hasil Verifikasi --}}
        <div class="col-md-6">
            <div class="card card-stat mb-4">
                <div class="card-header bg-white fw-semibold">Item Sampah</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($pickupRequest->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ $item->category->name ?? 'Sampah' }}</span>
                                <span class="text-muted">
                                    {{ $item->weight ?? 0 }} kg
                                    @if($item->category->price_per_kg)
                                        · Rp {{ number_format($item->category->price_per_kg, 0, ',', '.') }}/kg
                                    @endif
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Tidak ada item.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if($pickupRequest->status === 'completed')
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
                                <td>: <strong class="text-success">Rp {{ number_format($pickupRequest->total_harga ?? 0, 0, ',', '.') }}</strong></td>
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
                                <td class="text-muted">Diverifikasi</td>
                                <td>: {{ optional($pickupRequest->verified_at)->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection