<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Awal - WasteLyn</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .onboarding-modal {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .onboarding-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .onboarding-icon i {
            font-size: 40px;
            color: #2E7D32;
        }

        .step-badge {
            display: inline-block;
            background: #E8F5E9;
            color: #2E7D32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px 16px;
            border: 2px solid #E0E0E0;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        }

        .btn-success {
            background: linear-gradient(135deg, #2E7D32, #4CAF50);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #1B5E20, #388E3C);
        }
    </style>
</head>

<body>

    <div class="onboarding-modal">
        <div class="text-center mb-4">
            <div class="onboarding-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div class="step-badge">LANGKAH 1 DARI 1</div>
            <h3 class="fw-bold mb-2">Selamat Datang! 🌱</h3>
            <p class="text-muted mb-0">
                Isi data berikut biar kami bisa kasih misi & layanan terbaik buat kamu.
            </p>
        </div>

        <form action="{{ route('warga.onboarding.store') }}" method="POST">
            @csrf

            {{-- Alamat --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat Lengkap</label>
                <textarea name="address" rows="3"
                    class="form-control @error('address') is-invalid @enderror"
                    placeholder="Jl. Contoh No. 123, Depok"
                    required>{{ old('address') }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bank Sampah --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Pilih Bank Sampah Terdekat</label>
                <select name="waste_bank_id"
                    class="form-select @error('waste_bank_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Bank Sampah --</option>
                    @foreach($mitras as $mitra)
                    @if($mitra->managedWasteBank)
                    <option value="{{ $mitra->managedWasteBank->bank_id }}"
                        {{ old('waste_bank_id') == $mitra->managedWasteBank->bank_id ? 'selected' : '' }}>
                        {{ $mitra->name }} — {{ $mitra->managedWasteBank->address ?? '' }}
                    </option>
                    @endif
                    @endforeach
                </select>
                @error('waste_bank_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    💡 Bank sampah ini bakal jadi langganan kamu. Bisa diganti kapan aja di profil.
                </small>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-check-circle me-1"></i> Mulai Sekarang
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>