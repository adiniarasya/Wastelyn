<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\RewardRedemption;
use App\Models\WasteBank;

class MitraController extends Controller
{
    public function dashboard()
    {
        $mitraId = auth()->id();

        // Ambil semua bank milik mitra ini
        $bankIds = WasteBank::where('mitra_id', $mitraId)->pluck('bank_id')->toArray();

        // Total Pickup dari bank-bank mitra
        $totalPickups = PickupRequest::whereIn('bank_id', $bankIds)->count();
        $pendingPickups = PickupRequest::whereIn('bank_id', $bankIds)
            ->where('status', 'pending')
            ->count();
        $acceptedPickups = PickupRequest::whereIn('bank_id', $bankIds)
            ->where('status', 'accepted')
            ->count();
        $completedPickups = PickupRequest::whereIn('bank_id', $bankIds)
            ->where('status', 'completed')
            ->count();

        // Total Transaksi (dari bank mitra)
        $totalTransactions = Transaction::whereIn('bank_id', $bankIds)->count();
        $totalRedemptions = RewardRedemption::whereIn('bank_id', $bankIds)->count();

        // Recent Pickups
        $recentPickups = PickupRequest::whereIn('bank_id', $bankIds)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Total Bank
        $totalBanks = WasteBank::where('mitra_id', $mitraId)->count();

        return view('mitra.dashboard', compact(
            'totalPickups',
            'pendingPickups',
            'acceptedPickups',
            'completedPickups',
            'totalTransactions',
            'totalRedemptions',
            'recentPickups',
            'totalBanks'
        ));
    }
}
