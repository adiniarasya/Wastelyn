@extends('template.layout')

@section('title', 'Detail User - WasteLyn')

@section('content')
    <div class="page-heading">
        <section class="section">
            <div class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">
                <div class="card shadow-sm border-0" style="width: 500px; max-width: 100%;">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-arrow-left me-1"></i>
                                <strong>Detail User</strong>
                            </a>
                        </div>

                        <div class="text-center mb-4">
                            @if(
                                    $user->photo &&
                                    \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)
                                )
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="rounded-circle"
                                    style="width:85px; height:85px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-dark text-white d-inline-flex justify-content-center align-items-center"
                                    style="width:85px; height:85px; font-size:40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Nama
                            </div>
                            <div class="col-8 fw-semibold">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Email
                            </div>
                            <div class="col-8">
                                {{ $user->email }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                No HP
                            </div>
                            <div class="col-8">
                                {{ $user->phone ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Alamat
                            </div>
                            <div class="col-8">
                                {{ $user->address ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Role
                            </div>
                            <div class="col-8">
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">
                                        Admin
                                    </span>
                                @elseif($user->role === 'mitra')
                                    <span class="badge bg-success">
                                        Mitra
                                    </span>
                                @else
                                    <span class="badge bg-primary">
                                        Warga
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                XP
                            </div>
                            <div class="col-8">
                                {{ number_format($user->xp ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Poin
                            </div>
                            <div class="col-8">
                                {{ number_format($user->points ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Level
                            </div>
                            <div class="col-8">
                                {{ $user->level ?? 1 }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Status
                            </div>
                            <div class="col-8">
                                @if($user->status === 'active')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Aktif
                                    </span>
                                @elseif($user->status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>
                                        Pending
                                    </span>
                                @elseif($user->status === 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Ditolak
                                    </span>
                                @elseif($user->status === 'inactive')
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-dash-circle me-1"></i>
                                        Nonaktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Tidak diketahui
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Bergabung
                            </div>
                            <div class="col-8">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-light border">
                                <i class="bi bi-pencil me-1"></i>
                                Edit
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-light border">
                                Kembali
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection