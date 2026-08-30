@extends('template.layout')

@section('title', 'Tambah Reward - WasteLyn')

@section('content')
    <div class="page-heading">

        <div class="page-title">
            <h3>Tambah Reward</h3>
            <p class="text-subtitle text-muted">Tambahkan reward baru</p>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-body">

                    <form action="{{ route('admin.rewards.store') }}" method="POST">

                        @csrf

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nama Reward</label>

                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required>

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Poin Dibutuhkan</label>

                                    <input type="number" name="point_required"
                                        class="form-control @error('point_required') is-invalid @enderror"
                                        value="{{ old('point_required') }}" min="1" required>

                                    @error('point_required')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Deskripsi</label>

                                    <textarea name="description" class="form-control"
                                        rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Stok</label>

                                    <input type="number" name="stock" class="form-control" value="{{ old('stock') }}"
                                        min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>URL Gambar</label>

                                    <input type="text" name="image" class="form-control" value="{{ old('image') }}"
                                        placeholder="Contoh: gambar/reward.jpg">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Status</label>

                                    <select name="status" class="form-select" required>

                                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>
                                            Tersedia
                                        </option>

                                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>
                                            Tidak Tersedia
                                        </option>

                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Simpan Reward
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </section>

    </div>
@endsection