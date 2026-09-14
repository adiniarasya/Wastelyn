<x-guest-layout>

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">
            Buat Akun Baru ✨
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            Mulai perjalanan hijau kamu bersama WasteLyn
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                Nama Lengkap
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name" placeholder="Masukkan nama lengkap"
                class="form-input">
            @error('name')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" placeholder="nama@email.com"
                class="form-input">
            @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Role --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">
                Daftar Sebagai
            </label>

            <div class="grid grid-cols-2 gap-3">

                {{-- Warga --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="warga" class="peer sr-only"
                        {{ old('role') === 'warga' ? 'checked' : '' }} required>

                    <div class="p-3 border-2 border-gray-200 rounded-xl transition-all
                                peer-checked:border-green-700 peer-checked:bg-green-50
                                hover:border-green-300">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-green-100">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2M7 11a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Warga</p>
                                <p class="text-xs text-gray-500">Pengguna umum</p>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Mitra --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="mitra" class="peer sr-only"
                        {{ old('role') === 'mitra' ? 'checked' : '' }}>

                    <div class="p-3 border-2 border-gray-200 rounded-xl transition-all
                                peer-checked:border-green-700 peer-checked:bg-green-50
                                hover:border-green-300">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-green-100">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Mitra</p>
                                <p class="text-xs text-gray-500">Partner layanan</p>
                            </div>
                        </div>
                    </div>
                </label>

            </div>

            <div class="flex items-start gap-2 mt-3 p-2.5 rounded-lg bg-gray-50">
                <svg class="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z" />
                </svg>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Akun Mitra memerlukan persetujuan Admin sebelum dapat digunakan.
                </p>
            </div>

            @error('role')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                Password
            </label>
            <input id="password" type="password" name="password" required
                autocomplete="new-password" placeholder="Masukkan password"
                class="form-input">
            @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                Konfirmasi Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="Ulangi password"
                class="form-input">
        </div>

        {{-- Submit --}}
        <div class="pt-2">
            <button type="submit" class="btn-primary">
                Buat Akun
            </button>
        </div>

        {{-- Login Link --}}
        <div class="text-center pt-2">
            <span class="text-sm text-gray-500">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-sm font-semibold text-green-700 hover:text-green-900 ml-1">
                Masuk sekarang
            </a>
        </div>

    </form>

</x-guest-layout>