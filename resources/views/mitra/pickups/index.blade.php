@extends('template.layout')

@section('title', 'Kelola Setoran')

@section('content')
    <h3 class="mb-4"><i class="bi bi-truck"></i> Kelola Setoran</h3>

    <!-- Permintaan Tersedia (rebutan) -->
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
                                <td><span class="badge bg-secondary text-capitalize">{{ $pickup->pickup_method ?? '-' }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}" class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                    <form action="{{ route('mitra.pickup-requests.status', $pickup->pickup_request_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-sm btn-success">Ambil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada permintaan tersedia saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $available->links() }}</div>
        </div>
    </div>

    <!-- Permintaan yang Sudah Diambil -->
    <div class="card card-stat">
        <div class="card-header bg-white fw-semibold">
            Sedang Diproses <span class="badge bg-primary">{{ $mine->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Warga</th>
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
                                    {{ optional($pickup->pickup_date)->format('d M Y') }}
                                    {{ $pickup->pickup_time }}
                                </td>
                                <td><span class="badge bg-info text-dark text-capitalize">{{ $pickup->status }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('mitra.pickup-requests.show', $pickup->pickup_request_id) }}" class="btn btn-sm btn-outline-success">
                                        Proses / Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada setoran yang sedang diproses.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $mine->links() }}</div>
        </div>
    </div>
@endsection
