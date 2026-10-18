@extends('template.layout')

@section('title', 'Ajukan Setoran Sampah')

@section('content')
    

    <div class="page-heading pickup-form">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Ajukan Setoran</h3>
                    <p class="text-subtitle text-muted mb-0">
                        Isi form di bawah untuk mengajukan setoran sampah.
                    </p>
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
            <div class="card">
                <div class="card-body">


                    {{-- Daftar error --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Ada yang perlu diperbaiki:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.pickup-requests.store') }}">
                        @csrf

                        <div class="row g-3">

                            {{-- Jenis sampah --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Jenis sampah <span class="text-danger">*</span>
                                </label>
                                <select name="waste_category_id"
                                    class="form-select @error('waste_category_id') is-invalid @enderror" required>
                                    <option value="">Pilih jenis sampah</option>
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
                                @error('waste_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Estimasi berat --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Estimasi berat <span class="text-danger">*</span>
                                </label>
                                <div class="input-group has-validation">
                                    <input type="number" step="0.1" min="0.1" name="weight_kg"
                                        value="{{ old('weight_kg') }}"
                                        class="form-control @error('weight_kg') is-invalid @enderror"
                                        placeholder="0.0" required>
                                    <span class="input-group-text">kg</span>
                                    @error('weight_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Metode --}}
                            <div class="col-12 col-md-6">
                                <label class="form-label">
                                    Metode <span class="text-danger">*</span>
                                </label>
                                <select name="pickup_method"
                                    class="form-select @error('pickup_method') is-invalid @enderror" required>
                                    <option value="pickup" {{ old('pickup_method') == 'pickup' ? 'selected' : '' }}>
                                        Dijemput
                                    </option>
                                    <option value="dropoff" {{ old('pickup_method') == 'dropoff' ? 'selected' : '' }}>
                                        Antar sendiri
                                    </option>
                                </select>
                                @error('pickup_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal dan jam --}}
                            <div class="col-6 col-md-3">
                                <label class="form-label">
                                    Tanggal <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="pickup_date"
                                    class="form-control @error('pickup_date') is-invalid @enderror"
                                    value="{{ old('pickup_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                @error('pickup_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">
                                    Jam <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="pickup_time"
                                    class="form-control @error('pickup_time') is-invalid @enderror"
                                    value="{{ old('pickup_time', '08:00') }}" required>
                                @error('pickup_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Alamat penjemputan <span class="text-danger">*</span>
                                </label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                    rows="2" required>{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="col-12">
                                <label class="form-label">
                                    Catatan <span class="text-muted fw-normal">(opsional)</span>
                                </label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                    rows="2" placeholder="Contoh: sampah sudah dipilah">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-light">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                Ajukan setoran
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection