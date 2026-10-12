@extends('template.layout')

@section('title', 'Kelola Harga Sampah - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Harga Sampah</h4>
            <p class="text-muted small mb-0">Atur harga per kg untuk setiap jenis sampah di bank sampah kamu.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info d-flex align-items-start small py-2 mb-3" role="alert">
        <i class="bi bi-info-circle me-2 mt-1"></i>
        <div>
            Kalau harga tidak diatur, sistem akan pakai <strong>harga default dari admin</strong>.
            Kamu bisa atur harga khusus untuk bank sampahmu.
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Daftar Harga Sampah</h6>
            <span class="badge bg-light text-dark border">{{ $categories->count() }} Kategori</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kategori</th>
                        <th>Harga Default (Admin)</th>
                        <th>Harga Kamu</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        @php
                            $override = $prices->get($cat->category_id);
                            $hargaKamu = $override->price_per_kg ?? null;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $cat->name }}</div>
                                @if($cat->description)
                                    <div class="small text-muted">{{ $cat->description }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">
                                    Rp {{ number_format($cat->price_per_kg, 0, ',', '.') }} / kg
                                </span>
                            </td>
                            <td>
                                @if($hargaKamu)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Rp {{ number_format($hargaKamu, 0, ',', '.') }} / kg
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        Default
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $cat->category_id }}">
                                    <i class="bi bi-pencil me-1"></i>
                                    {{ $hargaKamu ? 'Edit' : 'Atur' }}
                                </button>

                                @if($hargaKamu)
                                    <form action="{{ route('mitra.harga-sampah.destroy', $cat->category_id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus harga override? Kembali ke default admin.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Edit --}}
    @foreach ($categories as $cat)
        @php
            $override = $prices->get($cat->category_id);
            $hargaKamu = $override->price_per_kg ?? '';
        @endphp
        <div class="modal fade" id="editModal{{ $cat->category_id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('mitra.harga-sampah.update', $cat->category_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h6 class="modal-title fw-bold">Atur Harga — {{ $cat->name }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Harga Default Admin</label>
                                <input type="text" class="form-control form-control-sm"
                                       value="Rp {{ number_format($cat->price_per_kg, 0, ',', '.') }} / kg"
                                       disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Harga Kamu (Rp/kg)</label>
                                <input type="number" name="price_per_kg" class="form-control form-control-sm"
                                       min="0" step="100"
                                       value="{{ $hargaKamu }}"
                                       placeholder="Misal: 3500"
                                       required>
                                <small class="text-muted">
                                    Kosongkan & klik "Reset" kalau mau pakai harga default admin.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection