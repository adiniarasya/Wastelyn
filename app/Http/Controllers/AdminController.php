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
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalWarga = User::where('role', 'warga')->count();
        $totalMitra = User::where('role', 'mitra')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalWasteBanks = WasteBank::count();
        $totalWasteCategories = WasteCategory::count();
        $totalRewards = Reward::count();
        $totalMissions = Mission::count();

        $totalEarned = (int) Transaction::where('type', 'earn')->sum('points');
        $totalRedeemed = (int) Transaction::where('type', 'redeem')->sum('points');
        $totalPoints = max(0, $totalEarned - $totalRedeemed);

        $totalSetoranKg = 0;
        foreach (['weight', 'weight_kg', 'total_weight', 'berat'] as $col) {
            try {
                if (Schema::hasColumn('pickup_requests', $col)) {
                    $totalSetoranKg = (float) PickupRequest::sum($col);
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $recentPickups = PickupRequest::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $recentUsers = User::latest()->limit(5)->get();

        try {
            $pendingMitra = User::where('role', 'mitra')
                ->where('status', 'pending')
                ->count();
        } catch (\Exception $e) {
            $pendingMitra = 0;
        }

        try {
            $pendingSetoran = PickupRequest::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingSetoran = 0;
        }

        try {
            $pendingReward = Reward::where('status', 'pending')->count();
        } catch (\Exception $e) {
            $pendingReward = 0;
        }

        [$chartLabels, $chartData] = $this->buildChartData('weight');

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
            'totalSetoranKg',
            'recentTransactions',
            'recentPickups',
            'recentUsers',
            'pendingMitra',
            'pendingSetoran',
            'pendingReward',
            'chartLabels',
            'chartData'
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

        $totalEarned = (int) Transaction::where('type', 'earn')->sum('points');
        $totalRedeemed = (int) Transaction::where('type', 'redeem')->sum('points');
        $totalPoints = max(0, $totalEarned - $totalRedeemed);

        $recentUsers = User::latest()->limit(5)->get();

        [$chartLabels, $chartData] = $this->buildChartData();

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
            'totalEarned',
            'totalRedeemed',
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function laporan(Request $request)
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalMissions = Mission::count();
        $totalRewards = Reward::count();

        $recentUsers = User::latest()
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        $recentTransactions = Transaction::with('user')->latest()
            ->paginate(10, ['*'], 'transactions_page')
            ->withQueryString();

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

    public function laporanPdf()
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalPickups = PickupRequest::count();
        $totalMissions = Mission::count();
        $totalRewards = Reward::count();

        $recentUsers = User::latest()->limit(10)->get();
        $recentTransactions = Transaction::with('user')->latest()->limit(10)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan-pdf', compact(
            'totalUsers',
            'totalTransactions',
            'totalPickups',
            'totalMissions',
            'totalRewards',
            'recentUsers',
            'recentTransactions'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-wastelyn-' . now()->format('Y-m-d') . '.pdf');
    }
    private function buildChartData(?string $sumColumn = null): array
    {
        $labels = [];
        $data = [];
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        $hasColumn = false;
        if ($sumColumn) {
            try {
                $hasColumn = Schema::hasColumn('pickup_requests', $sumColumn);
            } catch (\Exception $e) {
                $hasColumn = false;
            }
        }

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $dayNames[$date->dayOfWeek];

            if ($hasColumn && $sumColumn) {
                $value = (float) PickupRequest::whereDate('created_at', $date)
                    ->sum($sumColumn);
            } else {
                $value = (int) Transaction::whereDate('created_at', $date)->count();
            }

            $data[] = $value;
        }

        if (empty(array_filter($data))) {
            $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $data = array_fill(0, 7, 0);
        }

        return [$labels, $data];
    }
}