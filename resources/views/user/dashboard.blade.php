@extends('template.layout')

@section('card-title', 'Dashboard User')

@section('content')

<!-- User Profile Card -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row items-center gap-6">

        <div
            class="w-24 h-24 bg-green-200 rounded-full flex items-center justify-center text-4xl font-bold text-green-700">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>

        <div class="flex-1 text-center md:text-left">
            <h2 class="text-xl font-bold text-gray-800">
                {{ auth()->user()->name }}
            </h2>

            <p class="text-gray-500">
                {{ auth()->user()->email }}
            </p>

            <p class="text-sm text-gray-500">
                {{ auth()->user()->phone ?? 'No HP belum diisi' }}
            </p>
        </div>

        <div class="flex gap-6">

            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $totalXp ?? 0 }}
                </p>
                <p class="text-xs text-gray-500">XP</p>
            </div>

            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">
                    {{ $totalPoints ?? 0 }}
                </p>
                <p class="text-xs text-gray-500">Poin</p>
            </div>

            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">
                    #{{ $totalLevel ?? 1 }}
                </p>
                <p class="text-xs text-gray-500">Level</p>
            </div>

        </div>
    </div>
</div>



<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl shadow-md p-4 text-center border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Total Pickup</p>
        <p class="text-xl font-bold text-gray-800">
            {{ $totalPickups ?? 0 }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 text-center border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500">Pending Pickup</p>
        <p class="text-xl font-bold text-yellow-600">
            {{ $pendingPickups ?? 0 }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 text-center border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Transaksi</p>
        <p class="text-xl font-bold text-gray-800">
            {{ $totalTransactions ?? 0 }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-4 text-center border-l-4 border-purple-500">
        <p class="text-xs text-gray-500">Notifikasi</p>
        <p class="text-xl font-bold text-purple-600">
            {{ $unreadNotifications ?? 0 }}
        </p>
    </div>

</div>


<!-- Menu Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

    <a
        href="#"
        class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
        <div class="text-3xl mb-2">🎯</div>

        <p class="font-semibold text-gray-700">
            Eco Mission
        </p>

        <p class="text-xs text-gray-500">
            Misi ramah lingkungan
        </p>

        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full mt-2 inline-block">
            {{ $completedMissions ?? 0 }} Selesai
        </span>
    </a>


    <a
        href="#"
        class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
        <div class="text-3xl mb-2">🏪</div>

        <p class="font-semibold text-gray-700">
            Smart Waste
        </p>

        <p class="text-xs text-gray-500">
            Bank sampah terdekat
        </p>
    </a>


    <a
        href="#"
        class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
        <div class="text-3xl mb-2">🎁</div>

        <p class="font-semibold text-gray-700">
            Reward Center
        </p>

        <p class="text-xs text-gray-500">
            Tukar poin
        </p>

        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full mt-2 inline-block">
            {{ $totalRedemptions ?? 0 }} Tukar
        </span>
    </a>


    <a
        href="#"
        class="bg-white hover:shadow-lg rounded-xl p-6 text-center transition shadow-md">
        <div class="text-3xl mb-2">🤖</div>

        <p class="font-semibold text-gray-700">
            EcoGuide AI
        </p>

        <p class="text-xs text-gray-500">
            Asisten AI
        </p>
    </a>

</div>


<!-- Recent Pickups & Transactions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <!-- Recent Pickups -->
    <div class="bg-white rounded-xl shadow-md p-6">

        <h3 class="font-semibold text-gray-700 mb-4">
            📦 Pickup Terbaru
        </h3>

        <div class="space-y-3">

            @forelse($recentPickups ?? [] as $pickup)

            <div class="flex justify-between items-center border-b pb-2">

                <div>
                    <p class="text-sm text-gray-500">
                        {{ $pickup->created_at->format('d/m/Y H:i') }}
                    </p>

                    @if ($pickup->weight)
                    <p class="text-sm text-gray-600">
                        {{ $pickup->weight }} kg
                    </p>
                    @endif
                </div>

                <span
                    class="text-xs px-2 py-1 rounded-full
                            @if ($pickup->status == 'completed')
                                bg-green-100 text-green-800
                            @elseif($pickup->status == 'pending')
                                bg-yellow-100 text-yellow-800
                            @elseif($pickup->status == 'accepted')
                                bg-blue-100 text-blue-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                    {{ $pickup->status }}
                </span>

            </div>

            @empty

            <p class="text-gray-500 text-center py-4">
                Belum ada pickup
            </p>

            @endforelse

        </div>
    </div>


    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-md p-6">

        <h3 class="font-semibold text-gray-700 mb-4">
            💰 Transaksi Terbaru
        </h3>

        <div class="space-y-3">

            @forelse($recentTransactions ?? [] as $transaction)

            <div class="flex justify-between items-center border-b pb-2">

                <div>
                    <p class="text-sm text-gray-500">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </p>

                    <p class="text-sm text-gray-600">
                        {{ $transaction->description ?? 'Transaksi' }}
                    </p>
                </div>

                <div class="text-right">

                    <p
                        class="font-bold {{ $transaction->type == 'earn' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type == 'earn' ? '+' : '-' }}
                        {{ number_format($transaction->points ?? 0, 0, ',', '.') }}
                        poin
                    </p>

                    <span
                        class="text-xs px-2 py-1 rounded-full
                                @if ($transaction->status == 'completed')
                                    bg-green-100 text-green-800
                                @elseif($transaction->status == 'pending')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                        {{ $transaction->status }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">
                Belum ada transaksi
            </p>
            @endforelse
        </div>
    </div>
</div>

@endsection