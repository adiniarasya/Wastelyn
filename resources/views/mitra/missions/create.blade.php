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

                    <form action="{{ route('mitra.missions.store') }}" method="POST">
                        @csrf

                        <div class="row">

                            {{-- Bank Sampah --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Bank Sampah <span class="text-danger">*</span></label>
                                    <select name="bank_id" class="form-select @error('bank_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Cabang --</option>
                                        @foreach($wasteBanks as $bank)
                                            <option value="{{ $bank->bank_id }}" {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Nama Mission --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nama Mission <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Deskripsi <span class="text-danger">*</span></label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror" rows="4"
                                        required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Target --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Target <span class="text-danger">*</span></label>
                                    <input type="number" name="target"
                                        class="form-control @error('target') is-invalid @enderror"
                                        value="{{ old('target') }}" min="1" required>
                                    @error('target')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Unit --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Satuan (Unit) <span class="text-danger">*</span></label>
                                    <input type="text" name="unit"
                                        class="form-control @error('unit') is-invalid @enderror"
                                        value="{{ old('unit') }}"
                                        placeholder="botol / kg / karya" required>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Type --}}
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Tipe Misi <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="quantitative" {{ old('type') == 'quantitative' ? 'selected' : '' }}>
                                            Kuantitatif (hitung jumlah)
                                        </option>
                                        <option value="qualitative" {{ old('type') == 'qualitative' ? 'selected' : '' }}>
                                            Kualitatif (karya/hasil akhir)
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- AI Prompt --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>AI Prompt <span class="text-danger">*</span></label>
                                    <textarea name="ai_prompt"
                                        class="form-control @error('ai_prompt') is-invalid @enderror" rows="3"
                                        placeholder='Contoh: Hitung jumlah botol plastik di gambar ini. Jawab JSON: {"valid": bool, "count": int, "reason": string}'
                                        required>{{ old('ai_prompt') }}</textarea>
                                    <small class="text-muted">
                                        Instruksi buat AI buat validasi foto bukti dari warga.
                                        Harus minta AI jawab dalam format JSON dengan field <code>valid</code>, <code>count</code>, <code>reason</code>.
                                    </small>
                                    @error('ai_prompt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Reward XP --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Reward XP <span class="text-danger">*</span></label>
                                    <input type="number" name="reward_xp"
                                        class="form-control @error('reward_xp') is-invalid @enderror"
                                        value="{{ old('reward_xp') }}" min="0" required>
                                    @error('reward_xp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Reward Poin --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Reward Poin <span class="text-danger">*</span></label>
                                    <input type="number" name="reward_points"
                                        class="form-control @error('reward_points') is-invalid @enderror"
                                        value="{{ old('reward_points') }}" min="0" required>
                                    @error('reward_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Berakhir --}}
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Berakhir <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date"
                                        class="form-control @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('mitra.missions.index') }}" class="btn btn-secondary">
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