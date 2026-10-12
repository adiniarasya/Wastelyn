@extends('template.layout')

@section('title', 'Riwayat Setoran - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Riwayat Setoran</h4>
            <p class="text-muted mb-0 small">Catatan transaksi poin warga</p>
        </div>
        <a href="{{ route('mitra.transactions.export-pdf') }}" class="btn btn-success">
            <i class="bi bi-download me-1"></i> Export PDF
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Warga</th>
                            <th>Deskripsi</th>
                            <th>Poin</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr>
                                <td class="ps-4">
                                    <div class="small">{{ $trx->created_at->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $trx->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $trx->user->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ $trx->description ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-{{ $trx->type === 'earn' ? 'success' : 'danger' }}">
                                        {{ $trx->type === 'earn' ? '+' : '-' }}{{ number_format($trx->points) }}
                                    </span>
                                    <div class="small text-muted">
                                        {{ $trx->type === 'earn' ? 'Dapat Poin' : 'Tukar Poin' }}
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('mitra.transactions.show', $trx->transaction_id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    Belum ada riwayat transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-3 border-top">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection