<x-guest-layout>

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">
            Selamat Datang Kembali 👋
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            Masuk untuk melanjutkan perjalanan hijau kamu
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-5" :status="session('status')" />

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.5a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zM10 14a.875.875 0 100 1.75A.875.875 0 0010 14z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="nama@email.com"
                class="form-input">
            @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="text-sm font-medium text-gray-700">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-green-700 hover:text-green-900 transition">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="relative">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password kamu"
                    class="form-input pr-12">

                <button type="button" onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-green-700 transition"
                    aria-label="Tampilkan password">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.584 10.587a2 2 0 002.829 2.829" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="h-4 w-4 rounded border-gray-300 text-green-700 shadow-sm focus:ring-green-600">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit" class="btn-primary">
                Masuk
            </button>
        </div>

        {{-- Register Link --}}
        <div class="text-center pt-2">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="ml-1 font-semibold text-green-700 hover:text-green-900 transition">
                    Daftar sekarang
                </a>
            </p>
        </div>

    </form>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>