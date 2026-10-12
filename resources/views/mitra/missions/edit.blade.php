@extends('template.layout')

@section('title', 'Edit Mission - WasteLyn')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Mission</h4>
        <p class="text-muted small mb-0">Perbarui data mission</p>
    </div>

    <form action="{{ route('mitra.missions.update', $mission->mission_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- SECTION 1: Info Mission --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informasi Mission</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Bank Sampah <span class="text-danger">*</span></label>
                        <select name="bank_id" class="form-select form-select-sm @error('bank_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($wasteBanks as $bank)
                                <option value="{{ $bank->bank_id }}"
                                    {{ old('bank_id', $mission->bank_id) == $bank->bank_id ? 'selected' : '' }}>
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Nama Mission <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control form-control-sm @error('title') is-invalid @enderror"
                               value="{{ old('title', $mission->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small text-muted">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description"
                                  class="form-control form-control-sm @error('description') is-invalid @enderror"
                                  rows="3" required>{{ old('description', $mission->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 1.5: Gambar Mission --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Gambar Mission</h6>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Ganti Gambar</label>
                        <input type="file" name="image" id="imageInput"
                               class="form-control form-control-sm @error('image') is-invalid @enderror"
                               accept="image/*">
                        <small class="text-muted">Kosongkan kalau gak mau ganti. Format: JPG, PNG, WEBP. Maks 2MB.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted d-block">Preview</label>

                        {{-- Preview gambar lama --}}
                        <img id="imagePreview"
                             src="{{ $mission->image ? asset('storage/' . $mission->image) : '#' }}"
                             alt="Preview"
                             class="rounded border {{ $mission->image ? '' : 'd-none' }}"
                             style="width:100%; max-height:120px; object-fit:cover;">

                        {{-- Placeholder kalau belum ada gambar --}}
                        <div id="imagePlaceholder"
                             class="rounded border bg-light d-flex align-items-center justify-content-center {{ $mission->image ? 'd-none' : '' }}"
                             style="height:120px; width:100%;">
                            <i class="bi bi-image text-muted fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: Target & Tipe --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Target & Tipe</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Target <span class="text-danger">*</span></label>
                        <input type="number" name="target"
                               class="form-control form-control-sm @error('target') is-invalid @enderror"
                               value="{{ old('target', $mission->target) }}" min="1" required>
                        @error('target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit"
                               class="form-control form-control-sm @error('unit') is-invalid @enderror"
                               value="{{ old('unit', $mission->unit) }}"
                               placeholder="botol / kg / karya" required>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tipe Misi <span class="text-danger">*</span></label>
                        <select name="type" class="form-select form-select-sm @error('type') is-invalid @enderror" required>
                            <option value="quantitative" {{ old('type', $mission->type) == 'quantitative' ? 'selected' : '' }}>
                                Kuantitatif (hitung jumlah)
                            </option>
                            <option value="qualitative" {{ old('type', $mission->type) == 'qualitative' ? 'selected' : '' }}>
                                Kualitatif (karya/hasil akhir)
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Validasi AI --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-1">Validasi AI</h6>
                <p class="text-muted small mb-3">
                    Instruksi untuk AI memvalidasi foto bukti dari warga. Harus minta AI jawab dalam format JSON dengan
                    field <code>valid</code>, <code>count</code>, <code>reason</code>.
                </p>

                <label class="form-label small text-muted">AI Prompt <span class="text-danger">*</span></label>

                {{-- Kotak contoh prompt yang bisa disalin --}}
                <div class="d-flex justify-content-between align-items-center bg-light border rounded p-2 mb-2 small">
                    <div class="text-truncate me-2" id="contohPrompt" style="font-family: monospace; font-size: 12px;">
                        Hitung jumlah botol plastik di gambar ini. Jawab JSON: {"valid": bool, "count": int, "reason": string}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary flex-shrink-0"
                            onclick="salinPrompt()" title="Salin ke textarea">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>

                <textarea name="ai_prompt" id="aiPromptInput"
                          class="form-control form-control-sm @error('ai_prompt') is-invalid @enderror"
                          rows="3" required>{{ old('ai_prompt', $mission->ai_prompt) }}</textarea>
                @error('ai_prompt')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- SECTION 4: Reward --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Reward</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Reward XP <span class="text-danger">*</span></label>
                        <input type="number" name="reward_xp"
                               class="form-control form-control-sm @error('reward_xp') is-invalid @enderror"
                               value="{{ old('reward_xp', $mission->reward_xp) }}" min="0" required>
                        @error('reward_xp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted">Reward Poin <span class="text-danger">*</span></label>
                        <input type="number" name="reward_points"
                               class="form-control form-control-sm @error('reward_points') is-invalid @enderror"
                               value="{{ old('reward_points', $mission->reward_points) }}" min="0" required>
                        @error('reward_points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: Periode & Status --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Periode & Status</h6>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date"
                               class="form-control form-control-sm @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', \Carbon\Carbon::parse($mission->start_date)->format('Y-m-d')) }}"
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Berakhir <span class="text-danger">*</span></label>
                        <input type="date" name="end_date"
                               class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', \Carbon\Carbon::parse($mission->end_date)->format('Y-m-d')) }}"
                               required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $mission->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $mission->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('mitra.missions.index') }}"
               class="btn btn-outline-secondary btn-sm"
               onclick="return confirm('Yakin batal? Perubahan yang belum disimpan akan hilang.')">
                Batal
            </a>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
            </button>
        </div>

    </form>
@endsection

@push('scripts')
<script>
    function salinPrompt() {
        const contoh = document.getElementById('contohPrompt').innerText.trim();
        const textarea = document.getElementById('aiPromptInput');
        textarea.value = contoh;
        textarea.focus();

        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 1500);
    }

    // Preview gambar sebelum upload
    document.getElementById('imageInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush