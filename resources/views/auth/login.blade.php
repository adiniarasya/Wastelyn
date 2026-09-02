<x-guest-layout>
    <div class="mb-8 text-center">

        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100">
                <svg
                    class="w-7 h-7 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold tracking-tight text-gray-800">
            Selamat Datang
        </h2>

        <p class="mt-2 text-sm text-gray-500">
            Silakan masuk untuk melanjutkan ke akun kamu
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status
        class="mb-5"
        :status="session('status')"
    />

    <!-- Error Message -->
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <div class="flex items-start gap-3">

                <div class="mt-0.5 text-red-500">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-11.5a.75.75 0 011.5 0v4a.75.75 0 01-1.5 0v-4zM10 14a.875.875 0 100 1.75A.875.875 0 0010 14z"
                            clip-rule="evenodd"
                        />
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

    <form
        method="POST"
        action="{{ route('login') }}"
        class="space-y-5"
    >
        @csrf

        <!-- Email -->
        <div>
            <x-input-label
                for="email"
                value="Email"
                class="mb-2 text-sm font-medium text-gray-700"
            />

            <x-text-input
                id="email"
                class="block w-full rounded-xl border-gray-300 px-4 py-3 text-sm shadow-sm transition
                       focus:border-emerald-500 focus:ring-emerald-500"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan email kamu"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div>

            <div class="flex items-center justify-between mb-2">

                <x-input-label
                    for="password"
                    value="Password"
                    class="text-sm font-medium text-gray-700"
                />

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-xs font-medium text-emerald-600 transition hover:text-emerald-800"
                    >
                        Lupa password?
                    </a>
                @endif

            </div>

            <!-- Password Input -->
            <div class="relative">

                <x-text-input
                    id="password"
                    class="block w-full rounded-xl border-gray-300 px-4 py-3 pr-12 text-sm shadow-sm transition
                           focus:border-emerald-500 focus:ring-emerald-500"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password kamu"
                />

                <!-- Toggle Password -->
                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400
                           transition hover:text-emerald-600 focus:outline-none"
                    aria-label="Tampilkan password"
                >

                    <!-- Eye Open -->
                    <svg
                        id="eyeOpen"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                    </svg>

                    <!-- Eye Closed -->
                    <svg
                        id="eyeClosed"
                        xmlns="http://www.w3.org/2000/svg"
                        class="hidden h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3l18 18"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10.584 10.587a2 2 0 002.829 2.829"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.88 5.09A10.94 10.94 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.94 10.94 0 01-4.035 5.24M6.228 6.228A10.94 10.94 0 002.458 12c.8 2.55 2.5 4.55 4.542 5.69"
                        />
                    </svg>

                </button>
            </div>

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">

            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-gray-300 text-emerald-600 shadow-sm
                       focus:ring-emerald-500"
            >

            <label
                for="remember_me"
                class="ms-2 text-sm text-gray-600"
            >
                Ingat saya
            </label>

        </div>

        <!-- Login Button -->
        <div class="pt-1">

            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-xl
                       bg-emerald-600 px-4 py-3 text-sm font-semibold text-white
                       shadow-sm transition duration-200
                       hover:bg-emerald-700 hover:shadow-md
                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
            >
                Masuk
            </button>

        </div>

        <!-- Divider -->
        <div class="relative py-2">

            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>

            <div class="relative flex justify-center">
                <span class="bg-white px-3 text-xs text-gray-400">
                    atau
                </span>
            </div>

        </div>

        <!-- Register -->
        <div class="text-center">

            <p class="text-sm text-gray-600">
                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="ml-1 font-semibold text-emerald-600 transition hover:text-emerald-800"
                >
                    Daftar sekarang
                </a>
            </p>

        </div>

    </form>

    <!-- Toggle Password Script -->
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