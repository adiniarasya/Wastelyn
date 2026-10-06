@extends('template.layout')

@section('title', 'Kelola User - WasteLyn')

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

    .badge-icon {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-icon svg {
        display: block;
    }

    .avatar-cell {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }
    .avatar-initial {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #435EBE;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 15px;
    }

    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .users-table thead th {
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

    .users-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        font-size: 14px;
    }

    .users-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .search-input {
        height: 40px;
        border-radius: 8px;
    }

    .search-btn {
        height: 40px;
        width: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .search-btn svg {
        display: block;
    }
</style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold mb-1">Kelola User</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Daftar semua pengguna WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <h5 class="card-title mb-0">Daftar User</h5>

                    <a href="{{ route('admin.users.export.pdf', request()->query()) }}"
                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <polyline points="9 15 12 12 15 15"/>
                        </svg>
                        Export PDF
                    </a>
                </div>

                <div class="card-body pt-3">
                    <div class="row mb-3">
                        <div class="col-12 col-md-5">
                            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Cari nama, email, atau role..."
                                    value="{{ request('search') }}">

                                <button type="submit" class="btn btn-primary search-btn" title="Cari">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                </button>

                                @if(request('search'))
                                    <a href="{{ route('admin.users.index') }}"
                                        class="btn btn-outline-secondary search-btn" title="Reset">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table users-table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-center">XP</th>
                                    <th class="text-center">Point</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width:180px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($users as $item)
                                    <tr>
                                        <td>
                                            @if(
                                                $item->photo &&
                                                \Illuminate\Support\Facades\Storage::disk('public')->exists($item->photo)
                                            )
                                                <img src="{{ asset('storage/' . $item->photo) }}"
                                                    alt="{{ $item->name }}"
                                                    class="avatar-cell">
                                            @else
                                                <div class="avatar-initial">
                                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">{{ $item->name }}</span>
                                        </td>

                                        <td>
                                            <span class="text-muted">{{ $item->email }}</span>
                                        </td>

                                        <td>
                                            @if($item->role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif($item->role === 'mitra')
                                                <span class="badge bg-success">Mitra</span>
                                            @else
                                                <span class="badge bg-primary">Warga</span>
                                            @endif
                                        </td>

                                        <td class="text-center">{{ number_format($item->xp ?? 0, 0, ',', '.') }}</td>

                                        <td class="text-center">{{ number_format($item->points ?? 0, 0, ',', '.') }}</td>

                                        <td>
                                            @if($item->status === 'active')
                                                <span class="badge bg-success badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @elseif($item->status === 'pending')
                                                <span class="badge bg-warning text-dark badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"/>
                                                        <polyline points="12 6 12 12 16 14"/>
                                                    </svg>
                                                    Pending
                                                </span>
                                            @elseif($item->status === 'rejected')
                                                <span class="badge bg-danger badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                                    </svg>
                                                    Ditolak
                                                </span>
                                            @elseif($item->status === 'inactive')
                                                <span class="badge bg-secondary badge-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                                    </svg>
                                                    Nonaktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Tidak diketahui</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="action-group justify-content-center">
                                                @if($item->role === 'mitra' && $item->status === 'pending')
                                                    <form action="{{ route('admin.users.approve', $item->user_id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success btn-icon" title="Setujui Mitra">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.users.reject', $item->user_id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menolak mitra ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Tolak Mitra">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <line x1="18" y1="6" x2="6" y2="18"/>
                                                                <line x1="6" y1="6" x2="18" y2="18"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('admin.users.show', $item->user_id) }}"
                                                    class="btn btn-sm btn-outline-info btn-icon" title="Lihat Detail">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                </a>

                                                <a href="{{ route('admin.users.edit', $item->user_id) }}"
                                                    class="btn btn-sm btn-outline-warning btn-icon" title="Edit User">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                </a>

                                                @if($item->user_id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $item->user_id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Hapus User">
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"/>
                                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                                <path d="M10 11v6"/>
                                                                <path d="M14 11v6"/>
                                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="mb-3"
                                                style="opacity:.4;display:block;margin:0 auto;">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                            <div class="mt-2">
                                                @if(request('search'))
                                                    Data user tidak ditemukan.
                                                @else
                                                    Belum ada data user.
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="d-flex justify-content-end mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection