<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WasteLyn') }} — @yield('title', 'Masuk')</title>

    {{-- Poppins (sesuai proposal) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (untuk halaman auth saja) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Gradient background hijau eco */
        .auth-bg {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            position: relative;
            overflow: hidden;
        }

        /* Pattern lingkaran dekoratif */
        .auth-bg::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -150px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .auth-bg::after {
            content: '';
            position: absolute;
            bottom: -180px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        /* Input style */
        .form-input {
            width: 100%;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #FAFBFC;
        }

        .form-input:focus {
            outline: none;
            border-color: #2E7D32;
            background: white;
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        /* Button primary */
        .btn-primary {
            background: #2E7D32;
            color: white;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background: #1B5E20;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }

        /* Card putih */
        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 40px;
        }

        /* Logo placeholder */
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
            font-weight: 800;
            color: #2E7D32;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="antialiased">

    <div class="auth-bg min-h-screen flex items-center justify-center px-4 py-10">

        {{-- Konten di tengah --}}
        <div class="w-full max-w-md relative z-10">

            {{-- Brand Header --}}
            <div class="text-center mb-6">
                <div class="brand-logo">
                    <span style="font-size: 32px;">♻️</span>
                    <span style="color: white;">WasteLyn</span>
                </div>
                <p class="text-white/80 text-sm mt-2">
                    Platform Pembentukan Kebiasaan Pengelolaan Sampah
                </p>
            </div>

            {{-- Card Form --}}
            <div class="auth-card">
                {{ $slot }}
            </div>

            {{-- Footer kecil --}}
            <div class="text-center mt-6">
                <p class="text-white/70 text-xs">
                    &copy; {{ date('Y') }} WasteLyn — Dibuat dengan ❤️ untuk lingkungan yang lebih baik
                </p>
            </div>

        </div>

    </div>

</body>
</html>