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
        $totalEarned = Transaction::where('type', 'earn')->sum('points') ?? 0;
        $totalRedeemed = Transaction::where('type', 'redeem')->sum('points') ?? 0;
        $totalPoints = $totalEarned - $totalRedeemed;

        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();
        $recentPickups = PickupRequest::with('user')->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(5)->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D');
            $chartData[] = Transaction::whereDate('created_at', $date)->count();
        }
        if (empty(array_filter($chartData))) {
            $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $chartData = [0, 0, 0, 0, 0, 0, 0];
        }

        try {
            $pendingMitra = User::where('role', 'mitra')->where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingMitra = 0;
        }

        try {
            $pendingSetoran = PickupRequest::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingSetoran = 0;
        }

        // Reward – gunakan model Reward dengan fallback
        try {
            $pendingReward = Reward::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingReward = 0;
        }

        // 3. Total Setoran (Kg) – fallback 0 jika kolom 'weight' tidak ada
        try {
            $totalSetoranKg = PickupRequest::sum('weight') ?? 0;
        } catch (\Exception $e) {
            $totalSetoranKg = 0;
        }

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
            'recentUsers',
            'chartLabels',
            'chartData',
            'pendingMitra',
            'pendingSetoran',
            'pendingReward',
            'totalSetoranKg'
        ));
    }

    public function statistics()
    {
        $totalUsers = User::count();
        $totalWarga = User::where('role', 'warga')->count();
        $totalMitra = User::where('role', 'mitra')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalWasteBanks = WasteBank::count();
        $totalRewards = Reward::count();
        $totalMissions = Mission::count();
        $totalEarned = Transaction::where('type', 'earn')->sum('points') ?? 0;
        $totalRedeemed = Transaction::where('type', 'redeem')->sum('points') ?? 0;
        $totalPoints = $totalEarned - $totalRedeemed;
        $recentUsers = User::latest()->limit(5)->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D');
            $chartData[] = Transaction::whereDate('created_at', $date)->count();
        }
        if (empty(array_filter($chartData))) {
            $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $chartData = [0, 0, 0, 0, 0, 0, 0];
        }

        return view('admin.statistics', compact(
            'totalUsers',
            'totalWarga',
            'totalMitra',
            'totalAdmin',
            'totalTransactions',
            'totalPickups',
            'totalWasteBanks',
            'totalRewards',
            'totalMissions',
            'totalPoints',
            'recentUsers',
            'chartLabels',
            'chartData'
        ));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone'   => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function laporan()
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalMissions = Mission::count();
        $totalRewards = Reward::count();
        $recentUsers = User::latest()->limit(10)->get();
        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();

        return view('admin.laporan', compact(
            'totalUsers',
            'totalTransactions',
            'totalPickups',
            'totalMissions',
            'totalRewards',
            'recentUsers',
            'recentTransactions'
        ));
    }
}