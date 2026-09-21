@extends('template.layout')

@section('title', 'Detail Transaksi')

@section('content')
    <a href="{{ route('mitra.transactions.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Setoran
    </a>

    <div class="card card-stat" style="max-width: 600px;">
        <div class="card-header bg-white fw-semibold">Detail Transaksi</div>
        <div class="card-body">
            <table class="table table-borderless mb-4">
                <tr>
                    <td class="text-muted" width="150">Warga</td>
                    <td>: {{ $transaction->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal</td>
                    <td>: {{ $transaction->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tipe</td>
                    <td>: <span class="text-capitalize">{{ $transaction->type }}</span></td>
                </tr>
                <tr>
                    <td class="text-muted">Poin</td>
                    <td>: +{{ $transaction->points }}</td>
                </tr>
                @if(isset($transaction->xp_earned) && $transaction->xp_earned > 0)
                    <tr>
                        <td class="text-muted">XP</td>
                        <td>: <span class="badge bg-primary">+{{ $transaction->xp_earned }} XP</span></td>
                    </tr>
                @endif
                <tr>
                    <td class="text-muted">Deskripsi</td>
                    <td>: {{ $transaction->description ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>: <span class="badge bg-success text-capitalize">Selesai</span></td>
                </tr>
                @if ($transaction->pickupRequest)
                    <tr>
                        <td class="text-muted">Setoran Terkait</td>
                        <td>
                            : 
                            {{ $transaction->pickupRequest->wasteCategory->name ?? $transaction->pickupRequest->jenis_sampah ?? '-' }}
                            ({{ $transaction->pickupRequest->berat_aktual ?? 0 }} kg)
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Harga per Kg</td>
                        <td>
                            : Rp {{ number_format($transaction->pickupRequest->price_per_kg ?? 0, 0, ',', '.') }}
                            @if($transaction->pickupRequest->price_per_kg == $transaction->pickupRequest->wasteCategory->price_per_kg)
                                <span class="badge bg-secondary">Default Admin</span>
                            @else
                                <span class="badge bg-success">Harga Mitra</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Harga</td>
                        <td>
                            : <strong class="text-success">
                                Rp {{ number_format($transaction->pickupRequest->total_harga ?? 0, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                @endif
            </table>

            <a href="{{ route('mitra.transactions.index') }}" class="btn btn-secondary w-100">
                Kembali
            </a>
        </div>
    </div>
@endsection