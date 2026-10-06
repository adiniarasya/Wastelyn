@extends('template.layout')

@section('title', 'Edit User - WasteLyn')

@section('content')

    <div class="page-heading">

        <div class="page-title">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h3 class="fw-bold mb-1">Edit User</h3>
                        <p class="text-subtitle text-muted mb-0">
                            Perbarui informasi pengguna WasteLyn
                        </p>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                        <h5 class="card-title mb-0">Form Edit User</h5>

                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12 19 5 12 12 5"/>
                            </svg>
                            Kembali
                        </a>
                    </div>

                    <div class="card-body pt-4">
                        <form action="{{ route('admin.users.update', $user->user_id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-9">

                                    {{-- Foto Profil --}}
                                    <div class="text-center mb-4">
                                        @if($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo))
                                            <img src="{{ asset('storage/' . $user->photo) }}"
                                                alt="Foto Profil"
                                                class="rounded-circle"
                                                style="width:90px;height:90px;object-fit:cover;border:3px solid #f1f3f5;">
                                        @else
                                            <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                                                style="width:90px;height:90px;background:#E7F1FF;color:#435EBE;font-size:40px;border:3px solid #f1f3f5;">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                    <circle cx="12" cy="7" r="4"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="mt-2">
                                            <label for="photo" class="text-success small fw-semibold" style="cursor:pointer;">
                                                Ganti Foto
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Nama --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="name" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Nama <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="text" id="name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="email" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="email" id="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- No HP --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="phone" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            No HP
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="text" id="phone" name="phone"
                                                class="form-control" value="{{ old('phone', $user->phone) }}">
                                        </div>
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="row mb-3">
                                        <label for="address" class="col-4 col-md-3 text-md-end text-muted small fw-semibold pt-2 mb-0">
                                            Alamat
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <textarea id="address" name="address" rows="2"
                                                class="form-control">{{ old('address', $user->address) }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Role --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="role" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Role <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <select id="role" name="role" class="form-select" required>
                                                <option value="warga" {{ old('role', $user->role) == 'warga' ? 'selected' : '' }}>Warga</option>
                                                <option value="mitra" {{ old('role', $user->role) == 'mitra' ? 'selected' : '' }}>Mitra</option>
                                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="status" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- XP --}}
                                    <div class="row align-items-center mb-3">
                                        <label class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            XP
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="text" class="form-control" value="{{ $user->xp ?? 0 }}" disabled>
                                        </div>
                                    </div>

                                    {{-- Point --}}
                                    <div class="row align-items-center mb-3">
                                        <label class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Point
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="text" class="form-control" value="{{ $user->points ?? 0 }}" disabled>
                                        </div>
                                    </div>

                                    {{-- Foto --}}
                                    <div class="row align-items-center mb-4">
                                        <label for="photo" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Foto
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="file" id="photo" name="photo"
                                                class="form-control" accept="image/*">
                                            <small class="text-muted d-block mt-1">Format: jpg, png, webp (maks 2MB)</small>
                                        </div>
                                    </div>

                                    {{-- Separator --}}
                                    <div class="border-top pt-3 mt-2 mb-3">
                                        <small class="text-muted">
                                            <strong>Ubah Password</strong> — kosongkan jika tidak ingin mengubah
                                        </small>
                                    </div>

                                    {{-- Password --}}
                                    <div class="row align-items-center mb-3">
                                        <label for="password" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Password
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Password baru">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Konfirmasi Password --}}
                                    <div class="row align-items-center mb-4">
                                        <label for="password_confirmation" class="col-4 col-md-3 text-md-end text-muted small fw-semibold mb-0">
                                            Konfirmasi
                                        </label>
                                        <div class="col-8 col-md-9">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="form-control" placeholder="Ulangi password">
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-4">
                                            Batal
                                        </a>
                                        <button type="submit" class="btn btn-success px-4 d-inline-flex align-items-center gap-2">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                                <polyline points="17 21 17 13 7 13 7 21"/>
                                                <polyline points="7 3 7 8 15 8"/>
                                            </svg>
                                            Simpan Perubahan
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
@endsection