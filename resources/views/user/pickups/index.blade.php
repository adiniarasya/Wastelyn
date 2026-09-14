@extends('template.layout')

@section('title', 'Riwayat Setoran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Setoran Sampah</h3>
        <a href="{{ route('user.pickup-requests.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajukan Setoran
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card card-stat">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Bank Sampah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Poin</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pickups as $pickup)
                            <tr>
                                <td>{{ $loop->iteration + ($pickups->currentPage() - 1) * $pickups->perPage() }}</td>
                                <td>
                                    {{ optional($pickup->pickup_date)->format('d M Y') }}
                                    <br><small class="text-muted">{{ $pickup->pickup_time }}</small>
                                </td>
                                <td>{{ $pickup->wasteBank->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $pickup->pickup_method ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $badge = match($pickup->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'accepted' => 'bg-info text-dark',
                                            'scheduled' => 'bg-primary',
                                            'completed' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }} text-capitalize">{{ $pickup->status }}</span>
                                </td>
                                <td>
                                    @if($pickup->status === 'completed')
                                        <span class="text-success fw-semibold">+{{ $pickup->points_earned ?? 0 }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('user.pickup-requests.show', $pickup->pickup_request_id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                    @if($pickup->status === 'pending')
                                        <form action="{{ route('user.pickup-requests.destroy', $pickup->pickup_request_id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin batalkan pengajuan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada riwayat setoran.
                                    <a href="{{ route('user.pickup-requests.create') }}">Ajukan sekarang</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $pickups->links() }}</div>
        </div>
    </div>
@endsection