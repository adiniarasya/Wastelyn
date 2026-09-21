@extends('template.layout')

@section('title', 'Notifikasi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="bi bi-bell"></i> Notifikasi
            @if($unreadCount > 0)
                <span class="badge bg-danger">{{ $unreadCount }} baru</span>
            @endif
        </h3>
        @if($unreadCount > 0)
            <form action="{{ route('mitra.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-check-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card card-stat mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="info" @selected(request('type') === 'info')>Info</option>
                        <option value="success" @selected(request('type') === 'success')>Sukses</option>
                        <option value="warning" @selected(request('type') === 'warning')>Peringatan</option>
                        <option value="danger" @selected(request('type') === 'danger')>Penting</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="unread" @selected(request('status') === 'unread')>Belum Dibaca</option>
                        <option value="read" @selected(request('status') === 'read')>Sudah Dibaca</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="card card-stat">
        <div class="card-body p-0">
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
                    <div class="me-3">
                        <i class="bi {{ $icon }} fs-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 {{ $notif->is_read ? '' : 'fw-bold' }}">
                                    {{ $notif->title }}
                                </h6>
                                <p class="mb-1 text-muted small">{{ $notif->message }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="d-flex gap-1">
                                @if(!$notif->is_read)
                                    <form action="{{ route('mitra.notifications.read', $notif->notification_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Tandai dibaca">
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
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mb-0 mt-2">Belum ada notifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
@endsection