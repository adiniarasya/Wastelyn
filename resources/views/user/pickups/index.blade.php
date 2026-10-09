@extends('template.layout')

@section('title', 'Riwayat Setoran')

@section('content')
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">
                <i class="bi bi-clock-history text-success"></i> Riwayat Setoran Sampah
            </h3>
            <p class="text-muted mb-0 small">Pantau semua pengajuan setoran sampahmu</p>
        </div>
        <a href="{{ route('user.pickup-requests.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajukan Setoran
        </a>
    </div>

    {{-- INFO BANK LANGGANAN --}}
    @if(auth()->user()->wasteBank)
        <div class="alert alert-info d-flex align-items-center">
            <i class="bi bi-shop fs-4 me-3"></i>
            <div>
                <strong>Bank Sampah Langganan:</strong> {{ auth()->user()->wasteBank->name }}
                <br>
                <small class="text-muted">Semua setoran otomatis dikirim ke bank sampah ini.</small>
            </div>
        </div>
    @endif

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- STATISTIK CEPAT --}}
    @php
        $total = $pickups->total();
        $pending = $pickups->where('status', 'pending')->count();
        $completed = $pickups->where('status', 'completed')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-inbox fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold">{{ $total }}</div>
                        <div class="text-muted small">Total Setoran</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold">{{ $pending }}</div>
                        <div class="text-muted small">Menunggu</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold">{{ $completed }}</div>
                        <div class="text-muted small">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Berat</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Poin</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pickups as $pickup)
                            <tr>
                                <td class="ps-3">
                                    {{ $loop->iteration + ($pickups->currentPage() - 1) * $pickups->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $pickup->pickup_date ? \Carbon\Carbon::parse($pickup->pickup_date)->format('d M Y') : '-' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> {{ $pickup->pickup_time ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    @if($pickup->wasteCategory)
                                        <span class="badge bg-light text-dark border">
                                            {{ $pickup->wasteCategory->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pickup->weight_kg)
                                        <strong>{{ number_format($pickup->weight_kg, 1) }}</strong> kg
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pickup->pickup_method === 'pickup')
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="bi bi-truck"></i> Dijemput
                                        </span>
                                    @elseif($pickup->pickup_method === 'dropoff')
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-box-arrow-in-down"></i> Antar Sendiri
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badge = match ($pickup->status) {
                                            'pending' => 'bg-warning text-dark',
                                            'accepted' => 'bg-info text-dark',
                                            'scheduled' => 'bg-primary',
                                            'in_progress' => 'bg-info',
                                            'waiting_verification' => 'bg-secondary',
                                            'completed' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'cancelled' => 'bg-dark',
                                            default => 'bg-secondary',
                                        };
                                        $icon = match ($pickup->status) {
                                            'pending' => 'hourglass-split',
                                            'accepted' => 'check2',
                                            'scheduled' => 'calendar-check',
                                            'in_progress' => 'arrow-repeat',
                                            'waiting_verification' => 'hourglass',
                                            'completed' => 'check-circle-fill',
                                            'rejected' => 'x-circle-fill',
                                            'cancelled' => 'slash-circle',
                                            default => 'circle',
                                        };
                                        $label = match ($pickup->status) {
                                            'pending' => 'Menunggu',
                                            'accepted' => 'Diterima',
                                            'scheduled' => 'Dijadwalkan',
                                            'in_progress' => 'Diproses',
                                            'waiting_verification' => 'Verifikasi',
                                            'completed' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            'cancelled' => 'Dibatalkan',
                                            default => $pickup->status,
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">
                                        <i class="bi bi-{{ $icon }}"></i> {{ $label }}
                                    </span>
                                </td>
                                <td>
                                    @if($pickup->status === 'completed' && $pickup->points_earned)
                                        <span class="text-success fw-semibold">
                                            <i class="bi bi-coin"></i> +{{ number_format($pickup->points_earned) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('user.pickup-requests.show', $pickup->pickup_request_id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    @if($pickup->status === 'pending')
                                        <form action="{{ route('user.pickup-requests.destroy', $pickup->pickup_request_id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin batalkan pengajuan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle"></i> Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        <p class="mb-2">Belum ada riwayat setoran.</p>
                                        <a href="{{ route('user.pickup-requests.create') }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-plus-circle"></i> Ajukan Setoran Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pickups->hasPages())
                <div class="p-3">{{ $pickups->links() }}</div>
            @endif
        </div>
    </div>
@endsection