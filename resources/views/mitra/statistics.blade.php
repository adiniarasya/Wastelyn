@extends('template.layout')

@section('title', 'Statistik')

@section('content')
    <h3 class="mb-4"><i class="bi bi-bar-chart-line"></i> Statistik</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat"><div class="card-body">
                <div class="text-muted small">Menunggu</div>
                <div class="fs-4 fw-bold">{{ $statusStats['pending'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat"><div class="card-body">
                <div class="text-muted small">Diproses</div>
                <div class="fs-4 fw-bold">{{ $statusStats['accepted'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat"><div class="card-body">
                <div class="text-muted small">Selesai</div>
                <div class="fs-4 fw-bold">{{ $statusStats['completed'] ?? 0 }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat"><div class="card-body">
                <div class="text-muted small">Ditolak</div>
                <div class="fs-4 fw-bold">{{ $statusStats['rejected'] ?? 0 }}</div>
            </div></div>
        </div>
    </div>

    <!-- Sisanya tetap sama ... -->
@endsection