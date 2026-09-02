@extends('template.layout')

@section('title', 'Edit User - WasteLyn')

@section('content')

<div class="page-heading">

    <section class="section">

        <div class="d-flex justify-content-center align-items-center"
             style="min-height: 75vh;">

            <div class="card shadow-sm border-0"
                 style="width: 500px; max-width: 100%;">

                <div class="card-body p-4">

                    <div class="mb-4">
                        <a href="{{ route('admin.users.index') }}"
                           class="text-decoration-none text-dark">
                            <i class="bi bi-arrow-left"></i>
                            <strong>Edit User</strong>
                        </a>
                    </div>

                    <form action="{{ route('admin.users.update', $user->user_id) }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">

                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}"
                                     alt="Foto Profil"
                                     class="rounded-circle"
                                     style="width:85px; height:85px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-dark text-white d-inline-flex
                                            justify-content-center align-items-center"
                                     style="width:85px; height:85px; font-size:40px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="name" class="form-label mb-0">
                                    Nama
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control form-control-sm @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}"
                                       required>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="email" class="form-label mb-0">
                                    Email
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control form-control-sm @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}"
                                       required>

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="phone" class="form-label mb-0">
                                    No HP
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="text"
                                       id="phone"
                                       name="phone"
                                       class="form-control form-control-sm"
                                       value="{{ old('phone', $user->phone) }}">
                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="address" class="form-label mb-0">
                                    Alamat
                                </label>
                            </div>

                            <div class="col-8">
                                <textarea id="address"
                                          name="address"
                                          rows="2"
                                          class="form-control form-control-sm">{{ old('address', $user->address) }}</textarea>
                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="role" class="form-label mb-0">
                                    Role
                                </label>
                            </div>

                            <div class="col-8">

                                <select id="role"
                                        name="role"
                                        class="form-select form-select-sm"
                                        required>

                                    <option value="warga"
                                        {{ old('role', $user->role) == 'warga' ? 'selected' : '' }}>
                                        Warga
                                    </option>

                                    <option value="mitra"
                                        {{ old('role', $user->role) == 'mitra' ? 'selected' : '' }}>
                                        Mitra
                                    </option>

                                    <option value="admin"
                                        {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="status" class="form-label mb-0">
                                    Status
                                </label>
                            </div>

                            <div class="col-8">

                                <select id="status"
                                        name="status"
                                        class="form-select form-select-sm"
                                        required>

                                    <option value="active"
                                        {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="pending"
                                        {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="rejected"
                                        {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>

                                    <option value="inactive"
                                        {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label class="form-label mb-0">
                                    XP
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="{{ $user->xp ?? 0 }}"
                                       disabled>
                            </div>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label class="form-label mb-0">
                                    Point
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="text"
                                       class="form-control form-control-sm"
                                       value="{{ $user->points ?? 0 }}"
                                       disabled>
                            </div>

                        </div>

                        <div class="row align-items-center mb-4">

                            <div class="col-4 text-end">
                                <label for="photo" class="form-label mb-0">
                                    Foto
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="file"
                                       id="photo"
                                       name="photo"
                                       class="form-control form-control-sm"
                                       accept="image/*">
                            </div>

                        </div>

                        <div class="border-top pt-3 mt-3 mb-3">

                            <small class="text-muted">
                                Ubah password (kosongkan jika tidak ingin mengubah)
                            </small>

                        </div>

                        <div class="row align-items-center mb-3">

                            <div class="col-4 text-end">
                                <label for="password"
                                       class="form-label mb-0">
                                    Password
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control form-control-sm"
                                       placeholder="Password baru">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row align-items-center mb-4">

                            <div class="col-4 text-end">
                                <label for="password_confirmation"
                                       class="form-label mb-0">
                                    Konfirmasi
                                </label>
                            </div>

                            <div class="col-8">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control form-control-sm"
                                       placeholder="Ulangi password">
                            </div>

                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-4">

                            <button type="submit"
                                    class="btn btn-primary px-4">
                                Simpan
                            </button>

                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-light border px-4">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection