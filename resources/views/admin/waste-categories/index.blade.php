@extends('template.layout')

@section('title', 'Jenis Sampah - WasteLyn')

@push('styles')
    <style>
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 1;
        }

        .btn-icon svg {
            display: block;
            pointer-events: none;
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .category-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
            display: block;
        }

        .category-icon-empty {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #f1f3f5;
            color: #adb5bd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .table-clean thead th {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6c757d;
            background: #f8f9fa;
            padding: 12px 14px;
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .table-clean tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table-clean tbody tr:last-child td {
            border-bottom: 0;
        }

        .price-text {
            font-weight: 600;
            color: #2E7D32;
            white-space: nowrap;
        }

        .metric-text {
            font-family: monospace;
            font-size: 13px;
            color: #495057;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Jenis Sampah</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Kelola kategori jenis sampah WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Daftar Jenis Sampah</h5>

                    <a href="{{ route('admin.waste-categories.create') }}"
                        class="btn btn-sm btn-success d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Tambah Jenis Sampah
                    </a>
                </div>

                <div class="card-body pt-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2"
                            role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            {{ session('success') }}
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2"
                            role="alert">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            {{ session('error') }}
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-clean table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:60px;">ID</th>
                                    <th style="width:80px;">Foto</th>
                                    <th>Nama</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Harga/kg</th>
                                    <th class="text-center">Reward/kg</th>
                                    <th class="text-center">Poin/kg</th>
                                    <th class="text-center">CO₂/kg</th>
                                    <th class="text-center" style="width:140px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($wasteCategories as $c)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold">
                                            #{{ $c->category_id }}
                                        </td>

                                        <td>
                                            @if($c->icon && \Illuminate\Support\Facades\Storage::disk('public')->exists($c->icon))
                                                <img src="{{ asset('storage/' . $c->icon) }}" alt="{{ $c->name }}"
                                                    class="category-icon">
                                            @else
                                                <div class="category-icon-empty">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="23 4 23 10 17 10" />
                                                        <polyline points="1 20 1 14 7 14" />
                                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                                        <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">{{ $c->name }}</span>
                                        </td>

                                        <td>
                                            <span class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit($c->description, 60) ?: '-' }}
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <span class="price-text">
                                                Rp {{ number_format($c->price_per_kg, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center metric-text">
                                            {{ $c->reward_per_kg ?? '-' }}
                                        </td>

                                        <td class="text-center metric-text">
                                            {{ $c->point_per_kg ?? '-' }}
                                        </td>

                                        <td class="text-center metric-text">
                                            {{ $c->co2_saved_per_kg ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            <div class="action-group justify-content-center">
                                                <a href="{{ route('admin.waste-categories.show', $c->category_id) }}"
                                                    class="btn btn-sm btn-outline-info btn-icon" title="Lihat Detail">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                </a>

                                                <a href="{{ route('admin.waste-categories.edit', $c->category_id) }}"
                                                    class="btn btn-sm btn-outline-warning btn-icon" title="Edit">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                </a>

                                                <form action="{{ route('admin.waste-categories.destroy', $c->category_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus jenis sampah ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-icon"
                                                        title="Hapus">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6" />
                                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                            <path d="M10 11v6" />
                                                            <path d="M14 11v6" />
                                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <polyline points="23 4 23 10 17 10" />
                                                <polyline points="1 20 1 14 7 14" />
                                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10" />
                                                <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14" />
                                            </svg>
                                            <div class="mt-2">
                                                Belum ada data jenis sampah.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection