<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - WasteLyn</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', 'Poppins', sans-serif;
        }

        .font-display {
            font-family: 'Poppins', sans-serif;
        }

        .bg-primary { background: #2E7D32; }
        .bg-primary-dark { background: #1B5E20; }
        .text-primary { color: #2E7D32; }
        .border-primary { border-color: #2E7D32; }

        .btn-primary {
            background: #2E7D32;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1B5E20;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.3);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 50%, #E3F2FD 100%);
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #2E7D32;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
            outline: none;
        }

        .blob-1 {
            position: absolute;
            top: -10%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: rgba(46, 125, 50, 0.05);
            border-radius: 50%;
            z-index: 0;
        }

        .blob-2 {
            position: absolute;
            bottom: -10%;
            left: -5%;
            width: 250px;
            height: 250px;
            background: rgba(26, 35, 126, 0.05);
            border-radius: 50%;
            z-index: 0;
        }
    </style>
</head>
<body>

    <div class="min-h-screen flex items-center justify-center gradient-bg p-4 relative overflow-hidden">
        <!-- Decorative Blobs -->
        <div class="blob-1"></div>
        <div class="blob-2"></div>

        <!-- Card -->
        <div class="card-glass rounded-3xl shadow-2xl max-w-md w-full p-8 relative z-10">

            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-2">🌿</div>
                <h1 class="text-3xl font-extrabold text-primary font-display">WasteLyn</h1>
                <p class="text-gray-500 text-sm mt-1">Daftar akun baru</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm max-h-32 overflow-y-auto">
                    @foreach($errors->all() as $error)
                        <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                    <p><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-user text-primary mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition"
                        placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-envelope text-primary mr-2"></i>Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition"
                        placeholder="masukkan@email.com" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-lock text-primary mr-2"></i>Password
                    </label>
                    <input type="password" name="password"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition"
                        placeholder="Minimal 8 karakter" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-check-circle text-primary mr-2"></i>Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition"
                        placeholder="Ulangi password" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-phone text-primary mr-2"></i>No HP
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition"
                        placeholder="0812xxxxxx">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>Alamat
                    </label>
                    <textarea name="address" rows="2"
                        class="input-field w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary transition resize-none"
                        placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <p class="text-center text-gray-600 text-sm mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk sekarang</a>
            </p>

            <!-- Footer -->
            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">
                    <i class="fas fa-shield-alt text-primary mr-1"></i>
                    Data Anda aman dan terenkripsi
                </p>
            </div>
        </div>
    </div>

</body>
</html>