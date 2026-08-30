@extends('template.layout')

@section('title', 'Tambah User - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold">Tambah User</h3>
                    <p class="text-subtitle text-muted">
                        Tambahkan data pengguna baru ke WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">Form Tambah User</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            {{-- Foto Profil --}}
                            <div class="col-12 mb-4">
                                <label class="form-label">
                                    Foto Profil
                                </label>

                                <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo"
                                    accept=".jpg,.jpeg,.png,.webp">

                                <small class="text-muted">
                                    Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                                </small>

                                @error('photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Nama
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" placeholder="Masukkan nama user" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Email
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="Masukkan email user" required>

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Password
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="Minimal 8 karakter" required>

                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Konfirmasi Password
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" class="form-control" name="password_confirmation"
                                        placeholder="Ulangi password" required>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Role
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select @error('role') is-invalid @enderror" name="role" required>

                                        <option value="">Pilih Role</option>

                                        <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>
                                            Warga
                                        </option>

                                        <option value="mitra" {{ old('role') == 'mitra' ? 'selected' : '' }}>
                                            Mitra
                                        </option>

                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>

                                    </select>

                                    @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        No. Telepon
                                    </label>

                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">

                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        Alamat
                                    </label>

                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                        rows="3" placeholder="Masukkan alamat user">{{ old('address') }}</textarea>

                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light border">
                                <i class="bi bi-arrow-left"></i>
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                Simpan User
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection