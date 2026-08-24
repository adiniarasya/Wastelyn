<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - WasteLyn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-green-700 text-white p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">🌿 WasteLyn</h1>
                <div class="flex items-center gap-4">
                    <span>Halo, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="container mx-auto p-6">
            <!-- User Profile Card -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-24 h-24 bg-green-200 rounded-full flex items-center justify-center text-4xl font-bold text-green-700">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-500">{{ auth()->user()->email }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->phone ?? 'No HP belum diisi' }}</p>
                    </div>
                    <div class="flex gap-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ auth()->user()->xp }}</p>
                            <p class="text-xs text-gray-500">XP</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ auth()->user()->points }}</p>
                            <p class="text-xs text-gray-500">Poin</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">#{{ auth()->user()->level }}</p>
                            <p class="text-xs text-gray-500">Level</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#" class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
                    <div class="text-3xl mb-2">🎯</div>
                    <p class="font-semibold text-gray-700">Eco Mission</p>
                    <p class="text-xs text-gray-500">Misi ramah lingkungan</p>
                </a>

                <a href="#" class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
                    <div class="text-3xl mb-2">🏪</div>
                    <p class="font-semibold text-gray-700">Smart Waste</p>
                    <p class="text-xs text-gray-500">Bank sampah terdekat</p>
                </a>

                <a href="#" class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
                    <div class="text-3xl mb-2">🎁</div>
                    <p class="font-semibold text-gray-700">Reward Center</p>
                    <p class="text-xs text-gray-500">Tukar poin</p>
                </a>

                <a href="#" class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
                    <div class="text-3xl mb-2">🤖</div>
                    <p class="font-semibold text-gray-700">EcoGuide AI</p>
                    <p class="text-xs text-gray-500">Asisten AI</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>