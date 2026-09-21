@extends('template.layout')

@section('title', 'Riwayat Setoran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Setoran</h3>
        <a href="{{ route('mitra.transactions.export-pdf') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>

    <div class="card card-stat">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Warga</th>
                            <th>Deskripsi</th>
                            <th>Poin</th>
                            <th>Tipe</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d M Y') }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->description ?? '-' }}</td>
                                <td>
                                    <span class="fw-semibold text-{{ $trx->type === 'earn' ? 'success' : 'danger' }}">
                                        {{ $trx->type === 'earn' ? '+' : '-' }}{{ $trx->points }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $trx->type === 'earn' ? 'success' : 'warning text-dark' }}">
                                        {{ $trx->type === 'earn' ? 'Dapat Poin' : 'Tukar Poin' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.transactions.show', $trx->transaction_id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada riwayat transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $transactions->links() }}</div>
        </div>
    </div>
@endsection