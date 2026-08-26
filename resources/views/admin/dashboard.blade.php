<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WasteLyn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-green-700 text-white p-4 shadow-lg">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">🌿 WasteLyn Admin</h1>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Dashboard Admin</h2>

            <!-- Stats Users -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Warga</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalWarga }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm">Total Mitra</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMitra }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                    <p class="text-gray-500 text-sm">Total Admin</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAdmin }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Total User</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>

            <!-- Stats Transactions & Points -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                    <p class="text-gray-500 text-sm">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Total Pickup</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPickups }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-500">
                    <p class="text-gray-500 text-sm">Total Bank Sampah</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalWasteBanks }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
                    <p class="text-gray-500 text-sm">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalWasteCategories }}</p>
                </div>
            </div>

            <!-- Stats Rewards & Missions & Points -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-pink-500">
                    <p class="text-gray-500 text-sm">Total Reward</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRewards }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-cyan-500">
                    <p class="text-gray-500 text-sm">Total Misi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMissions }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Total Poin Earned</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalEarned, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Total Poin Redeem</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalRedeemed, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-700 mb-4">⚡ Aksi Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('admin.waste-banks.index') }}"
                        class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🏪</div>
                        <span class="text-sm font-medium text-gray-700">Bank Sampah</span>
                    </a>
                    <a href="{{ route('admin.waste-categories.index') }}"
                        class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🗑️</div>
                        <span class="text-sm font-medium text-gray-700">Kategori Sampah</span>
                    </a>
                    <a href="{{ route('admin.missions.index') }}"
                        class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🎯</div>
                        <span class="text-sm font-medium text-gray-700">Misi</span>
                    </a>
                    <a href="{{ route('admin.rewards.index') }}"
                        class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🎁</div>
                        <span class="text-sm font-medium text-gray-700">Reward</span>
                    </a>
                    <a href="{{ route('admin.pickup-requests.index') }}"
                        class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">📦</div>
                        <span class="text-sm font-medium text-gray-700">Pickup Request</span>
                    </a>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="bg-red-50 hover:bg-red-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">💰</div>
                        <span class="text-sm font-medium text-gray-700">Transaksi</span>
                    </a>
                    <a href="{{ route('admin.waste-bank-staff.index') }}"
                        class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">👤</div>
                        <span class="text-sm font-medium text-gray-700">Staff</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="bg-pink-50 hover:bg-pink-100 p-4 rounded-lg text-center transition">
                        <div class="text-2xl mb-1">🔔</div>
                        <span class="text-sm font-medium text-gray-700">Notifikasi</span>
                    </a>
                </div>
            </div>

            <!-- Recent Transactions & Pickups -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📝 Transaksi Terbaru</h3>
                    <div class="space-y-3">
                        @forelse($recentTransactions as $transaction)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $transaction->user->name ?? 'User' }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="font-bold {{ $transaction->type == 'earn' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type == 'earn' ? '+' : '-' }}
                                        {{ number_format($transaction->points, 0, ',', '.') }} poin
                                    </p>
                                    <span
                                        class="text-xs px-2 py-1 rounded-full 
                                    @if ($transaction->type == 'earn') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                        {{ $transaction->type }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">📦 Pickup Terbaru</h3>
                    <div class="space-y-3">
                        @forelse($recentPickups as $pickup)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $pickup->user->name ?? 'User' }}</p>
                                    <p class="text-sm text-gray-500">{{ $pickup->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span
                                    class="text-xs px-2 py-1 rounded-full 
                                @if ($pickup->status == 'completed') bg-green-100 text-green-800
                                @elseif($pickup->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($pickup->status == 'process') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ $pickup->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">Belum ada pickup</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
