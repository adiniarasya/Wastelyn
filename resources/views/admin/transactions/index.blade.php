@extends('template.layout')

@section('title', 'Kelola Transaksi - WasteLyn')

@section('content')

    <div class="page-heading">

        <div class="page-title">
            <div class="row">
                <div class="col-12">
                    <h3>Kelola Transaksi</h3>
                    <p class="text-subtitle text-muted">
                        Daftar semua transaksi di platform
                    </p>
                </div>
            </div>
        </div>

        <section class="section">

            <div class="card shadow-sm border-0">

                {{-- Header --}}
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">
                        Daftar Transaksi
                    </h4>
                </div>

                <div class="card-body">

                    {{-- Filter --}}
                    <form action="{{ route('admin.transactions.index') }}" method="GET">

                        <div class="row g-3 mb-4">

                            {{-- Search --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Cari User
                                </label>

                                <div class="input-group">

                                    <input type="text" name="search" class="form-control" placeholder="Cari nama user..."
                                        value="{{ request('search') }}">

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i>
                                    </button>

                                </div>

                            </div>

                            {{-- Tipe --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Tipe Transaksi
                                </label>

                                <select class="form-select" name="type">

                                    <option value="">
                                        Semua Tipe
                                    </option>

                                    <option value="earn" {{ request('type') == 'earn' ? 'selected' : '' }}>
                                        Earn
                                    </option>

                                    <option value="redeem" {{ request('type') == 'redeem' ? 'selected' : '' }}>
                                        Redeem
                                    </option>

                                </select>

                            </div>

                            {{-- Status --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select class="form-select" name="status">

                                    <option value="">
                                        Semua Status
                                    </option>

                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                        Failed
                                    </option>

                                </select>

                            </div>

                            {{-- Tombol Filter --}}
                            <div class="col-md-2 d-flex align-items-end gap-2">

                                <button type="submit" class="btn btn-outline-primary">
                                    Filter
                                </button>

                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary"
                                    title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>

                            </div>

                        </div>

                    </form>

                    {{-- Tabel --}}
                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Tipe</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($transactions as $item)

                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>

                                                            <td>
                                                                <strong>
                                                                    {{ $item->user->name ?? 'User' }}
                                                                </strong>
                                                            </td>

                                                            {{-- Tipe --}}
                                                            <td>

                                                                @if($item->type == 'earn')

                                                                    <span class="badge bg-success">
                                                                        Earn
                                                                    </span>

                                                                @else

                                                                    <span class="badge bg-danger">
                                                                        Redeem
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            {{-- Poin --}}
                                                            <td>

                                                                <strong>
                                                                    {{ number_format($item->points ?? 0, 0, ',', '.') }}
                                                                </strong>

                                                            </td>

                                                            {{-- Status --}}
                                                            <td>

                                                                @if($item->status == 'completed')

                                                                    <span class="badge bg-success">
                                                                        Completed
                                                                    </span>

                                                                @elseif($item->status == 'pending')

                                                                    <span class="badge bg-warning text-dark">
                                                                        Pending
                                                                    </span>

                                                                @else

                                                                    <span class="badge bg-danger">
                                                                        Failed
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            {{-- Tanggal --}}
                                                            <td>

                                                                {{ $item->created_at
                                    ? $item->created_at->format('d/m/Y H:i')
                                    : '-' }}

                                                            </td>

                                                            {{-- Aksi --}}
                                                            <td>

                                                                <div class="d-flex justify-content-center">

                                                                    <a href="{{ route('admin.transactions.show', $item->id) }}"
                                                                        class="btn btn-sm btn-outline-info" title="Detail">

                                                                        <i class="bi bi-eye"></i>

                                                                    </a>

                                                                </div>

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>

                                        <td colspan="7" class="text-center text-muted py-5">

                                            <i class="bi bi-receipt fs-3 d-block mb-2"></i>

                                            Belum ada transaksi

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)

                        <div class="mt-3">
                            {{ $transactions->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </section>

    </div>

@endsection