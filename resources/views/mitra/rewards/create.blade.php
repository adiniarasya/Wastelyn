@extends('template.layout')

@section('title', 'Tambah Reward - WasteLyn')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Reward</h4>
        <p class="text-muted small mb-0">Tambahkan reward baru untuk warga</p>
    </div>

    <form action="{{ route('mitra.rewards.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                <h6 class="fw-bold mb-3">Informasi Reward</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama Reward <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Poin Dibutuhkan <span class="text-danger">*</span></label>
                        <input type="number" name="point_required"
                               class="form-control form-control-sm @error('point_required') is-invalid @enderror"
                               value="{{ old('point_required') }}" min="1" required>
                        @error('point_required')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Tipe Reward <span class="text-danger">*</span></label>
                        <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror" required>
                            <option value="instant" {{ old('type', 'instant') == 'instant' ? 'selected' : '' }}>
                                Instant (Voucher Digital / Kode)
                            </option>
                            <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>
                                Manual (Pulsa / E-Wallet)
                            </option>
                            <option value="pickup" {{ old('type') == 'pickup' ? 'selected' : '' }}>
                                Pickup (Ambil Sendiri di Bank Sampah)
                            </option>
                        </select>
                        <small class="text-muted">
                            Instant: langsung dapat kode voucher. Manual: input nomor pulsa. Pickup: ambil sendiri di bank sampah.
                        </small>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small text-muted">Deskripsi</label>
                        <textarea name="description"
                                  class="form-control form-control-sm @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Stok & Gambar</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock"
                               class="form-control form-control-sm @error('stock') is-invalid @enderror"
                               value="{{ old('stock') }}" min="0" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Upload Gambar</label>
                        <input type="file" name="image"
                               class="form-control form-control-sm @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, GIF. Maks 2MB.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Status</h6>

                <label class="form-label small text-muted">Status Reward <span class="text-danger">*</span></label>
                <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required>
                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('mitra.rewards.index') }}"
               class="btn btn-outline-secondary btn-sm"
               onclick="return confirm('Yakin batal? Semua yang sudah kamu isi akan hilang.')">
                Batal
            </a>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-lg me-1"></i> Simpan Reward
            </button>
        </div>

    </form>
@endsection