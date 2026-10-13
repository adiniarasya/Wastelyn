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
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Total Penukaran</div>
                    <div class="fs-3 fw-bold text-primary">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Perlu Diambil</div>
                    <div class="fs-3 fw-bold text-warning">{{ $stats['pending_pickup'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Selesai</div>
                    <div class="fs-3 fw-bold text-success">{{ $stats['completed'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat">
                <div class="card-body">
                    <div class="text-muted small">Hari Ini</div>
                    <div class="fs-3 fw-bold text-info">{{ $stats['today'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB FILTER --}}
    <div class="card card-stat mb-3">
        <div class="card-body pb-2">
            <ul class="nav nav-pills gap-2 flex-wrap">
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'all' && $type === 'all' ? 'active' : 'bg-light text-dark' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => 'all', 'type' => 'all']) }}">
                        Semua <span class="badge bg-secondary ms-1">{{ $stats['total'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'pending' ? 'active' : 'bg-warning-subtle text-warning-emphasis' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => 'pending', 'type' => 'all']) }}">
                        <i class="bi bi-hourglass"></i> Perlu Diambil
                        <span class="badge bg-warning text-dark ms-1">{{ $stats['pending_pickup'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'completed' ? 'active' : 'bg-light text-dark' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => 'completed', 'type' => 'all']) }}">
                        <i class="bi bi-check-circle"></i> Selesai
                        <span class="badge bg-success ms-1">{{ $stats['completed'] }}</span>
                    </a>
                </li>
                <li class="nav-item ms-md-auto">
                    <span class="nav-link disabled text-muted small">Filter Tipe:</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'manual' ? 'active' : 'bg-info-subtle text-info-emphasis' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => $status, 'type' => 'manual']) }}">
                        <i class="bi bi-phone"></i> Pulsa/E-Wallet
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'instant' ? 'active' : 'bg-success-subtle text-success-emphasis' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => $status, 'type' => 'instant']) }}">
                        <i class="bi bi-ticket-perforated"></i> Voucher
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type === 'pickup' ? 'active' : 'bg-warning-subtle text-warning-emphasis' }}"
                       href="{{ route('mitra.rewards.redemptions', ['status' => $status, 'type' => 'pickup']) }}">
                        <i class="bi bi-bag"></i> Pickup
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- TABEL --}}
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
                            <th>Detail</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redemptions as $r)
                            @php
                                $expiresSoon = false;
                                if ($r->pickup_expires_at && $r->status === 'pending') {
                                    $diff = \Carbon\Carbon::parse($r->pickup_expires_at)->diffInDays(now(), false);
                                    $expiresSoon = $diff >= -7 && $diff <= 0;
                                }
                                $expired = $r->pickup_expires_at && now()->greaterThan($r->pickup_expires_at) && $r->status === 'pending';
                            @endphp
                            <tr class="{{ $expired ? 'table-danger' : ($expiresSoon ? 'table-warning' : '') }}">
                                <td>{{ $r->redeemed_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <strong>{{ $r->user->name ?? '-' }}</strong>
                                    <br><small class="text-muted">{{ $r->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    {{ $r->reward->name ?? '-' }}
                                    @if(($r->reward->type ?? 'instant') === 'manual')
                                        <br><small class="badge bg-info-subtle text-info border border-info-subtle">Manual</small>
                                    @elseif(($r->reward->type ?? 'instant') === 'pickup')
                                        <br><small class="badge bg-warning-subtle text-warning border border-warning-subtle">Pickup</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        ⭐ {{ number_format($r->reward->point_required ?? 0) }}
                                    </span>
                                </td>
                                <td>
                                    @if($r->pickup_code)
                                        <small class="text-muted d-block">Kode Ambil:</small>
                                        <code class="text-primary fw-bold" style="font-size: 0.8rem;">{{ $r->pickup_code }}</code>
                                        @if($r->pickup_expires_at)
                                            @if($expired)
                                                <small class="text-danger fw-bold d-block mt-1">
                                                    <i class="bi bi-exclamation-triangle"></i> KADALUARSA
                                                </small>
                                            @elseif($expiresSoon)
                                                <small class="text-warning fw-bold d-block mt-1">
                                                    <i class="bi bi-exclamation-triangle"></i> Segera Diambil!
                                                </small>
                                            @endif
                                            <small class="text-danger d-block mt-1">
                                                <i class="bi bi-clock"></i> Berlaku s.d. {{ \Carbon\Carbon::parse($r->pickup_expires_at)->format('d M Y') }}
                                            </small>
                                        @endif
                                    @elseif($r->voucher_code)
                                        <small class="text-muted d-block">Kode:</small>
                                        <code class="text-success" style="font-size: 0.8rem;">{{ $r->voucher_code }}</code>
                                    @elseif($r->delivery_note)
                                        <small class="text-dark">{{ $r->delivery_note }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass"></i> Perlu Diambil
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.rewards.redemptions.show', $r->redemption_id) }}"
                                       class="btn btn-sm btn-outline-success me-1"
                                       title="Lihat bukti transaksi">
                                        <i class="bi bi-receipt"></i>
                                    </a>

                                    @if($r->status === 'pending' && $r->pickup_code)
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verifyPickupModal"
                                                data-redemption-id="{{ $r->redemption_id }}"
                                                data-reward-name="{{ $r->reward->name }}"
                                                data-pickup-code="{{ $r->pickup_code }}"
                                                data-user-name="{{ $r->user->name }}">
                                            <i class="bi bi-key"></i> Verifikasi & Selesaikan
                                        </button>
                                    @elseif($r->status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass"></i> Perlu Diambil
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle"></i> Berhasil
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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

    {{-- MODAL VERIFIKASI PICKUP --}}
    <div class="modal fade" id="verifyPickupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-key text-primary"></i> Verifikasi Pengambilan Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="verifyPickupForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="modal-body py-4">
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle"></i>
                            Minta warga menunjukkan <strong>kode pengambilan</strong>, lalu input kode tersebut di bawah ini.
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Warga</label>
                            <input type="text" id="verifyUserName" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Reward</label>
                            <input type="text" id="verifyRewardName" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-semibold">
                                Kode Pengambilan <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="pickup_code"
                                   id="verifyPickupCode"
                                   class="form-control form-control-sm text-center fw-bold"
                                   style="font-size: 1.2rem; letter-spacing: 2px;"
                                   placeholder="AMBIL-XXXXXX"
                                   autocomplete="off"
                                   required>
                            <small class="text-muted">Kode dari halaman warga. Huruf kapital.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const verifyModal = document.getElementById('verifyPickupModal');
            if (!verifyModal) return;

            verifyModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const redemptionId = button.getAttribute('data-redemption-id');
                const rewardName = button.getAttribute('data-reward-name');
                const userName = button.getAttribute('data-user-name');

                document.getElementById('verifyUserName').value = userName;
                document.getElementById('verifyRewardName').value = rewardName;
                document.getElementById('verifyPickupCode').value = '';

                document.getElementById('verifyPickupForm').action =
                    `/mitra/rewards/redemptions/${redemptionId}/verify-pickup`;
            });
        });
    </script>
@endsection