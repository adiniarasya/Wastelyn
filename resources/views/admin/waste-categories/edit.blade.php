@extends('template.layout')

@section('title', 'Edit Jenis Sampah - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Edit Jenis Sampah</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Perbarui informasi jenis sampah
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Form Edit Jenis Sampah</h5>

                    <a href="{{ route('admin.waste-categories.index') }}"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="card-body pt-4">
                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-start gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
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

                    <form action="{{ route('admin.waste-categories.update', $wasteCategory->category_id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-10">

                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold small text-muted">
                                        Nama <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $wasteCategory->name) }}"
                                        placeholder="Contoh: Plastik PET"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold small text-muted">
                                        Deskripsi
                                    </label>
                                    <textarea id="description" name="description" rows="3"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Deskripsi singkat tentang jenis sampah ini...">{{ old('description', $wasteCategory->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Harga, Reward, Poin --}}
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="price_per_kg" class="form-label fw-semibold small text-muted">
                                            Harga per Kg <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" id="price_per_kg" name="price_per_kg"
                                                class="form-control @error('price_per_kg') is-invalid @enderror"
                                                value="{{ old('price_per_kg', $wasteCategory->price_per_kg) }}"
                                                step="0.01"
                                                min="0"
                                                required>
                                            @error('price_per_kg')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="reward_per_kg" class="form-label fw-semibold small text-muted">
                                            Reward per Kg
                                        </label>
                                        <input type="number" id="reward_per_kg" name="reward_per_kg"
                                            class="form-control @error('reward_per_kg') is-invalid @enderror"
                                            value="{{ old('reward_per_kg', $wasteCategory->reward_per_kg) }}"
                                            step="1"
                                            min="0">
                                        @error('reward_per_kg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="point_per_kg" class="form-label fw-semibold small text-muted">
                                            Poin per Kg <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" id="point_per_kg" name="point_per_kg"
                                            class="form-control @error('point_per_kg') is-invalid @enderror"
                                            value="{{ old('point_per_kg', $wasteCategory->point_per_kg) }}"
                                            step="1"
                                            min="0"
                                            required>
                                        @error('point_per_kg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- CO2 & Foto --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="co2_saved_per_kg" class="form-label fw-semibold small text-muted">
                                            CO₂ Saved per Kg
                                        </label>
                                        <input type="number" id="co2_saved_per_kg" name="co2_saved_per_kg"
                                            class="form-control @error('co2_saved_per_kg') is-invalid @enderror"
                                            value="{{ old('co2_saved_per_kg', $wasteCategory->co2_saved_per_kg) }}"
                                            step="0.001"
                                            min="0">
                                        @error('co2_saved_per_kg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="icon" class="form-label fw-semibold small text-muted">
                                            Foto / Icon
                                        </label>
                                        <input type="file" id="icon" name="icon"
                                            class="form-control @error('icon') is-invalid @enderror"
                                            accept="image/*">
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-1">
                                            Biarkan kosong jika tidak ingin mengganti foto.
                                            Format: jpg, jpeg, png, svg. Maks 2MB.
                                        </small>

                                        <div class="mt-2">
                                            @if($wasteCategory->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($wasteCategory->icon))
                                                <img id="preview"
                                                    src="{{ asset('storage/' . $wasteCategory->icon) }}"
                                                    alt="{{ $wasteCategory->name }}"
                                                    class="rounded-3"
                                                    style="width:90px;height:90px;object-fit:cover;border:1px solid #e9ecef;">
                                            @else
                                                <img id="preview" src="" alt="Preview"
                                                    class="d-none rounded-3"
                                                    style="width:90px;height:90px;object-fit:cover;border:1px solid #e9ecef;">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                    <a href="{{ route('admin.waste-categories.index') }}"
                                        class="btn btn-light border px-4">
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="btn btn-success px-4 d-inline-flex align-items-center gap-2">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                            <polyline points="17 21 17 13 7 13 7 21"/>
                                            <polyline points="7 3 7 8 15 8"/>
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

@push('scripts')
    <script>
        document.getElementById('icon')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const img = document.getElementById('preview');
            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
        });
    </script>
@endpush