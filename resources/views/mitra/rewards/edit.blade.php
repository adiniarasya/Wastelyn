@extends('template.layout')

@section('title', 'Edit Reward - WasteLyn')

@section('content')
    <div class="page-heading">

        <div class="page-title">
            <h3>Edit Reward</h3>
            <p class="text-subtitle text-muted">Ubah informasi reward</p>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-body">

                    <form action="{{ route('admin.rewards.update', $reward->reward_id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nama Reward</label>

                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $reward->name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Poin Dibutuhkan</label>

                                    <input type="number" name="point_required" class="form-control"
                                        value="{{ old('point_required', $reward->point_required) }}" min="1" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Deskripsi</label>

                                    <textarea name="description" class="form-control"
                                        rows="4">{{ old('description', $reward->description) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Stok</label>

                                    <input type="number" name="stock" class="form-control"
                                        value="{{ old('stock', $reward->stock) }}" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>URL Gambar</label>

                                    <input type="text" name="image" class="form-control"
                                        value="{{ old('image', $reward->image) }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Status</label>

                                    <select name="status" class="form-select" required>

                                        <option value="available" {{ old('status', $reward->status) == 'available' ? 'selected' : '' }}>
                                            Tersedia
                                        </option>

                                        <option value="unavailable" {{ old('status', $reward->status) == 'unavailable' ? 'selected' : '' }}>
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
                                Update Reward
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </section>

    </div>
@endsection