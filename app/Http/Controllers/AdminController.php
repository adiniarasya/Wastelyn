<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\PickupRequest;
use App\Models\WasteBank;
use App\Models\WasteCategory;
use App\Models\Reward;
use App\Models\Mission;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total Users by Role
        $totalUsers = User::count();
        $totalWarga = User::where('role', 'warga')->count();
        $totalMitra = User::where('role', 'mitra')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // Total Data
        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalWasteBanks = WasteBank::count();
        $totalWasteCategories = WasteCategory::count();
        $totalRewards = Reward::count();
        $totalMissions = Mission::count();

        // Total Points (Earned - Redeemed)
        $totalEarned = Transaction::where('type', 'earn')->sum('points') ?? 0;
        $totalRedeemed = Transaction::where('type', 'redeem')->sum('points') ?? 0;
        $totalPoints = $totalEarned - $totalRedeemed;

        // Recent Data
        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();
        $recentPickups = PickupRequest::with('user')->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalWarga',
            'totalMitra',
            'totalAdmin',
            'totalTransactions',
            'totalPickups',
            'totalWasteBanks',
            'totalWasteCategories',
            'totalRewards',
            'totalMissions',
            'totalEarned',
            'totalRedeemed',
            'totalPoints',
            'recentTransactions',
            'recentPickups',
            'recentUsers'
        ));
    }
}
