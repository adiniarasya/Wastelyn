@extends('template.layout')

@section('title', 'Kelola Reward - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Reward</h4>
            <p class="text-muted small mb-0">Kelola reward untuk warga</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('mitra.rewards.redemptions') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-right me-1"></i> Penukaran Reward
            </a>
            <a href="{{ route('mitra.rewards.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Reward
            </a>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Daftar Reward</h6>
            <span class="badge bg-light text-dark border">{{ $rewards->count() }} Reward</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Reward</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Poin</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $reward)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $reward->name }}</div>
                            </td>
                            <td>
                                <span class="small text-muted">
                                    {{ \Illuminate\Support\Str::limit($reward->description, 50) ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if(($reward->type ?? 'instant') === 'manual')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Manual</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Instant</span>
                                @endif
                            </td>
                            <td>
                                <span class="small fw-semibold">
                                    {{ number_format($reward->point_required, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="small">{{ $reward->stock }}</span>
                            </td>
                            <td>
                                @if($reward->image)
                                    <img src="{{ asset('assets/images/' . $reward->image) }}"
                                         alt="{{ $reward->name }}"
                                         width="48" height="48"
                                         style="object-fit: cover; border-radius: 6px;">
                                @else
                                    <span class="small text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($reward->status == 'available')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('mitra.rewards.show', $reward->reward_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('mitra.rewards.edit', $reward->reward_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('mitra.rewards.destroy', $reward->reward_id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus reward ini?')">
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
                                Belum ada reward.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection