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

                <div class="card-header">
                    <h4 class="card-title">
                        {{ $mission->title }}
                    </h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Informasi Mission --}}
                        <div class="col-md-6">

                            <table class="table table-borderless">

                                <tr>
                                    <th width="180">Nama Mission</th>
                                    <td>{{ $mission->title }}</td>
                                </tr>

                                <tr>
                                    <th>Target</th>
                                    <td>{{ $mission->target }}</td>
                                </tr>

                                <tr>
                                    <th>Reward XP</th>
                                    <td>{{ $mission->reward_xp }} XP</td>
                                </tr>

                                <tr>
                                    <th>Reward Poin</th>
                                    <td>
                                        {{ number_format($mission->reward_points, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($mission->status == 'active')
                                            <span class="badge bg-success">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            </table>

                        </div>

                        {{-- Waktu Mission --}}
                        <div class="col-md-6">

                            <table class="table table-borderless">

                                <tr>
                                    <th width="180">Tanggal Mulai</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($mission->start_date)->format('d/m/Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Tanggal Berakhir</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($mission->end_date)->format('d/m/Y') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Dibuat</th>
                                    <td>
                                        {{ $mission->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Terakhir Update</th>
                                    <td>
                                        {{ $mission->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>

                            </table>

                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-12">

                            <hr>

                            <h6>Deskripsi Mission</h6>

                            <p class="text-muted">
                                {{ $mission->description }}
                            </p>

                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">

                        <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                        <a href="{{ route('admin.missions.edit', $mission->mission_id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i>
                            Edit Mission
                        </a>

                    </div>

                </div>
            </div>
        </section>

    </div>
@endsection