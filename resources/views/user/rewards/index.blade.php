@extends('template.layout')

@section('title', 'Reward Center')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-gift"></i> Reward Center</h3>
        <div class="text-end">
            <div class="small text-muted">Poin Kamu</div>
            <div class="fs-4 fw-bold text-warning">
                ⭐ {{ number_format($user->points) }}
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Daftar Reward --}}
    <div class="row g-3 mb-4">
        @forelse ($rewards as $reward)
            <div class="col-md-3 col-sm-6">
                <div class="card card-stat h-100">
                    @if($reward->image)
                        <img src="{{ asset('storage/' . $reward->image) }}"
                             class="card-img-top" style="height:150px; object-fit:cover;"
                             alt="{{ $reward->name }}">
                    @endif
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ $reward->name }}</h6>
                        <p class="small text-muted mb-2">{{ Str::limit($reward->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark">
                                ⭐ {{ number_format($reward->point_required) }}
                            </span>
                            <small class="text-muted">Stok: {{ $reward->stock }}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        @if($user->points >= $reward->point_required && $reward->stock > 0)
                            <form action="{{ route('user.rewards.redeem', $reward->reward_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Tukar {{ $reward->point_required }} poin dengan {{ $reward->name }}?');">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="bi bi-gift"></i> Tukar
                                </button>
                            </form>
                        @elseif($reward->stock <= 0)
                            <button class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                        @else
                            <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                Poin Kurang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">Belum ada reward tersedia.</div>
            </div>
        @endforelse
    </div>

    {{-- Riwayat Penukaran --}}
    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-clock-history"></i> Riwayat Penukaran
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Reward</th>
                            <th>Poin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myRedemptions as $r)
                            <tr>
                                <td>{{ $r->redeemed_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $r->reward->name ?? '-' }}</td>
                                <td>-{{ number_format($r->reward->point_required ?? 0) }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada riwayat penukaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection