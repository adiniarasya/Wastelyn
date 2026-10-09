@extends('template.layout')

@section('title', 'Ajukan Setoran Sampah')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3 class="fw-bold">Ajukan Setoran</h3>
                <p class="text-subtitle text-muted mb-0">Isi form di bawah buat ngajuin setoran sampah</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.pickup-requests.index') }}">Riwayat Setoran</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Ajukan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                {{-- INFO BANK LANGGANAN --}}
                @if(auth()->user()->wasteBank)
                    <div class="d-flex align-items-center gap-3 p-3 mb-4 bg-success bg-opacity-10 rounded-3">
                        <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px;">
                            <i class="bi bi-shop fs-5"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Setoran akan dikirim ke</small>
                            <strong>{{ auth()->user()->wasteBank->name }}</strong>
                        </div>
                    </div>
                @endif

                {{-- ERROR --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Ada yang salah:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('user.pickup-requests.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Jenis Sampah --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Jenis Sampah <span class="text-danger">*</span>
                            </label>
                            <select name="waste_category_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($wasteCategories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ old('waste_category_id') == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                        @if($cat->reward_per_kg)
                                            — {{ number_format($cat->reward_per_kg) }} poin/kg
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Estimasi Berat --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Estimasi Berat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0.1" name="weight_kg"
                                       value="{{ old('weight_kg') }}" class="form-control"
                                       placeholder="0.0" required>
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>

                        {{-- Metode --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Metode <span class="text-danger">*</span>
                            </label>
                            <select name="pickup_method" class="form-select" required>
                                <option value="pickup" {{ old('pickup_method') == 'pickup' ? 'selected' : '' }}>
                                    🚚 Dijemput
                                </option>
                                <option value="dropoff" {{ old('pickup_method') == 'dropoff' ? 'selected' : '' }}>
                                    📦 Antar Sendiri
                                </option>
                            </select>
                        </div>

                        {{-- Tanggal & Jam --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="pickup_date" class="form-control"
                                   value="{{ old('pickup_date', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                Jam <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="pickup_time" class="form-control"
                                   value="{{ old('pickup_time', '08:00') }}" required>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Alamat Penjemputan <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" class="form-control" rows="2"
                                      required>{{ old('address', auth()->user()->address) }}</textarea>
                        </div>

                        {{-- Catatan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Catatan <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <textarea name="notes" class="form-control" rows="2"
                                      placeholder="Misal: sampah sudah dipilah">{{ old('notes') }}</textarea>
                        </div>

                    </div>

                    {{-- TOMBOL --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-send me-1"></i> Ajukan Setoran
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>
</div>
@endsection