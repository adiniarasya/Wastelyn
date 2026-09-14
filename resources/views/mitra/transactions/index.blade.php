@extends('layouts.mitra')

@section('title', 'Riwayat Setoran')

@section('content')
    <h3 class="mb-4"><i class="bi bi-clock-history"></i> Riwayat Setoran</h3>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama warga...">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                @foreach (['pending','completed','failed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-success w-100">Filter</button>
        </div>
    </form>

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
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d M Y') }}</td>
                                <td>{{ $trx->user->name ?? '-' }}</td>
                                <td>{{ $trx->description ?? '-' }}</td>
                                <td>+{{ $trx->points }}</td>
                                <td><span class="badge bg-secondary text-capitalize">{{ $trx->status ?? 'completed' }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.transactions.show', $trx->transaction_id) }}" class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $transactions->links() }}</div>
        </div>
    </div>
@endsection
