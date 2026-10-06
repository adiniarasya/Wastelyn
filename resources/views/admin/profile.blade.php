@extends('template.layout')

@section('title', 'Profil Admin - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title mb-4">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Profil Admin</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Kelola informasi profil akun kamu
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Informasi Akun</h5>
                </div>

                <div class="card-body pt-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"
                            role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            {{ session('success') }}
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-start gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <div>
                                <strong>Periksa kembali data Anda:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="rounded-circle d-inline-flex justify-content-center align-items-center"
                            style="width:90px;height:90px;background:#E7F1FF;color:#435EBE;font-size:36px;border:3px solid #f1f3f5;">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <h5 class="fw-bold mt-3 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted small mb-0">{{ $user->email }}</p>
                        <span class="badge bg-danger mt-2">Admin</span>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-10">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold small text-muted">
                                            Nama <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold small text-muted">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold small text-muted">
                                            Nomor HP
                                        </label>
                                        <input type="text" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-muted">
                                            Role
                                        </label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                                        <small class="text-muted d-block mt-1">
                                            Role tidak dapat diubah.
                                        </small>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold small text-muted">
                                            Alamat
                                        </label>
                                        <textarea id="address" name="address" rows="3"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-4 mb-3">
                                    <small class="text-muted">
                                        <strong>Ubah Password</strong> — kosongkan jika tidak ingin mengubah
                                    </small>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold small text-muted">
                                            Password Baru
                                        </label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold small text-muted">
                                            Konfirmasi Password
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Ulangi password">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <button type="reset" class="btn btn-light border px-4">
                                        Reset
                                    </button>
                                    <button type="submit"
                                        class="btn btn-success px-4 d-inline-flex align-items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                            <polyline points="17 21 17 13 7 13 7 21" />
                                            <polyline points="7 3 7 8 15 8" />
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