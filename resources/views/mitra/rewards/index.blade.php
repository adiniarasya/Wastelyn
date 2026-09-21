@extends('template.layout')

@section('title', 'Kelola Reward - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <h3>Kelola Reward</h3>
            <p class="text-subtitle text-muted">Kelola reward untuk warga</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Reward</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mitra.rewards.redemptions') }}" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-left-right"></i> Penukaran Reward
                        </a>
                        <a href="{{ route('mitra.rewards.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Reward
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Reward</th>
                                    <th>Deskripsi</th>
                                    <th>Poin Dibutuhkan</th>
                                    <th>Stok</th>
                                    <th>Gambar</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rewards as $reward)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $reward->name }}</strong></td>
                                        <td>{{ $reward->description ?? '-' }}</td>
                                        <td>{{ number_format($reward->point_required, 0, ',', '.') }}</td>
                                        <td>{{ $reward->stock }}</td>
                                        <td>
                                            @if($reward->image)
                                                <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}" width="50" height="50"
                                                    style="object-fit: cover; border-radius: 8px;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($reward->status == 'available')
                                                <span class="badge bg-success">Tersedia</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('mitra.rewards.show', $reward->reward_id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('mitra.rewards.edit', $reward->reward_id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('mitra.rewards.destroy', $reward->reward_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus reward ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            Belum ada reward
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