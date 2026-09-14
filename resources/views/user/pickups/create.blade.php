@extends('template.layout')
@section('title', 'Ajukan Setoran Sampah')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-sm btn-link ps-0 mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h4 class="mb-4 fw-semibold">
                        <i class="bi bi-recycle"></i> Ajukan Setoran Sampah
                    </h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.pickup-requests.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Jenis Sampah</label>
                            <select name="waste_category_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($wasteCategories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ old('waste_category_id') == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                        @if($cat->reward_per_kg)
                                            ({{ number_format($cat->reward_per_kg) }} poin/kg)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimasi Berat (kg)</label>
                            <input type="number" step="0.1" min="0.1" name="weight_kg"
                                   value="{{ old('weight_kg') }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bank Sampah / Mitra Tujuan</label>
                            <select name="bank_id" class="form-select" required>
                                <option value="">-- Pilih Bank --</option>
                                @foreach($wasteBanks as $bank)
                                    <option value="{{ $bank->bank_id }}"
                                        {{ old('bank_id') == $bank->bank_id ? 'selected' : '' }}>
                                        {{ $bank->name }} - {{ $bank->address ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode</label>
                            <select name="pickup_method" class="form-select" required>
                                <option value="pickup" {{ old('pickup_method') == 'pickup' ? 'selected' : '' }}>Dijemput (Pickup)</option>
                                <option value="dropoff" {{ old('pickup_method') == 'dropoff' ? 'selected' : '' }}>Antar Sendiri (Dropoff)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="pickup_date" class="form-control"
                                       value="{{ old('pickup_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Jam</label>
                                <input type="time" name="pickup_time" class="form-control"
                                       value="{{ old('pickup_time', '08:00') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', auth()->user()->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Misal: sampah sudah dipilah">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-send"></i> Ajukan Setoran
                            </button>
                            <a href="{{ route('user.pickup-requests.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection