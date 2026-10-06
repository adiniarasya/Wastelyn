@extends('template.layout')

@section('title', 'Detail Jenis Sampah - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Detail Jenis Sampah</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Informasi lengkap jenis sampah
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Informasi Jenis Sampah</h5>

                    <a href="{{ route('admin.waste-categories.index') }}"
                        class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                            <line x1="19" y1="12" x2="5" y2="12" />
                            <polyline points="12 19 5 12 12 5" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="card-body pt-4">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10">

                            {{-- Foto & Nama --}}
                            <div class="text-center mb-4">
                                @if($wasteCategory->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($wasteCategory->icon))
                                    <img src="{{ asset('storage/' . $wasteCategory->icon) }}" alt="{{ $wasteCategory->name }}"
                                        class="rounded-4"
                                        style="width:120px;height:120px;object-fit:cover;border:3px solid #f1f3f5;">
                                @else
                                    <div class="rounded-4 d-inline-flex justify-content-center align-items-center"
                                        style="width:120px;height:120px;background:#E6F7EE;color:#2E7D32;border:3px solid #f1f3f5;">
                                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="23 4 23 10 17 10" />
                                            <polyline points="1 20 1 14 7 14" />
                                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                            <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                        </svg>
                                    </div>
                                @endif

                                <h5 class="fw-bold mt-3 mb-1">{{ $wasteCategory->name }}</h5>
                                <p class="text-muted small mb-0">
                                    ID Kategori: <strong>#{{ $wasteCategory->category_id }}</strong>
                                </p>
                            </div>

                            {{-- Deskripsi --}}
                            @if($wasteCategory->description)
                                <div class="alert alert-light border small text-muted mb-4">
                                    {{ $wasteCategory->description }}
                                </div>
                            @endif

                            {{-- Statistik Harga/Reward/Poin --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 rounded-3" style="background:#E6F7EE;">
                                        <div class="fw-bold fs-6" style="color:#2E7D32;">
                                            Rp {{ number_format($wasteCategory->price_per_kg, 0, ',', '.') }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">
                                            Harga / kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 rounded-3" style="background:#E7F1FF;">
                                        <div class="fw-bold fs-5" style="color:#435EBE;">
                                            {{ $wasteCategory->reward_per_kg ?? 0 }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">
                                            Reward / kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 rounded-3" style="background:#FFF4E0;">
                                        <div class="fw-bold fs-5" style="color:#D97706;">
                                            {{ $wasteCategory->point_per_kg ?? 0 }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">
                                            Poin / kg
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-3 rounded-3" style="background:#E0F5FA;">
                                        <div class="fw-bold fs-5" style="color:#0891B2;">
                                            {{ $wasteCategory->co2_saved_per_kg ?? 0 }}
                                        </div>
                                        <div class="text-uppercase small fw-semibold text-muted mt-1">
                                            CO₂ / kg
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Info --}}
                            <div class="border-top pt-3">
                                <h6 class="fw-bold mb-3">Informasi Tambahan</h6>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                        Dibuat
                                    </div>
                                    <div class="col-8 col-md-9">
                                        {{ $wasteCategory->created_at?->format('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 text-md-end text-muted small fw-semibold">
                                        Terakhir Update
                                    </div>
                                    <div class="col-8 col-md-9">
                                        {{ $wasteCategory->updated_at?->format('d F Y, H:i') ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.waste-categories.index') }}" class="btn btn-light border px-4">
                                    Kembali
                                </a>
                                <a href="{{ route('admin.waste-categories.edit', $wasteCategory->category_id) }}"
                                    class="btn btn-success px-4 d-inline-flex align-items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Edit Jenis Sampah
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection