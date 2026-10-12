@extends('template.layout')

@section('title', 'Notifikasi - WasteLyn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                Notifikasi
                @if($unreadCount > 0)
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1">
                        {{ $unreadCount }} baru
                    </span>
                @endif
            </h4>
            <p class="text-muted small mb-0">Pemberitahuan aktivitas terbaru</p>
        </div>
        @if($unreadCount > 0)
            <form action="{{ route('mitra.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted">Tipe</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Tipe</option>
                    <option value="info" @selected(request('type') === 'info')>Info</option>
                    <option value="success" @selected(request('type') === 'success')>Sukses</option>
                    <option value="warning" @selected(request('type') === 'warning')>Peringatan</option>
                    <option value="danger" @selected(request('type') === 'danger')>Penting</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="unread" @selected(request('status') === 'unread')>Belum Dibaca</option>
                    <option value="read" @selected(request('status') === 'read')>Sudah Dibaca</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success btn-sm w-100">Filter</button>
            </div>
        </div>
    </form>

    {{-- Daftar Notifikasi --}}
    <div class="card border-0 shadow-sm">
        @forelse($notifications as $notif)
            @php
                $iconMap = [
                    'info' => 'bi-info-circle text-info',
                    'success' => 'bi-check-circle text-success',
                    'warning' => 'bi-exclamation-triangle text-warning',
                    'danger' => 'bi-x-circle text-danger',
                ];
                $icon = $iconMap[$notif->type] ?? 'bi-bell text-secondary';
            @endphp
            <div class="d-flex align-items-start p-3 border-bottom {{ $notif->is_read ? '' : 'bg-light' }}">
                <div class="me-3 mt-1">
                    <i class="bi {{ $icon }} fs-5"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="flex-grow-1">
                            <div class="mb-1 {{ $notif->is_read ? 'fw-normal' : 'fw-semibold' }}">
                                {{ $notif->title }}
                            </div>
                            <div class="small text-muted mb-1">{{ $notif->message }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            @if(!$notif->is_read)
                                <form action="{{ route('mitra.notifications.read', $notif->notification_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Tandai dibaca">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('mitra.notifications.destroy', $notif->notification_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus notifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash fs-3 text-muted d-block mb-2"></i>
                <p class="text-muted small mb-0">Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-3">{{ $notifications->links() }}</div>
    @endif
@endsection