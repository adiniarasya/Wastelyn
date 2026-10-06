@extends('template.layout')

@section('title', 'Detail User - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Detail User</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Informasi lengkap pengguna WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Informasi User</h5>

                    <a href="{{ route('admin.users.index') }}"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="card-body pt-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">

                            {{-- Foto Profil --}}
                            <div class="text-center mb-4">
                                @if($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo))
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}"
                                        class="rounded-circle"
                                        style="width:100px;height:100px;object-fit:cover;border:3px solid #f1f3f5;">
                                @else
                                    <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                        style="width:100px;height:100px;background:#E7F1FF;color:#435EBE;font-size:44px;border:3px solid #f1f3f5;">
                                        <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                    </div>
                                @endif

                                <h5 class="fw-bold mt-3 mb-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-0">{{ $user->email }}</p>

                                <div class="mt-2">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($user->role === 'mitra')
                                        <span class="badge bg-success">Mitra</span>
                                    @else
                                        <span class="badge bg-primary">Warga</span>
                                    @endif

                                    @if($user->status === 'active')
                                        <span class="badge bg-success badge-icon ms-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Aktif
                                        </span>
                                    @elseif($user->status === 'pending')
                                        <span class="badge bg-warning text-dark badge-icon ms-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            Pending
                                        </span>
                                    @elseif($user->status === 'rejected')
                                        <span class="badge bg-danger badge-icon ms-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                            Ditolak
                                        </span>
                                    @elseif($user->status === 'inactive')
                                        <span class="badge bg-secondary badge-icon ms-1">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12" />
                                            </svg>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Statistik Singkat --}}
                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <div class="text-center p-3 rounded-3" style="background:#E7F1FF;">
                                        <div class="fw-bold fs-5" style="color:#435EBE;">
                                            {{ number_format($user->xp ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted">XP</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 rounded-3" style="background:#E6F7EE;">
                                        <div class="fw-bold fs-5" style="color:#2E7D32;">
                                            {{ number_format($user->points ?? 0, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted">Point</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-3 rounded-3" style="background:#FFF4E0;">
                                        <div class="fw-bold fs-5" style="color:#D97706;">
                                            {{ $user->level ?? 1 }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted">Level</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Info --}}
                            <div class="border-top pt-3">
                                <h6 class="fw-bold mb-3">Informasi Kontak</h6>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                        No HP
                                    </div>
                                    <div class="col-8 col-md-9">
                                        {{ $user->phone ?? '-' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                        Alamat
                                    </div>
                                    <div class="col-8 col-md-9">
                                        {{ $user->address ?? '-' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                        Bergabung
                                    </div>
                                    <div class="col-8 col-md-9">
                                        {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                                    </div>
                                </div>

                                @if($user->updated_at && $user->updated_at != $user->created_at)
                                    <div class="row mb-3">
                                        <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                            Terakhir Update
                                        </div>
                                        <div class="col-8 col-md-9">
                                            {{ $user->updated_at->format('d F Y, H:i') }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-4">
                                    Kembali
                                </a>
                                <a href="{{ route('admin.users.edit', $user->user_id) }}"
                                    class="btn btn-success px-4 d-inline-flex align-items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Edit User
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .badge-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-icon svg {
            display: block;
        }
    </style>
@endpush