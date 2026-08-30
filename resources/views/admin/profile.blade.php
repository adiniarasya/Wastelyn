@extends('template.layout')

@section('title', 'Profil Admin - WasteLyn')

@section('content')

    <div class="page-heading">

        <div class="page-title">
            <h3>Profil Admin</h3>
            <p class="text-subtitle text-muted">
                Kelola informasi profil akun kamu
            </p>
        </div>

        <section class="section">

            <div class="card">

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- Nama --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Nama
                                    </label>

                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" required>

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
                                    </label>

                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" required>

                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- Nomor HP --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Nomor HP
                                    </label>

                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}">

                                </div>

                            </div>


                            {{-- Role --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Role
                                    </label>

                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>

                                </div>

                            </div>


                            {{-- Alamat --}}
                            <div class="col-12">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Alamat
                                    </label>

                                    <textarea name="address" class="form-control"
                                        rows="3">{{ old('address', $user->address) }}</textarea>

                                </div>

                            </div>


                            {{-- Password --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Password Baru
                                    </label>

                                    <input type="password" name="password" class="form-control">

                                    <small class="text-muted">
                                        Kosongkan jika tidak ingin mengubah password.
                                    </small>

                                </div>

                            </div>


                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">

                                    <label class="form-label">
                                        Konfirmasi Password
                                    </label>

                                    <input type="password" name="password_confirmation" class="form-control">

                                </div>

                            </div>

                        </div>


                        <div class="d-flex justify-content-end">

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-save"></i>
                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </section>

    </div>

@endsection