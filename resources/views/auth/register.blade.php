<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="flex justify-center mb-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9a3 3 0 11-6 0 3 3 0 016 0zM6 9a3 3 0 11-6 0 3 3 0 016 0zM2 20a6 6 0 0112 0M14 20a6 6 0 016-6 6 6 0 016 6" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800">
            Buat Akun Baru
        </h2>

        <p class="mt-2 text-sm text-gray-500">
            Daftarkan dirimu dan mulai berkontribusi bersama kami.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label
                for="name"
                :value="__('Nama Lengkap')"
                class="text-gray-700 font-medium"
            />

            <x-text-input
                id="name"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
            />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2"
            />
        </div>

        <!-- Email -->
        <div class="mt-5">
            <x-input-label
                for="email"
                :value="__('Email')"
                class="text-gray-700 font-medium"
            />

            <x-text-input
                id="email"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="nama@email.com"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Role -->
        <div class="mt-5">
            <x-input-label
                for="role"
                :value="__('Daftar Sebagai')"
                class="text-gray-700 font-medium"
            />

            <div class="grid grid-cols-2 gap-3 mt-2">

                <!-- Warga -->
                <label
                    class="relative cursor-pointer"
                >
                    <input
                        type="radio"
                        name="role"
                        value="warga"
                        class="peer sr-only"
                        {{ old('role') === 'warga' ? 'checked' : '' }}
                        required
                    >

                    <div class="p-4 border-2 border-gray-200 rounded-xl
                                peer-checked:border-emerald-500
                                peer-checked:bg-emerald-50
                                hover:border-emerald-300
                                transition-all duration-200">

                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-100">
                                <svg class="w-5 h-5 text-emerald-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2M7 11a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 100-8 4 4 0 000 8zM17 15h1a4 4 0 014 4v2" />
                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-gray-800">
                                    Warga
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pengguna umum
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Mitra -->
                <label
                    class="relative cursor-pointer"
                >
                    <input
                        type="radio"
                        name="role"
                        value="mitra"
                        class="peer sr-only"
                        {{ old('role') === 'mitra' ? 'checked' : '' }}
                    >

                    <div class="p-4 border-2 border-gray-200 rounded-xl
                                peer-checked:border-emerald-500
                                peer-checked:bg-emerald-50
                                hover:border-emerald-300
                                transition-all duration-200">

                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-100">
                                <svg class="w-5 h-5 text-emerald-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-gray-800">
                                    Mitra
                                </p>
                                <p class="text-xs text-gray-500">
                                    Partner layanan
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

            </div>

            <div class="flex items-start gap-2 mt-3 p-3 rounded-lg bg-gray-50">
                <svg class="w-4 h-4 mt-0.5 text-gray-400 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z" />
                </svg>

                <p class="text-xs text-gray-500 leading-relaxed">
                    Akun Mitra memerlukan persetujuan Admin sebelum dapat digunakan.
                </p>
            </div>

            <x-input-error
                :messages="$errors->get('role')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label
                for="password"
                :value="__('Password')"
                class="text-gray-700 font-medium"
            />

            <x-text-input
                id="password"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Masukkan password"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label
                for="password_confirmation"
                :value="__('Konfirmasi Password')"
                class="text-gray-700 font-medium"
            />

            <x-text-input
                id="password_confirmation"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi password"
            />

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        <!-- Button -->
        <div class="mt-7">
            <x-primary-button
                class="w-full justify-center py-3 rounded-xl
                       bg-emerald-600 hover:bg-emerald-700
                       focus:bg-emerald-700
                       active:bg-emerald-800
                       focus:ring-emerald-500
                       transition duration-200"
            >
                {{ __('Buat Akun') }}
            </x-primary-button>
        </div>

        <!-- Login -->
        <div class="mt-6 text-center">
            <span class="text-sm text-gray-500">
                Sudah memiliki akun?
            </span>

            <a
                href="{{ route('login') }}"
                class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 ml-1"
            >
                Masuk sekarang
            </a>
        </div>

    </form>
</x-guest-layout>