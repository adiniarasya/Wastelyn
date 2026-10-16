<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\RewardRedemption;
use App\Models\UserMission;
use App\Models\Notification;
use App\Models\User;
use App\Models\XpLog;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $totalPickups = PickupRequest::where('user_id', $user->user_id)->count();
        $pendingPickups = PickupRequest::where('user_id', $user->user_id)
            ->where('status', 'pending')
            ->count();
        $totalTransactions = Transaction::where('user_id', $user->user_id)->count();
        $totalPoints = $user->points ?? 0;
        $totalXp = $user->xp ?? 0;
        $totalLevel = $user->level ?? 1;
        $totalRedemptions = RewardRedemption::where('user_id', $user->user_id)->count();
        $completedMissions = UserMission::where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->count();

        $totalUang = PickupRequest::where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->sum('total_harga') ?? 0;

        $totalCo2 = PickupRequest::where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->sum('co2_saved') ?? 0;

        $totalBerat = PickupRequest::where('user_id', $user->user_id)
            ->where('status', 'completed')
            ->sum('weight_kg') ?? 0;

        $userXp = null;

        $userMissions = UserMission::where('user_id', $user->user_id)
            ->with('mission')
            ->whereIn('status', ['ongoing', 'ready_pickup'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPickups = PickupRequest::where('user_id', $user->user_id)
            ->latest()
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::where('user_id', $user->user_id)
            ->latest()
            ->limit(5)
            ->get();

        $unreadNotifications = Notification::where('user_id', $user->user_id)
            ->where('is_read', false)
            ->count();

        $mitras = collect();
        $mitrasJson = collect();

        if (!$user->onboarding_completed || !$user->waste_bank_id) {
            $mitras = User::where('role', 'mitra')
                ->where('status', 'active')
                ->with('managedWasteBank')
                ->get()
                ->filter(
                    fn($m) =>
                    $m->managedWasteBank &&
                    $m->managedWasteBank->latitude &&
                    $m->managedWasteBank->longitude
                )
                ->values();

            $mitrasJson = $mitras->map(fn($m) => [
                'bank_id' => $m->managedWasteBank->bank_id,
                'name' => $m->managedWasteBank->name ?? $m->name,
                'address' => $m->managedWasteBank->address,
                'latitude' => (float) $m->managedWasteBank->latitude,
                'longitude' => (float) $m->managedWasteBank->longitude,
            ])->values();
        } else {
            $userXp = $user->getXpForBank($user->waste_bank_id);
        }

        $streakBonus = $this->checkStreakBonus($user);

        return view('user.dashboard', compact(
            'user',
            'userXp',
            'totalPickups',
            'pendingPickups',
            'totalTransactions',
            'totalPoints',
            'totalXp',
            'totalLevel',
            'totalRedemptions',
            'completedMissions',
            'recentPickups',
            'recentTransactions',
            'unreadNotifications',
            'userMissions',
            'mitras',
            'mitrasJson',
            'totalUang',
            'totalCo2',
            'totalBerat',
            'streakBonus'
        ));
    }

    protected function checkStreakBonus(User $user): ?array
    {
        $streak = (int) ($user->current_streak ?? 0);

        if ($streak === 0 || $streak % 4 !== 0) {
            return null;
        }

        $lastClaimed = (int) ($user->last_streak_bonus_week ?? 0);

        if ($lastClaimed >= $streak) {
            return null;
        }

        $bonusXp = 50;

        if (method_exists($user, 'addXp')) {
            $user->addXp($bonusXp);
        } else {
            $user->xp = ($user->xp ?? 0) + $bonusXp;
            $user->save();
        }

        $user->last_streak_bonus_week = $streak;
        $user->save();

        return [
            'streak' => $streak,
            'xp' => $bonusXp,
        ];
    }

    public function ecoHabit()
    {
        $user = auth()->user();

        if (!$user->waste_bank_id) {
            return redirect()->route('user.dashboard');
        }

        $userXp = $user->getXpForBank($user->waste_bank_id);

        $logs = XpLog::where('user_id', $user->user_id)
            ->where('bank_id', $user->waste_bank_id)
            ->latest()
            ->paginate(20);

        return view('user.eco-habit', compact('user', 'userXp', 'logs'));
    }

    public function profile()
    {
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        $passwordChanged = false;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->save();

        if ($passwordChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah. Silakan login kembali.');
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}