@extends('layouts.mitra')

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
                <tr>
                    <td class="text-muted">Deskripsi</td>
                    <td>: {{ $transaction->description ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>: <span class="badge bg-secondary text-capitalize">{{ $transaction->status ?? 'completed' }}</span></td>
                </tr>
                @if ($transaction->pickupRequest)
                    <tr>
                        <td class="text-muted">Setoran Terkait</td>
                        <td>
                            : {{ $transaction->pickupRequest->jenis_sampah ?? '-' }}
                            ({{ $transaction->pickupRequest->berat_aktual ?? 0 }} kg)
                        </td>
                    </tr>
                @endif
            </table>

            <form action="{{ route('mitra.transactions.status', $transaction->transaction_id) }}" method="POST" class="d-flex gap-2">
                @csrf
                @method('PUT')
                <select name="status" class="form-select">
                    @foreach (['pending','completed','failed','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($transaction->status === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline-success">Update Status</button>
            </form>
        </div>
    </div>
@endsection
