@extends('template.layout')

@section('title', 'Kelola Mission - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Mission</h4>
            <p class="text-muted small mb-0">Kelola semua mission untuk warga WasteLyn</p>
        </div>
        <a href="{{ route('mitra.missions.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Mission
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Daftar Mission</h6>
            <span class="badge bg-light text-dark border">{{ $missions->count() }} Mission</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Mission</th>
                        <th>Bank Sampah</th>
                        <th>Target</th>
                        <th>Tipe</th>
                        <th>Reward</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missions as $mission)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $mission->title }}</div>
                                <div class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($mission->description, 40) }}
                                </div>
                            </td>

                            <td>
                                <span class="small">{{ $mission->bank->name ?? '-' }}</span>
                            </td>

                            <td>
                                <span class="small">{{ $mission->target }} {{ $mission->unit ?? 'item' }}</span>
                            </td>

                            <td>
                                @if($mission->type == 'quantitative')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Hitung</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Karya</span>
                                @endif
                            </td>

                            <td>
                                <div class="small">
                                    <span class="fw-semibold">{{ $mission->reward_xp }} XP</span>
                                    <span class="text-muted"> / </span>
                                    <span>{{ number_format($mission->reward_points, 0, ',', '.') }} Poin</span>
                                </div>
                            </td>

                            <td>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($mission->start_date)->format('d/m/Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($mission->end_date)->format('d/m/Y') }}
                                </div>
                            </td>

                            <td>
                                @if($mission->status == 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Nonaktif</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('mitra.missions.show', $mission->mission_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mitra.missions.edit', $mission->mission_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('mitra.missions.destroy', $mission->mission_id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus mission ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                Belum ada mission.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection