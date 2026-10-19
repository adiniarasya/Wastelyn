@extends('template.layout')

@section('title', 'Reward Center')

@section('content')
    <style>
        .reward-center .reward-img {
            height: 150px;
            object-fit: cover;
        }

        .reward-center .reward-type-badge {
            font-size: .65rem;
            flex-shrink: 0;
        }

        .reward-center .code-sm {
            font-size: .85rem;
        }

        #redeemModal .modal-reward-img {
            max-height: 120px;
            object-fit: contain;
        }

        #redeemModal .point-box {
            min-width: 130px;
        }

        #redeemModal .spinner-lg {
            width: 3rem;
            height: 3rem;
        }

        #redeemModal .success-icon {
            font-size: 4rem;
            line-height: 1;
        }
    </style>

    <div class="page-heading reward-center">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h3>Reward Center</h3>
                    <p class="text-subtitle text-muted">Tukarkan poinmu dengan reward yang tersedia.</p>
                </div>
                <div class="col-12 col-md-4">
                    <div class="text-md-end">
                        <div class="small text-muted">Poin kamu</div>
                        <div class="fs-4 fw-bold text-warning">
                            ⭐ {{ number_format($user->points) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif
            <div class="row">
                @forelse ($rewards as $reward)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="card card-stat h-100 mb-0">
                            @if($reward->image)
                                <img src="{{ asset('assets/images/' . $reward->image) }}"
                                    class="card-img-top reward-img" alt="{{ $reward->name }}">
                            @endif

                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h6 class="fw-bold mb-0">{{ $reward->name }}</h6>
                                    @if(($reward->type ?? 'instant') === 'manual')
                                        <span class="badge reward-type-badge bg-info-subtle text-info border border-info-subtle">Manual</span>
                                    @elseif(($reward->type ?? 'instant') === 'pickup')
                                        <span class="badge reward-type-badge bg-warning-subtle text-warning border border-warning-subtle">Ambil sendiri</span>
                                    @else
                                        <span class="badge reward-type-badge bg-success-subtle text-success border border-success-subtle">Instan</span>
                                    @endif
                                </div>

                                <p class="small text-muted mb-3">{{ Str::limit($reward->description, 60) }}</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-warning text-dark">
                                        ⭐ {{ number_format($reward->point_required) }}
                                    </span>
                                    <small class="text-muted">Stok: {{ $reward->stock }}</small>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0">
                                @if($user->points >= $reward->point_required && $reward->stock > 0)
                                    <button type="button" class="btn btn-success btn-sm w-100 btn-redeem"
                                        data-bs-toggle="modal" data-bs-target="#redeemModal"
                                        data-reward-id="{{ $reward->reward_id }}"
                                        data-reward-name="{{ $reward->name }}"
                                        data-reward-points="{{ $reward->point_required }}"
                                        data-reward-type="{{ $reward->type ?? 'instant' }}"
                                        data-reward-image="{{ $reward->image ? asset('assets/images/' . $reward->image) : '' }}">
                                        <i class="bi bi-gift me-1"></i>Tukar
                                    </button>
                                @elseif($reward->stock <= 0)
                                    <button class="btn btn-secondary btn-sm w-100" disabled>Stok habis</button>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm w-100" disabled>Poin kurang</button>
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
            <div class="card card-stat">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Riwayat penukaran
                    </h5>
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
                                    <th>Kode / Info</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myRedemptions as $r)
                                    <tr>
                                        <td>{{ $r->redeemed_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td>{{ $r->reward->name ?? '-' }}</td>
                                        <td>-{{ number_format($r->reward->point_required ?? 0) }}</td>
                                        <td>
                                            @if($r->status === 'pending' && $r->pickup_code)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-hourglass me-1"></i>Belum diambil
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Berhasil
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($r->pickup_code)
                                                <div class="d-flex align-items-center gap-2">
                                                    <code class="code-sm text-primary fw-bold">{{ $r->pickup_code }}</code>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="copyVoucher('{{ $r->pickup_code }}', this)"
                                                        title="Salin kode">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Tunjukkan kode ini ke mitra untuk ambil barang.
                                                </small>
                                                @if($r->pickup_expires_at)
                                                    <small class="text-danger d-block">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Berlaku s.d. {{ \Carbon\Carbon::parse($r->pickup_expires_at)->format('d M Y') }}
                                                    </small>
                                                @endif
                                            @elseif($r->voucher_code)
                                                <div class="d-flex align-items-center gap-2">
                                                    <code class="code-sm text-success fw-bold">{{ $r->voucher_code }}</code>
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        onclick="copyVoucher('{{ $r->voucher_code }}', this)"
                                                        title="Salin kode voucher">
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Tukarkan kode ini di kasir / aplikasi terkait.
                                                </small>
                                            @elseif($r->delivery_note)
                                                <small class="text-success fw-semibold">
                                                    <i class="bi bi-check-circle me-1"></i>{{ $r->delivery_note }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('user.rewards.redemptions.show', $r->redemption_id) }}"
                                                class="btn btn-sm btn-outline-success" title="Lihat bukti">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            Belum ada riwayat penukaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <div class="modal fade" id="redeemModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">
                        <i class="bi bi-gift text-success me-2"></i>Konfirmasi tukar reward
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                        id="modalCloseBtn"></button>
                </div>

                <div class="modal-body text-center py-4">
                    <img id="modalRewardImage" src="" alt="" class="modal-reward-img rounded mb-3 d-none">

                    <h5 class="fw-bold mb-2" id="modalRewardName">-</h5>
                    <p class="text-muted mb-4" id="modalConfirmText">Apakah kamu yakin ingin menukar reward ini?</p>

                    <div class="d-flex justify-content-center gap-3" id="modalPointBox">
                        <div class="point-box border rounded p-3 text-center">
                            <div class="small text-muted mb-1">Poin dibutuhkan</div>
                            <div class="fs-5 fw-bold text-warning">
                                ⭐ <span id="modalRewardPoints">0</span>
                            </div>
                        </div>
                        <div class="point-box border rounded p-3 text-center">
                            <div class="small text-muted mb-1">Poin kamu</div>
                            <div class="fs-5 fw-bold text-success">
                                ⭐ {{ number_format($user->points) }}
                            </div>
                        </div>
                    </div>

                    @php
                        $userPhone = auth()->user()->phone ?? null;
                    @endphp

                    <div id="modalPhoneField" class="text-start mt-3 d-none">
                        <div id="modalEwalletField" class="d-none mb-3">
                            <label class="form-label small fw-semibold">
                                Pilih e-wallet <span class="text-danger">*</span>
                            </label>
                            <select id="modalEwalletProvider" class="form-select form-select-sm">
                                <option value="">Pilih provider</option>
                                <option value="DANA">DANA</option>
                                <option value="GoPay">GoPay</option>
                                <option value="OVO">OVO</option>
                                <option value="ShopeePay">ShopeePay</option>
                                <option value="LinkAja">LinkAja</option>
                            </select>
                        </div>

                        <label class="form-label small fw-semibold">
                            Nomor tujuan <span class="text-danger">*</span>
                        </label>

                        @if($userPhone)
                            <div class="alert alert-light border small py-2 mb-2 d-flex justify-content-between align-items-center gap-2">
                                <div>
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    Nomor kamu: <strong>{{ $userPhone }}</strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="document.getElementById('modalPhoneNumber').value='{{ $userPhone }}'; document.getElementById('modalPhoneNumber').dispatchEvent(new Event('input'));">
                                    <i class="bi bi-check-lg me-1"></i>Pakai
                                </button>
                            </div>
                        @endif

                        <input type="text" id="modalPhoneNumber" class="form-control form-control-sm"
                            value="{{ $userPhone ?? '' }}" placeholder="08xxxxxxxxxx">
                        <small class="text-muted">Ubah jika ingin mengirim ke nomor lain.</small>
                    </div>

                    <div id="modalPickupField" class="text-start mt-3 d-none">
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Setelah menukar, kamu akan mendapat <strong>kode pengambilan</strong>.
                            Tunjukkan kode tersebut ke mitra untuk mengambil barang.
                        </div>
                        <label class="form-label small fw-semibold">
                            Rencana tanggal ambil <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="modalPickupDate" class="form-control form-control-sm"
                            value="{{ now()->addDay()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}">
                        <small class="text-muted">Kode berlaku 30 hari sejak tanggal tukar.</small>
                    </div>

                    <div class="alert alert-warning mt-3 mb-0 small" id="modalWarningBox">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Poin akan langsung terpotong dan tidak bisa dikembalikan.
                    </div>

                    <div id="modalLoading" class="d-none py-4">
                        <div class="spinner-border spinner-lg text-success mb-3" role="status">
                            <span class="visually-hidden">Memuat...</span>
                        </div>
                        <h6 class="fw-bold mb-1">Memproses penukaran...</h6>
                        <p class="text-muted small mb-0">Mohon tunggu sebentar.</p>
                    </div>

                    <div id="modalSuccess" class="d-none py-4">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill success-icon text-success"></i>
                        </div>
                        <h5 class="fw-bold text-success mb-2" id="modalSuccessTitle">Berhasil!</h5>
                        <p class="text-muted mb-3" id="modalSuccessMessage">Reward berhasil ditukar.</p>

                        <div id="modalSuccessPhoneBox" class="alert alert-success small mb-0 d-none">
                            <i class="bi bi-telephone-fill me-1"></i>
                            Nomor tujuan: <strong id="modalSuccessPhone">-</strong>
                        </div>

                        <div id="modalSuccessPickupBox" class="d-none">
                            <div class="alert alert-primary small mb-2">
                                <i class="bi bi-key-fill me-1"></i>Kode pengambilan:
                                <div class="mt-2">
                                    <code class="fs-4 fw-bold text-primary" id="modalSuccessCode">-</code>
                                </div>
                            </div>
                            <small class="text-muted d-block">
                                <i class="bi bi-clock me-1"></i>
                                Berlaku s.d. <strong id="modalSuccessExpiry">-</strong>
                            </small>
                            <small class="text-muted d-block mt-1">
                                Tunjukkan kode ini ke mitra untuk ambil barang.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0" id="modalFooterNormal">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="redeemSubmitBtn">
                        <i class="bi bi-check-lg me-1"></i>Ya, tukar sekarang
                    </button>
                </div>
                <div class="modal-footer border-0 pt-0 d-none" id="modalFooterSuccess">
                    <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal"
                        onclick="location.reload();">
                        <i class="bi bi-check-lg me-1"></i>Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const redeemModal = document.getElementById('redeemModal');
            if (!redeemModal) return;

            let currentRewardId = null;
            let currentRewardType = 'instant';
            let currentRewardName = '';
            let isEwallet = false;

            redeemModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                currentRewardId = button.getAttribute('data-reward-id');
                currentRewardType = button.getAttribute('data-reward-type');
                currentRewardName = button.getAttribute('data-reward-name');
                const rewardPoints = button.getAttribute('data-reward-points');
                const rewardImage = button.getAttribute('data-reward-image');

                isEwallet = /e-wallet|ewallet|dana|gopay|ovo|shopeepay/i.test(currentRewardName);

                document.getElementById('modalRewardName').textContent = currentRewardName;
                document.getElementById('modalRewardPoints').textContent =
                    new Intl.NumberFormat('id-ID').format(rewardPoints);

                const imgEl = document.getElementById('modalRewardImage');
                if (rewardImage) {
                    imgEl.src = rewardImage;
                    imgEl.classList.remove('d-none');
                } else {
                    imgEl.classList.add('d-none');
                }

                document.getElementById('modalConfirmText').classList.remove('d-none');
                document.getElementById('modalPointBox').classList.remove('d-none');
                document.getElementById('modalWarningBox').classList.remove('d-none');
                document.getElementById('modalLoading').classList.add('d-none');
                document.getElementById('modalSuccess').classList.add('d-none');
                document.getElementById('modalSuccessPhoneBox').classList.add('d-none');
                document.getElementById('modalSuccessPickupBox').classList.add('d-none');
                document.getElementById('modalFooterNormal').classList.remove('d-none');
                document.getElementById('modalFooterSuccess').classList.add('d-none');
                document.getElementById('modalCloseBtn').classList.remove('d-none');

                const phoneField = document.getElementById('modalPhoneField');
                const pickupField = document.getElementById('modalPickupField');
                const ewalletField = document.getElementById('modalEwalletField');
                const providerSelect = document.getElementById('modalEwalletProvider');

                providerSelect.value = '';

                if (currentRewardType === 'manual') {
                    phoneField.classList.remove('d-none');
                    pickupField.classList.add('d-none');

                    if (isEwallet) {
                        ewalletField.classList.remove('d-none');
                    } else {
                        ewalletField.classList.add('d-none');
                    }
                } else if (currentRewardType === 'pickup') {
                    phoneField.classList.add('d-none');
                    pickupField.classList.remove('d-none');
                } else {
                    phoneField.classList.add('d-none');
                    pickupField.classList.add('d-none');
                }

                checkForm();
            });

            const phoneInput = document.getElementById('modalPhoneNumber');
            const pickupDateInput = document.getElementById('modalPickupDate');
            const providerSelect = document.getElementById('modalEwalletProvider');
            const submitBtn = document.getElementById('redeemSubmitBtn');

            function checkForm() {
                if (currentRewardType === 'manual') {
                    const phoneOk = phoneInput.value.trim().length >= 8;
                    const providerOk = !isEwallet || (providerSelect.value !== '');
                    submitBtn.disabled = !(phoneOk && providerOk);
                } else if (currentRewardType === 'pickup') {
                    submitBtn.disabled = !pickupDateInput.value;
                } else {
                    submitBtn.disabled = false;
                }
            }

            phoneInput.addEventListener('input', checkForm);
            pickupDateInput.addEventListener('change', checkForm);
            providerSelect.addEventListener('change', checkForm);

            submitBtn.addEventListener('click', function () {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');

                let successMessage = '';

                if (currentRewardType === 'manual') {
                    const phone = phoneInput.value.trim();
                    if (phone.length < 8) {
                        alert('Nomor tujuan minimal 8 digit.');
                        return;
                    }

                    formData.append('phone_number', phone);

                    if (isEwallet) {
                        const provider = providerSelect.value;
                        if (!provider) {
                            alert('Pilih provider e-wallet dulu.');
                            return;
                        }
                        formData.append('wallet_provider', provider);
                        successMessage = 'Saldo ' + provider + ' telah terkirim ke ' + phone;
                    } else {
                        successMessage = 'Pulsa telah terkirim ke ' + phone;
                    }
                } else if (currentRewardType === 'pickup') {
                    formData.append('pickup_date', pickupDateInput.value);
                }

                document.getElementById('modalConfirmText').classList.add('d-none');
                document.getElementById('modalPointBox').classList.add('d-none');
                document.getElementById('modalPhoneField').classList.add('d-none');
                document.getElementById('modalPickupField').classList.add('d-none');
                document.getElementById('modalWarningBox').classList.add('d-none');
                document.getElementById('modalFooterNormal').classList.add('d-none');
                document.getElementById('modalCloseBtn').classList.add('d-none');
                document.getElementById('modalLoading').classList.remove('d-none');

                fetch(`/user/rewards/${currentRewardId}/redeem`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Gagal');
                    }

                    setTimeout(() => {
                        document.getElementById('modalLoading').classList.add('d-none');
                        document.getElementById('modalSuccess').classList.remove('d-none');
                        document.getElementById('modalFooterSuccess').classList.remove('d-none');

                        if (currentRewardType === 'pickup') {
                            document.getElementById('modalSuccessTitle').textContent = 'Berhasil!';
                            document.getElementById('modalSuccessMessage').textContent = 'Simpan kode pengambilan ini:';
                            document.getElementById('modalSuccessCode').textContent = data.pickup_code;
                            document.getElementById('modalSuccessExpiry').textContent = data.pickup_expires_at;
                            document.getElementById('modalSuccessPickupBox').classList.remove('d-none');
                        } else if (currentRewardType === 'manual') {
                            document.getElementById('modalSuccessTitle').textContent = 'Berhasil!';
                            document.getElementById('modalSuccessMessage').textContent = successMessage;
                            document.getElementById('modalSuccessPhone').textContent = data.phone_number;
                            document.getElementById('modalSuccessPhoneBox').classList.remove('d-none');
                        } else {
                            document.getElementById('modalSuccessTitle').textContent = 'Berhasil!';
                            document.getElementById('modalSuccessMessage').textContent = 'Voucher berhasil didapat.';
                        }
                    }, 2000 + Math.random() * 1000);
                })
                .catch(error => {
                    document.getElementById('modalLoading').classList.add('d-none');
                    document.getElementById('modalConfirmText').classList.remove('d-none');
                    document.getElementById('modalPointBox').classList.remove('d-none');
                    document.getElementById('modalWarningBox').classList.remove('d-none');
                    document.getElementById('modalFooterNormal').classList.remove('d-none');
                    document.getElementById('modalCloseBtn').classList.remove('d-none');

                    if (currentRewardType === 'manual') {
                        document.getElementById('modalPhoneField').classList.remove('d-none');
                    } else if (currentRewardType === 'pickup') {
                        document.getElementById('modalPickupField').classList.remove('d-none');
                    }

                    alert(error.message || 'Gagal memproses. Coba lagi.');
                });
            });
        });

        function copyVoucher(code, btn) {
            navigator.clipboard.writeText(code).then(function () {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg"></i>';
                btn.classList.remove('btn-outline-success', 'btn-outline-primary');
                btn.classList.add('btn-success');

                setTimeout(function () {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-success');
                }, 1500);
            }).catch(function () {
                alert('Kode: ' + code);
            });
        }
    </script>
@endsection