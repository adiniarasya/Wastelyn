@extends('template.layout')

@section('title', 'Profil Mitra - WasteLyn')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Profil Mitra</h4>
        <p class="text-muted small mb-0">Kelola informasi akun mitra kamu</p>
    </div>

    <form action="{{ route('mitra.profile.update') }}" method="POST" style="max-width: 640px;">
        @csrf
        @method('PUT')

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informasi Pribadi</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               value="{{ old('name', $mitra->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                               value="{{ old('email', $mitra->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">No. Telepon</label>
                        <input type="text" name="phone"
                               class="form-control form-control-sm @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $mitra->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small text-muted">Alamat</label>
                        <textarea name="address"
                                  class="form-control form-control-sm @error('address') is-invalid @enderror"
                                  rows="3">{{ old('address', $mitra->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
@endsection