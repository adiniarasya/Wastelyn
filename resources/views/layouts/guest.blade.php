<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WasteLyn') }} — @yield('title', 'Masuk')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --wl-green: #2E7D32;
            --wl-green-dark: #1B5E20;
            --wl-green-light: #4CAF50;
            --wl-ink: #1a2330;
            --wl-muted: #6b7a8c;
            --wl-line: #e6e9ee;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8faf8;
        }

        .form-input {
            width: 100%;
            border: 1px solid var(--wl-line);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fff;
            color: var(--wl-ink);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--wl-green);
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
        }

        .form-input::placeholder {
            color: #9CA3AF;
        }

        .btn-primary {
            background: var(--wl-green);
            color: white;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 100%;
            border: 0;
        }

        .btn-primary:hover {
            background: var(--wl-green-dark);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.25);
        }

        .auth-card {
            background: #fff;
            border: 1px solid var(--wl-line);
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(15, 23, 32, 0.06);
            padding: 40px;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen flex items-center justify-center px-4 py-10">

        <div class="w-full max-w-md">

            <div class="text-center mb-6">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo WasteLyn" class="mx-auto mb-3"
                    style="height:56px;width:auto;">
                <h1 class="text-xl font-bold" style="color:var(--wl-ink);">WasteLyn</h1>
                <p class="text-sm mt-1" style="color:var(--wl-muted);">
                    Platform Pembentukan Kebiasaan Pengelolaan Sampah
                </p>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>

            <div class="text-center mt-6">
                <p class="text-xs" style="color:var(--wl-muted);">
                    &copy; {{ date('Y') }} WasteLyn — Dibuat dengan
                    untuk lingkungan yang lebih baik
                </p>
            </div>

        </div>

    </div>

</body>

</html>