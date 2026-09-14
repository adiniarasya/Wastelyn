@extends('template.layout')

@section('content')
<div class="page-heading">
    <h3>Detail Waste Mission</h3>
    <p class="text-subtitle text-muted">
        {{ $userMission ? 'Lanjutkan progres misimu!' : 'Yuk, mulai misi dan dapatkan reward!' }}
    </p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">

            {{-- Alert --}}
            @foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $type)
                @if (session($key))
                    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @endforeach

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $mission->title }}</h4>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Deskripsi Misi</h6>
                        <p class="mb-0">{{ $mission->description }}</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Target</small>
                                <h6 class="mb-0">
                                    {{ $mission->target }} {{ $mission->unit ?? 'item' }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Reward XP</small>
                                <h6 class="mb-0">{{ $mission->reward_xp }} XP</h6>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <small class="text-muted d-block mb-1">Reward Points</small>
                                <h6 class="mb-0">{{ $mission->reward_points }} Points</h6>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <strong class="me-2">Status Misi:</strong>
                        <span class="badge bg-success">{{ $mission->status }}</span>
                    </div>

                    @if ($userMission)
                        <hr>

                        {{-- Progress --}}
                        @php
                            $progressPercent = $mission->target > 0
                                ? min(100, round(($userMission->progress / $mission->target) * 100))
                                : 0;
                        @endphp

                        <div class="mb-4">
                            <h5 class="mb-3">Progres Misi Kamu</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Progress</span>
                                <strong>
                                    {{ $userMission->progress }} / {{ $mission->target }}
                                    {{ $mission->unit ?? 'item' }}
                                    ({{ $progressPercent }}%)
                                </strong>
                            </div>

                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     style="width: {{ $progressPercent }}%;"
                                     aria-valuenow="{{ $progressPercent }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ $progressPercent }}%
                                </div>
                            </div>
                        </div>

                        {{-- Kode Unik --}}
                        <div class="alert alert-warning">
                            <i class="bi bi-key me-2"></i>
                            <strong>Kode Misi Kamu:</strong>
                            <span class="badge bg-dark fs-6 ms-2">{{ $userMission->unique_code }}</span>
                            <div class="mt-2 small">
                                Tulis kode ini di kertas, lalu foto bareng sampahmu
                                biar AI bisa verifikasi.
                            </div>
                        </div>

                        {{-- Form Upload Bukti (hanya muncul kalau status ongoing) --}}
                        @if ($userMission->status === 'ongoing')
                            <div class="card border-success mb-4">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-camera me-1"></i>
                                        Upload Bukti
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('user.submissions.store', $userMission->user_mission_id) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label">Foto Bukti *</label>
                                            <input type="file"
                                                   name="photo"
                                                   accept="image/*"
                                                   capture="environment"
                                                   required
                                                   class="form-control @error('photo') is-invalid @enderror">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">
                                                Maks 5MB. Foto bareng kode misi
                                                <b>{{ $userMission->unique_code }}</b>.
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Catatan (opsional)</label>
                                            <textarea name="note"
                                                      rows="2"
                                                      maxlength="200"
                                                      class="form-control"
                                                      placeholder="Misal: baru kumpulin 3 botol"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-cloud-upload me-1"></i>
                                            Kirim Bukti
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- Status info --}}
                        @if ($userMission->status === 'ready_pickup')
                            <div class="alert alert-info">
                                <i class="bi bi-truck me-2"></i>
                                Misi selesai! Sampahmu siap dijemput oleh bank sampah.
                                <a href="#" class="alert-link">Ajukan jemput →</a>
                                {{-- nanti diganti route pickup --}}
                            </div>
                        @elseif ($userMission->status === 'picked_up')
                            <div class="alert alert-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                Sampahmu sudah dijemput. Menunggu verifikasi akhir.
                            </div>
                        @elseif ($userMission->status === 'completed')
                            <div class="alert alert-success">
                                <i class="bi bi-trophy me-2"></i>
                                Misi selesai! Kamu dapat
                                {{ $mission->reward_xp }} XP &
                                {{ $mission->reward_points }} Points.
                            </div>
                        @endif

                        {{-- Riwayat submission --}}
                        @if ($userMission->submissions && $userMission->submissions->count() > 0)
                            <hr>
                            <h6 class="mb-3">Riwayat Bukti</h6>
                            @foreach ($userMission->submissions as $sub)
                                <div class="d-flex align-items-center border-bottom py-2">
                                    <div class="me-3">
                                        <img src="{{ asset('storage/' . $sub->photo_path) }}"
                                             alt="bukti"
                                             style="width: 60px; height: 60px; object-fit: cover;"
                                             class="rounded">
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>+{{ $sub->detected_count }} {{ $mission->unit ?? 'item' }}</strong>
                                        <div class="small text-muted">
                                            {{ $sub->created_at->diffForHumans() }}
                                            @if ($sub->note)
                                                — {{ $sub->note }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <hr>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('user.user-missions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            <span class="badge bg-warning text-dark p-2">
                                Status: {{ $userMission->status }}
                            </span>
                        </div>

                    @else
                        <hr>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('user.user-missions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>

                            <form id="confirmMissionForm"
                                  action="{{ route('user.user-missions.store') }}"
                                  method="POST">
                                @csrf
                                <input type="hidden" name="mission_id" value="{{ $mission->mission_id }}">
                                <button type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmMissionModal">
                                    <i class="bi bi-check-circle me-1"></i> Ikuti Misi
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Modal Konfirmasi Ikuti Misi --}}
<div class="modal fade" id="confirmMissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Ikuti Misi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Yakin ingin mengikuti misi ini?</p>
                <p class="text-muted mb-0">
                    Setelah mengikuti, progres misi akan dimulai dari 0.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" form="confirmMissionForm" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Ya, Ikuti Misi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection