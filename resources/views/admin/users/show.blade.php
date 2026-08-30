@extends('template.layout')

@section('title', 'Detail User - WasteLyn')

@section('content')

    <div class="page-heading">

        <section class="section">

            {{-- Membuat card berada di tengah --}}
            <div class="d-flex justify-content-center align-items-center" style="min-height: 75vh;">

                <div class="card shadow-sm border-0" style="width: 500px; max-width: 100%;">

                    <div class="card-body p-4">

                        {{-- Judul --}}
                        <div class="mb-4">
                            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-arrow-left"></i>
                                <strong>Detail User</strong>
                            </a>
                        </div>

                        {{-- Foto Profil --}}
                        <div class="text-center mb-4">

                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="rounded-circle"
                                    style="width:85px; height:85px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-dark text-white
                                                d-inline-flex justify-content-center
                                                align-items-center" style="width:85px; height:85px; font-size:40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif

                        </div>

                        {{-- Informasi User --}}
                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Nama
                            </div>
                            <div class="col-8">
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
                                <span class="badge bg-primary">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                XP
                            </div>
                            <div class="col-8">
                                {{ $user->xp ?? 0 }}
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
                                @if($user->status === 'active' || $user->status == 1)
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($user->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-4 text-end">
                                Bergabung
                            </div>
                            <div class="col-8">
                                {{ $user->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-center gap-2 mt-4">

                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-light border">
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