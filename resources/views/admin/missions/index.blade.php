@extends('template.layout')

@section('title', 'Kelola Mission - WasteLyn')

@section('content')
    <div class="page-heading">

        <div class="page-title">
            <h3>Kelola Mission</h3>
            <p class="text-subtitle text-muted">Kelola semua mission WasteLyn</p>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Mission</h4>

                    <a href="{{ route('admin.missions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Tambah Mission
                    </a>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mission</th>
                                    <th>Deskripsi</th>
                                    <th>Target</th>
                                    <th>XP</th>
                                    <th>Poin</th>
                                    <th>Mulai</th>
                                    <th>Berakhir</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($missions as $mission)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $mission->title }}</strong>
                                        </td>

                                        <td>
                                            {{ \Illuminate\Support\Str::limit($mission->description, 50) }}
                                        </td>

                                        <td>
                                            {{ $mission->target }}
                                        </td>

                                        <td>
                                            {{ $mission->reward_xp }} XP
                                        </td>

                                        <td>
                                            {{ number_format($mission->reward_points, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($mission->start_date)->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($mission->end_date)->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            @if($mission->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-1">

                                                <a href="{{ route('admin.missions.show', $mission->mission_id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.missions.edit', $mission->mission_id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form action="{{ route('admin.missions.destroy', $mission->mission_id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus mission ini?')">

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
                                        <td colspan="10" class="text-center text-muted py-5">
                                            Belum ada mission
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