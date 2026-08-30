@extends('template.layout')

@section('title', 'Laporan - WasteLyn')

@section('content')

    <div class="page-heading">

        <div class="page-title">
            <div class="row">
                <div class="col-12">
                    <h3>Laporan</h3>
                    <p class="text-subtitle text-muted">
                        Rekapitulasi data WasteLyn
                    </p>
                </div>
            </div>
        </div>

        <section class="section">

            {{-- Statistik --}}
            <div class="row">

                <div class="col-md-4 col-lg-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Total User</h6>
                            <h3>{{ number_format($totalUsers) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Transaksi</h6>
                            <h3>{{ number_format($totalTransactions) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Setoran</h6>
                            <h3>{{ number_format($totalPickups) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Mission</h6>
                            <h3>{{ number_format($totalMissions) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Reward</h6>
                            <h3>{{ number_format($totalRewards) }}</h3>
                        </div>
                    </div>
                </div>

            </div>


            {{-- Transaksi Terbaru --}}
            <div class="card mt-4">

                <div class="card-header">
                    <h4 class="card-title mb-0">
                        Transaksi Terbaru
                    </h4>
                </div>

                <div class="card-body">

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
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($recentTransactions as $transaction)

                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>

                                                            <td>
                                                                {{ $transaction->user->name ?? '-' }}
                                                            </td>

                                                            <td>

                                                                @if($transaction->type == 'earn')

                                                                    <span class="badge bg-success">
                                                                        Earn
                                                                    </span>

                                                                @else

                                                                    <span class="badge bg-danger">
                                                                        Redeem
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            <td>
                                                                {{ number_format($transaction->points ?? 0, 0, ',', '.') }}
                                                            </td>

                                                            <td>

                                                                @if($transaction->status == 'completed')

                                                                    <span class="badge bg-success">
                                                                        Completed
                                                                    </span>

                                                                @elseif($transaction->status == 'pending')

                                                                    <span class="badge bg-warning text-dark">
                                                                        Pending
                                                                    </span>

                                                                @elseif($transaction->status == 'failed')

                                                                    <span class="badge bg-danger">
                                                                        Failed
                                                                    </span>

                                                                @else

                                                                    <span class="badge bg-secondary">
                                                                        {{ ucfirst($transaction->status) }}
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            <td>

                                                                {{ $transaction->created_at
                                    ? $transaction->created_at->format('d/m/Y H:i')
                                    : '-' }}

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center text-muted py-4">

                                            Belum ada transaksi

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- User Terbaru --}}
            <div class="card mt-4">

                <div class="card-header">
                    <h4 class="card-title mb-0">
                        User Terbaru
                    </h4>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($recentUsers as $user)

                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>

                                                            <td>
                                                                <strong>
                                                                    {{ $user->name }}
                                                                </strong>
                                                            </td>

                                                            <td>
                                                                {{ $user->email }}
                                                            </td>

                                                            <td>
                                                                <span class="badge bg-info">
                                                                    {{ ucfirst($user->role) }}
                                                                </span>
                                                            </td>

                                                            <td>

                                                                @if($user->status == 'active')

                                                                    <span class="badge bg-success">
                                                                        Aktif
                                                                    </span>

                                                                @else

                                                                    <span class="badge bg-secondary">
                                                                        {{ ucfirst($user->status) }}
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            <td>

                                                                {{ $user->created_at
                                    ? $user->created_at->format('d/m/Y')
                                    : '-' }}

                                                            </td>

                                                        </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center text-muted py-4">

                                            Belum ada user

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