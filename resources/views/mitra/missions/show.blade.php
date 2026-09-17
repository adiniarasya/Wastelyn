@extends('template.layout')

@section('title', 'Detail Mission - WasteLyn')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <h3>Detail Mission</h3>
            <p class="text-subtitle text-muted">Informasi lengkap mission</p>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ $mission->title }}</h4>
                    @if($mission->status == 'active')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Informasi Mission --}}
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="180">Bank Sampah</th>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $mission->bank->name ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Target</th>
                                    <td>{{ $mission->target }} {{ $mission->unit ?? 'item' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Misi</th>
                                    <td>
                                        @if($mission->type == 'quantitative')
                                            <span class="badge bg-info">Kuantitatif (Hitung)</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Kualitatif (Karya)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reward XP</th>
                                    <td>{{ $mission->reward_xp }} XP</td>
                                </tr>
                                <tr>
                                    <th>Reward Poin</th>
                                    <td>{{ number_format($mission->reward_points, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Waktu Mission --}}
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="180">Tanggal Mulai</th>
                                    <td>{{ \Carbon\Carbon::parse($mission->start_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Berakhir</th>
                                    <td>{{ \Carbon\Carbon::parse($mission->end_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $mission->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Terakhir Update</th>
                                    <td>{{ $mission->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-12">
                            <hr>
                            <h6>Deskripsi Mission</h6>
                            <p class="text-muted">{{ $mission->description }}</p>
                        </div>

                        {{-- AI Prompt --}}
                        <div class="col-12">
                            <hr>
                            <h6>AI Prompt</h6>
                            <div class="alert alert-light border">
                                <code>{{ $mission->ai_prompt ?? '-' }}</code>
                            </div>
                        </div>

                        {{-- Daftar Warga yang Ikut --}}
                        <div class="col-12">
                            <hr>
                            <h6>Warga yang Mengikuti Misi ({{ $mission->userMissions->count() }})</h6>

                            @if($mission->userMissions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Warga</th>
                                                <th>Progress</th>
                                                <th>Status</th>
                                                <th>Kode Misi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mission->userMissions as $um)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $um->user->name ?? '-' }}</td>
                                                    <td>
                                                        {{ $um->progress }} / {{ $mission->target }}
                                                        {{ $mission->unit ?? 'item' }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusColor = [
                                                                'ongoing' => 'warning',
                                                                'ready_pickup' => 'info',
                                                                'picked_up' => 'primary',
                                                                'completed' => 'success',
                                                            ][$um->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge bg-{{ $statusColor }}">
                                                            {{ $um->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-dark">
                                                            {{ $um->unique_code ?? '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada warga yang mengikuti misi ini.</p>
                            @endif
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('mitra.missions.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <a href="{{ route('mitra.missions.edit', $mission->mission_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Mission
                        </a>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection