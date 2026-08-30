@extends('template.layout')

@section('title', 'Kelola User - WasteLyn')

@section('content')

    <div class="page-heading">

        {{-- Page Title --}}
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12">
                    <h3 class="fw-bold">Kelola User</h3>
                    <p class="text-subtitle text-muted">
                        Daftar semua pengguna WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="card shadow-sm border-0 rounded-4">

                {{-- Card Header --}}
                <div
                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <h5 class="card-title mb-0">
                        Daftar User
                    </h5>

                    <div class="d-flex gap-2">

                        {{-- Export PDF --}}
                        <a href="{{ route('admin.users.export.pdf', request()->query()) }}"
                            class="btn btn-sm btn-outline-danger">

                            <i class="bi bi-file-earmark-pdf"></i>
                            Export PDF

                        </a>

                        {{-- Tambah User --}}
                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">

                            <i class="bi bi-plus-circle"></i>
                            Tambah

                        </a>

                    </div>

                </div>


                {{-- Card Body --}}
                <div class="card-body">

                    {{-- Search --}}
                    <div class="row mb-4">

                        <div class="col-md-6">

                            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">

                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari nama, email, atau role..." value="{{ request('search') }}">

                                <button type="submit" class="btn btn-outline-primary">

                                    <i class="bi bi-search"></i>

                                </button>

                                @if(request('search'))

                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">

                                        <i class="bi bi-x-lg"></i>

                                    </a>

                                @endif

                            </form>

                        </div>

                    </div>


                    {{-- Tabel --}}
                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>XP</th>
                                    <th>Point</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($users as $item)

                                    <tr>

                                        {{-- FOTO --}}
                                        <td>

                                            @if(
                                                    $item->photo &&
                                                    \Illuminate\Support\Facades\Storage::disk('public')->exists($item->photo)
                                                )

                                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}"
                                                    class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">

                                            @else

                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-semibold"
                                                    style="width: 45px; height: 45px;">

                                                    {{ strtoupper(substr($item->name, 0, 1)) }}

                                                </div>

                                            @endif

                                        </td>


                                        {{-- NAMA --}}
                                        <td class="fw-semibold">

                                            {{ $item->name }}

                                        </td>


                                        {{-- EMAIL --}}
                                        <td>

                                            {{ $item->email }}

                                        </td>


                                        {{-- ROLE --}}
                                        <td>

                                            @if($item->role === 'admin')

                                                <span class="badge bg-danger">
                                                    Admin
                                                </span>

                                            @elseif($item->role === 'mitra')

                                                <span class="badge bg-success">
                                                    Mitra
                                                </span>

                                            @else
                                                <span class="badge bg-primary">
                                                    Warga
                                                </span>
                                            @endif
                                        </td>


                                        {{-- XP --}}
                                        <td>
                                            {{ number_format($item->xp ?? 0, 0, ',', '.') }}
                                        </td>


                                        {{-- POINT --}}
                                        <td>
                                            {{ number_format($item->points ?? 0, 0, ',', '.') }}
                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            @if($item->status === 'active')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i>
                                                    Aktif
                                                </span>

                                            @elseif($item->status === 'pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i>
                                                    Pending
                                                </span>

                                            @elseif($item->status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i>
                                                    Ditolak
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    Tidak diketahui
                                                </span>
                                            @endif
                                        </td>


                                        {{-- AKSI --}}
                                        <td>

                                            <div class="d-flex justify-content-center gap-1">
                                                @if(
                                                        $item->role === 'mitra' &&
                                                        $item->status === 'pending'
                                                    )

                                                    {{-- SETUJUI --}}
                                                    <form action="{{ route('admin.users.approve', $item->user_id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui Mitra">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>

                                                    </form>


                                                    {{-- TOLAK --}}
                                                    <form action="{{ route('admin.users.reject', $item->user_id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menolak mitra ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak Mitra">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif


                                                {{-- DETAIL --}}
                                                <a href="{{ route('admin.users.show', $item->user_id) }}"
                                                    class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>

                                                </a>

                                                {{-- EDIT --}}
                                                <a href="{{ route('admin.users.edit', $item->user_id) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit User">
                                                    <i class="bi bi-pencil"></i>
                                                </a>


                                                {{-- HAPUS --}}
                                                @if($item->user_id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $item->user_id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Hapus User">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>


                                    {{-- JIKA DATA KOSONG --}}
                                @empty

                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            @if(request('search'))
                                                Data user tidak ditemukan.
                                            @else
                                                Belum ada data user.
                                            @endif
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>
                    </div>


                    {{-- PAGINATION --}}
                    @if($users->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $users->links() }}
                        </div>

                    @endif
                </div>
            </div>
        </section>
    </div>

@endsection