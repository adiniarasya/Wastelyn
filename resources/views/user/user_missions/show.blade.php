@extends('template.layout')

@section('title', 'Detail Waste Mission - WasteLyn')

@section('content')
    <style>
        .mission-detail .mission-cover {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
        }

        /* Ukuran foto di riwayat bukti */
        .mission-detail .submission-thumb {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            object-fit: cover;
        }
    </style>

    @php
        $statusLabels = [
            'ongoing' => 'Berlangsung',
            'ready_pickup' => 'Siap dijemput',
            'picked_up' => 'Sudah dijemput',
            'completed' => 'Selesai',
        ];
    @endphp

    <div class="page-heading mission-detail">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h3>Detail Waste Mission</h3>
                    <p class="text-subtitle text-muted">
                        {{ $userMission ? 'Lanjutkan progres misimu.' : 'Mulai misi dan dapatkan reward.' }}
                    </p>
                </div>
                <div class="col-12 col-md-4">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.user-missions.index') }}">Waste Mission</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            @foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $type)
                @if (session($key))
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
            @endforeach

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <h4 class="card-title mb-0">{{ $mission->title }}</h4>
                    <span class="badge bg-success">{{ ucfirst($mission->status) }}</span>
                </div>

                <div class="card-body">
                    @if($mission->image)
                        <img src="{{ asset('storage/' . $mission->image) }}" alt="{{ $mission->title }}"
                            class="mission-cover rounded-3 mb-4">
                    @endif

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Deskripsi misi</h6>
                        <p class="mb-0">{{ $mission->description }}</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-bullseye me-1"></i>Target
                                </small>
                                <h6 class="mb-0">{{ $mission->target }} {{ $mission->unit ?? 'item' }}</h6>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>Reward XP
                                </small>
                                <h6 class="mb-0">{{ $mission->reward_xp }} XP</h6>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-coin me-1"></i>Reward poin
                                </small>
                                <h6 class="mb-0">{{ $mission->reward_points }} Poin</h6>
                            </div>
                        </div>
                    </div>

                    @if ($userMission)
                        @php
                            $progressPercent = $mission->target > 0
                                ? min(100, round(($userMission->progress / $mission->target) * 100))
                                : 0;
                        @endphp

                        <div class="border-top mt-4 pt-4">
                        {{-- Progres --}}
                        <div class="border-top mt-4 pt-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Progres misi kamu</h5>
                                <span class="badge bg-warning text-dark">
                                    {{ $statusLabels[$userMission->status] ?? $userMission->status }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Progres</span>
                                <strong>
                                    {{ $userMission->progress }} / {{ $mission->target }}
                                    {{ $mission->unit ?? 'item' }}
                                    ({{ $progressPercent }}%)
                                </strong>
                            </div>

                            <div class="progress progress-success">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $progressPercent }}%;"
                                    aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning d-flex align-items-start gap-3 mt-4">
                            <i class="bi bi-key-fill fs-4"></i>
                            <div>
                                <div>
                                    Kode misi kamu:
                                    <span class="badge bg-dark fs-6 ms-1">{{ $userMission->unique_code }}</span>
                                </div>
                                <small>
                                    Tulis kode ini di kertas, lalu foto bersama sampahmu agar AI bisa memverifikasinya.
                                </small>
                            </div>
                        </div>

                        @if ($userMission->status === 'ongoing')
                            <div class="card border-success mb-4">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0 text-white">
                                        <i class="bi bi-camera-fill me-1"></i>Upload bukti
                                    </h6>
                                </div>
                                <div class="card-body pt-4">
                                    <form action="{{ route('user.submissions.store', $userMission->user_mission_id) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Foto bukti <span class="text-danger">*</span></label>
                                            <input type="file" name="photo" accept="image/*" capture="environment"
                                                required class="form-control @error('photo') is-invalid @enderror">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">
                                                Maksimal 5MB. Foto sampah yang sudah kamu kumpulkan.
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Catatan (opsional)</label>
                                            <textarea name="note" rows="2" maxlength="200" class="form-control"
                                                placeholder="Contoh: baru mengumpulkan 3 botol"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-cloud-upload me-1"></i>Kirim bukti
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($userMission->status === 'ready_pickup')
                            <div class="alert alert-info d-flex align-items-center gap-3">
                                <i class="bi bi-truck fs-4"></i>
                                <div>
                                    Misi selesai. Sampahmu siap dijemput oleh bank sampah.
                                    <a href="#" class="alert-link">Ajukan jemput</a>
                                </div>
                            </div>
                        @elseif ($userMission->status === 'picked_up')
                            <div class="alert alert-primary d-flex align-items-center gap-3">
                                <i class="bi bi-check-circle-fill fs-4"></i>
                                <div>Sampahmu sudah dijemput. Menunggu verifikasi akhir.</div>
                            </div>
                        @elseif ($userMission->status === 'completed')
                            <div class="alert alert-success d-flex align-items-center gap-3">
                                <i class="bi bi-trophy-fill fs-4"></i>
                                <div>
                                    Misi selesai. Kamu mendapat {{ $mission->reward_xp }} XP dan
                                    {{ $mission->reward_points }} poin.
                                </div>
                            </div>
                        @endif
                        @if ($userMission->submissions && $userMission->submissions->count() > 0)
                            <div class="border-top pt-4">
                                <h6 class="mb-3">Riwayat bukti</h6>
                                @foreach ($userMission->submissions as $sub)
                                    <div class="d-flex align-items-center gap-3 border-bottom py-2">
                                        <img src="{{ asset('storage/' . $sub->photo_path) }}" alt="Bukti setoran"
                                            class="submission-thumb rounded">
                                        <div class="flex-grow-1">
                                            <strong>+{{ $sub->detected_count }} {{ $mission->unit ?? 'item' }}</strong>
                                            <div class="small text-muted">
                                                {{ $sub->created_at->diffForHumans() }}
                                                @if ($sub->note)
                                                    &middot; {{ $sub->note }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="border-top mt-4 pt-3">
                            <a href="{{ route('user.user-missions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    @else
                        <div class="border-top mt-4 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('user.user-missions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>

                            <form id="confirmMissionForm" action="{{ route('user.user-missions.store') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="mission_id" value="{{ $mission->mission_id }}">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#confirmMissionModal">
                                    <i class="bi bi-check-circle me-1"></i>Ikuti misi
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    </div>
    
    <div class="modal fade" id="confirmMissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi ikuti misi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Yakin ingin mengikuti misi ini?</p>
                    <p class="text-muted mb-0">Setelah mengikuti, progres misi akan dimulai dari 0.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="confirmMissionForm" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Ya, ikuti misi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection