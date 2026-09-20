@extends('template.layout')

@section('title', 'Bank Sampah')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-shop"></i> Daftar Bank Sampah</h3>
        <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-3">
        @forelse ($wasteBanks as $bank)
            <div class="col-md-4">
                <div class="card card-stat h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $bank->name }}</h5>
                        <p class="text-muted mb-1">
                            <i class="bi bi-geo-alt"></i> {{ $bank->address ?? '-' }}
                        </p>
                        @if($bank->phone)
                            <p class="text-muted mb-1">
                                <i class="bi bi-telephone"></i> {{ $bank->phone }}
                            </p>
                        @endif
                        @if($bank->opening_hours)
                            <p class="text-muted mb-0">
                                <i class="bi bi-clock"></i> {{ $bank->opening_hours }}
                            </p>
                        @endif

                        <a href="{{ route('user.waste-banks.show', $bank) }}" class="btn btn-sm btn-outline-success mt-3">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Belum ada bank sampah terdaftar.
                </div>
            </div>
        @endforelse
    </div>
@endsection