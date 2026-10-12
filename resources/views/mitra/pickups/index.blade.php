@extends('template.layout')

@section('title', 'Kelola Setoran - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Kelola Setoran</h4>
            <p class="text-muted mb-0 small">Daftar setoran sampah yang masuk dari warga</p>
        </div>
        <a href="{{ route('mitra.pickup-requests.export-pdf', request()->query()) }}" class="btn btn-success">
            <i class="bi bi-download me-1"></i> Export PDF
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Panel Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm" value="{{ $filterTanggalDari }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm" value="{{ $filterTanggalSampai }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Jenis Sampah</label>
                    <select name="kategori_id" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->category_id }}" @selected($filterKategori == $kat->category_id)>
                                {{ $kat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Metode</label>
                    <select name="metode" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="pickup" @selected($filterMetode === 'pickup')>Pickup</option>
                        <option value="dropoff" @selected($filterMetode === 'dropoff')>Dropoff</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm flex-fill">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                    <a href="{{ route('mitra.pickup-requests.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Setoran --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Setoran Masuk</h6>
            <span class="badge bg-light text-dark border">{{ $mine->total() }} Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Warga</th>
                            <th>Metode</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mine as $pickup)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $pickup->user->name ?? '-' }}</div>
                                    <div class="small text-muted">{{ $pickup->user->email ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="text-capitalize small">{{ $pickup->pickup_method ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="small">{{ optional($pickup->pickup_date)->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $pickup->pickup_time }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $pickup->status_badge }} fw-normal">
                                        {{ $pickup->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}"
                                       class="btn btn-sm btn-outline-success">
                                        Proses <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <p class="mb-0">Belum ada setoran masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($mine->hasPages())
                <div class="p-3 border-top">
                    {{ $mine->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection