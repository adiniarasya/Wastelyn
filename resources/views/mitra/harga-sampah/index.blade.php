@extends('template.layout')

@section('title', 'Kelola Harga Sampah')

@section('content')
    <h3 class="mb-4"><i class="bi bi-cash-coin"></i> Kelola Harga Sampah</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Atur harga per kg untuk setiap jenis sampah di bank sampah kamu.
        Kalau tidak diatur, sistem akan pakai <strong>harga default dari admin</strong>.
    </div>

    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            Daftar Harga Sampah
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th>Harga Default (Admin)</th>
                            <th>Harga Kamu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $cat)
                            @php
                                $override = $prices->get($cat->category_id);
                                $hargaKamu = $override->price_per_kg ?? null;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $cat->name }}</strong>
                                    @if($cat->description)
                                        <br><small class="text-muted">{{ $cat->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">
                                        Rp {{ number_format($cat->price_per_kg, 0, ',', '.') }}/kg
                                    </span>
                                </td>
                                <td>
                                    @if($hargaKamu)
                                        <span class="badge bg-success">
                                            Rp {{ number_format($hargaKamu, 0, ',', '.') }}/kg
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Default</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $cat->category_id }}">
                                        {{ $hargaKamu ? 'Edit' : 'Atur' }}
                                    </button>

                                    @if($hargaKamu)
                                        <form action="{{ route('mitra.harga-sampah.destroy', $cat->category_id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus harga override? Kembali ke default admin.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Reset</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                            <h5 class="modal-title">Atur Harga — {{ $cat->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Harga Default Admin</label>
                                <input type="text" class="form-control"
                                       value="Rp {{ number_format($cat->price_per_kg, 0, ',', '.') }}/kg"
                                       disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Kamu (Rp/kg)</label>
                                <input type="number" name="price_per_kg" class="form-control"
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection