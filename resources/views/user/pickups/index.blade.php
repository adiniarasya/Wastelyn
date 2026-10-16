@extends('template.layout')

@section('title', 'Riwayat Setoran')

@section('content')
    <style>
        /* Warna ikon: satu keluarga hijau, menunggu kuning. Ukuran tetap bawaan Mazer. */
        .riwayat-setoran .stats-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Reset posisi ikon bawaan Mazer supaya pas di tengah kotak */
        .riwayat-setoran .stats-icon i {
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            margin: 0;
            padding: 0;
            line-height: 1;
            color: #fff;
        }

        .riwayat-setoran .stats-icon i::before {
            display: block;
            line-height: 1;
            vertical-align: 0;
        }

        .riwayat-setoran .stats-icon.wl-green  { background-color: #2E7D32; }
        .riwayat-setoran .stats-icon.wl-teal   { background-color: #00897B; }
        .riwayat-setoran .stats-icon.wl-yellow { background-color: #FFC107; }
    </style>

    @php
        $total = $pickups->total();
        $pending = $pickups->where('status', 'pending')->count();
        $completed = $pickups->where('status', 'completed')->count();
    @endphp

    <div class="page-heading riwayat-setoran">
        {{-- Header --}}
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Riwayat Setoran Sampah</h3>
                    <p class="text-subtitle text-muted">Pantau semua pengajuan setoran sampahmu.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <div class="d-flex justify-content-md-end mb-3 mb-md-0">
                        <a href="{{ route('user.pickup-requests.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Ajukan setoran
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-light-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-light-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            {{-- Bank sampah langganan --}}
            @if(auth()->user()->wasteBank)
                <div class="alert alert-info d-flex align-items-center gap-3">
                    <div>
                        <div>
                            Bank sampah langganan:
                            <strong>{{ auth()->user()->wasteBank->name }}</strong>
                        </div>
                        <small>Semua setoran otomatis dikirim ke bank sampah ini.</small>
                    </div>
                </div>
            @endif

            {{-- Statistik --}}
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-4 d-flex justify-content-start">
                                    <div class="stats-icon wl-teal mb-2">
                                        <i class="bi bi-inbox-fill fs-4"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-muted font-semibold">Total setoran</h6>
                                    <h6 class="font-extrabold mb-0">{{ $total }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-4 d-flex justify-content-start">
                                    <div class="stats-icon wl-yellow mb-2">
                                        <i class="bi bi-hourglass-split fs-4"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-muted font-semibold">Menunggu</h6>
                                    <h6 class="font-extrabold mb-0">{{ $pending }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-4 d-flex justify-content-start">
                                    <div class="stats-icon wl-green mb-2">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="text-muted font-semibold">Selesai</h6>
                                    <h6 class="font-extrabold mb-0">{{ $completed }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel (gaya tabel tidak diubah) --}}
            <div class="card">
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
                                                <a href="{{ route('user.pickup-requests.create') }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-plus-circle me-1"></i>Ajukan setoran sekarang
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

        </section>
    </div>
@endsection