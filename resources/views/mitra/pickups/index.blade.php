@extends('template.layout')

@section('title', 'Kelola Setoran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-truck"></i> Kelola Setoran</h3>
        <a href="{{ route('mitra.pickup-requests.export-pdf', request()->query()) }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-pdf"></i> Export Data
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Panel Filter --}}
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-funnel"></i> Filter Data
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control"
                           value="{{ $filterTanggalDari }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control"
                           value="{{ $filterTanggalSampai }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis Sampah</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->category_id }}"
                                @selected($filterKategori == $kat->category_id)>
                                {{ $kat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Metode</label>
                    <select name="metode" class="form-select">
                        <option value="">Semua</option>
                        <option value="pickup" @selected($filterMetode === 'pickup')>Pickup</option>
                        <option value="dropoff" @selected($filterMetode === 'dropoff')>Dropoff</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-success flex-fill">
                        <i class="bi bi-search"></i> Terapkan
                    </button>
                    <a href="{{ route('mitra.pickup-requests.index') }}"
                       class="btn btn-outline-secondary" title="Reset">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Permintaan Tersedia -->
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            Permintaan Tersedia <span class="badge bg-warning text-dark">{{ $available->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warga</th>
                            <th>Alamat</th>
                            <th>Jadwal</th>
                            <th>Metode</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($available as $pickup)
                            <tr>
                                <td>{{ $pickup->user->name ?? '-' }}</td>
                                <td>{{ $pickup->address ?? $pickup->alamat ?? '-' }}</td>
                                <td>
                                    {{ optional($pickup->pickup_date)->format('d M Y') }}
                                    {{ $pickup->pickup_time }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $pickup->pickup_method ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}"
                                       class="btn btn-sm btn-outline-secondary">Detail</a>
                                    <form action="{{ route('mitra.pickup-requests.take', $pickup->pickup_request_id) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Ambil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada permintaan tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $available->links() }}</div>
        </div>
    </div>

    <!-- Sedang Diproses -->
    <div class="card card-stat mb-4">
        <div class="card-header bg-white fw-semibold">
            Sedang Diproses <span class="badge bg-primary">{{ $mine->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warga</th>
                            <th>Metode</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mine as $pickup)
                            <tr>
                                <td>{{ $pickup->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $pickup->pickup_method ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ optional($pickup->pickup_date)->format('d M Y') }}
                                    {{ $pickup->pickup_time }}
                                </td>
                                <td>
                                    <span class="badge {{ $pickup->status_badge }} text-capitalize">
                                        {{ $pickup->status_label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}"
                                       class="btn btn-sm btn-outline-success">Proses / Verifikasi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada setoran diproses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $mine->links() }}</div>
        </div>
    </div>

    <!-- Ditolak -->
    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            Ditolak <span class="badge bg-danger">{{ $rejected->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warga</th>
                            <th>Kategori</th>
                            <th>Berat</th>
                            <th>Alasan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rejected as $pickup)
                            <tr>
                                <td>{{ $pickup->user->name ?? '-' }}</td>
                                <td>{{ $pickup->wasteCategory->name ?? '-' }}</td>
                                <td>{{ $pickup->weight_kg ?? $pickup->estimasi_berat ?? 0 }} kg</td>
                                <td><small class="text-muted">{{ $pickup->rejection_reason ?? '-' }}</small></td>
                                <td><small>{{ $pickup->updated_at->format('d M Y H:i') }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada setoran ditolak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $rejected->links() }}</div>
        </div>
    </div>
@endsection