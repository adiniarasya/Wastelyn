<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Dashboard - WasteLyn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-blue-700 text-white p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">🏪 WasteLyn Mitra</h1>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard Mitra</h2>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-gray-500 text-sm">Total Setoran</p>
                    <p class="text-2xl font-bold text-gray-800">0 kg</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800">Rp 0</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <p class="text-gray-500 text-sm">Request Penjemputan</p>
                    <p class="text-2xl font-bold text-yellow-600">0</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-700 mb-4">⚡ Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <a href="#" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">📦</div>
                        <span class="text-sm font-medium text-gray-700">Verifikasi Setoran</span>
                    </a>
                    <a href="#" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🚚</div>
                        <span class="text-sm font-medium text-gray-700">Kelola Penjemputan</span>
                    </a>
                    <a href="#" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">📊</div>
                        <span class="text-sm font-medium text-gray-700">Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>