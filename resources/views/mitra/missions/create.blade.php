@extends('template.layout')

@section('title', 'Tambah Mission - WasteLyn')

@section('content')
    <div class="page-heading">

        <div class="page-title">
            <h3>Tambah Mission</h3>
            <p class="text-subtitle text-muted">Tambahkan mission baru</p>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-body">

                    <form action="{{ route('admin.missions.store') }}" method="POST">

                        @csrf

                        <div class="row">

                            {{-- Nama Mission --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nama Mission</label>

                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                        required>

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Target --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Target</label>

                                    <input type="number" name="target"
                                        class="form-control @error('target') is-invalid @enderror"
                                        value="{{ old('target') }}" min="1" required>

                                    @error('target')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Deskripsi</label>

                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror" rows="4"
                                        required>{{ old('description') }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Reward XP --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Reward XP</label>

                                    <input type="number" name="reward_xp"
                                        class="form-control @error('reward_xp') is-invalid @enderror"
                                        value="{{ old('reward_xp') }}" min="0" required>

                                    @error('reward_xp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Reward Poin --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Reward Poin</label>

                                    <input type="number" name="reward_points"
                                        class="form-control @error('reward_points') is-invalid @enderror"
                                        value="{{ old('reward_points') }}" min="0" required>

                                    @error('reward_points')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Mulai</label>

                                    <input type="date" name="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date') }}" required>

                                    @error('start_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Berakhir --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Berakhir</label>

                                    <input type="date" name="end_date"
                                        class="form-control @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date') }}" required>

                                    @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Status</label>

                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        required>

                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            Aktif
                                        </option>

                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
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

                        </div>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Simpan Mission
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </section>

    </div>
@endsection