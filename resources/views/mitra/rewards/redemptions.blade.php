@extends('template.layout')

@section('title', 'Penukaran Reward - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-arrow-left-right"></i> Penukaran Reward</h3>
        <a href="{{ route('mitra.rewards.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Kelola Reward
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Pending</div>
                    <div class="fs-3 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Diproses</div>
                    <div class="fs-3 fw-bold text-info">{{ $stats['processed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Selesai</div>
                    <div class="fs-3 fw-bold text-success">{{ $stats['completed'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card card-stat mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" @selected($status === 'all')>Semua</option>
                        <option value="pending" @selected($status === 'pending')>Pending</option>
                        <option value="processed" @selected($status === 'processed')>Diproses</option>
                        <option value="completed" @selected($status === 'completed')>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Penukaran --}}
    <div class="card card-stat">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Warga</th>
                            <th>Reward</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $r)
                            <tr>
                                <td>{{ $r->redeemed_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <strong>{{ $r->user->name ?? '-' }}</strong>
                                    <br><small class="text-muted">{{ $r->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $r->reward->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        ⭐ {{ number_format($r->reward->point_required ?? 0) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $badge = match($r->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'processed' => 'bg-info text-dark',
                                            'completed' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }} text-capitalize">{{ $r->status }}</span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('mitra.rewards.redemptions.update', $r->redemption_id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm d-inline-block"
                                                style="width: auto;" onchange="this.form.submit()">
                                            <option value="pending" @selected($r->status === 'pending')>Pending</option>
                                            <option value="processed" @selected($r->status === 'processed')>Diproses</option>
                                            <option value="completed" @selected($r->status === 'completed')>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada penukaran reward.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $redemptions->links() }}</div>
        </div>
    </div>
@endsection