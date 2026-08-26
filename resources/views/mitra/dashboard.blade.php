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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Total Bank Sampah</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalBanks }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Pickup</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPickups }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Pending Pickup</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $pendingPickups }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <p class="text-gray-500 text-sm">Completed Pickup</p>
                    <p class="text-2xl font-bold text-green-600">{{ $completedPickups }}</p>
                </div>
            </div>

            <!-- Stats 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                    <p class="text-gray-500 text-sm">Accepted Pickup</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $acceptedPickups }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
                    <p class="text-gray-500 text-sm">Total Redemption</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRedemptions }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-700 mb-4">⚡ Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <a href="{{ route('mitra.pickup-requests.index') }}"
                        class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">📦</div>
                        <span class="text-sm font-medium text-gray-700">Verifikasi Pickup</span>
                    </a>
                    <a href="{{ route('mitra.pickup-requests.index') }}"
                        class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🚚</div>
                        <span class="text-sm font-medium text-gray-700">Kelola Pickup</span>
                    </a>
                    <a href="{{ route('mitra.transactions.index') }}"
                        class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">📊</div>
                        <span class="text-sm font-medium text-gray-700">Laporan Transaksi</span>
                    </a>
                </div>
            </div>

            <!-- Recent Pickups -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-700 mb-4">📦 Request Pickup Terbaru</h3>
                <div class="space-y-3">
                    @forelse($recentPickups as $pickup)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium text-gray-800">{{ $pickup->user->name ?? 'User' }}</p>
                                <p class="text-sm text-gray-500">{{ $pickup->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-600">{{ $pickup->address ?? 'Alamat tidak tersedia' }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs px-2 py-1 rounded-full 
                                @if ($pickup->status == 'completed') bg-green-100 text-green-800
                                @elseif($pickup->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($pickup->status == 'accepted') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ $pickup->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Belum ada request pickup</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>

</html>
