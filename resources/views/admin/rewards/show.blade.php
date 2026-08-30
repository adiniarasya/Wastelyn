@extends('template.layout')

@section('title', 'Detail Reward - WasteLyn')

@section('content')
    <div class="page-heading">

        <div class="page-title">
            <h3>Detail Reward</h3>
            <p class="text-subtitle text-muted">Informasi lengkap reward</p>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <h4 class="card-title">
                        {{ $reward->name }}
                    </h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <table class="table table-borderless">

                                <tr>
                                    <th width="180">Nama Reward</th>
                                    <td>{{ $reward->name }}</td>
                                </tr>

                                <tr>
                                    <th>Poin Dibutuhkan</th>
                                    <td>
                                        {{ number_format($reward->point_required, 0, ',', '.') }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Stok</th>
                                    <td>{{ $reward->stock }}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>

                                    <td>
                                        @if($reward->status == 'available')
                                            <span class="badge bg-success">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Tidak Tersedia
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            </table>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">
                                <strong>Deskripsi</strong>

                                <p class="text-muted mt-2">
                                    {{ $reward->description ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <strong>Gambar</strong>

                                <div class="mt-2">

                                    @if($reward->image)
                                        <img src="{{ $reward->image }}" alt="{{ $reward->name }}" class="img-fluid rounded"
                                            style="max-height: 250px;">
                                    @else
                                        <p class="text-muted">
                                            Tidak ada gambar
                                        </p>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">

                        <a href="{{ route('admin.rewards.edit', $reward->reward_id) }}" class="btn btn-warning">
                            Edit
                        </a>

                        <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </div>
            </div>
        </section>

    </div>
@endsection