<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Default null — diisi kalau user sudah onboarding
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
            // === Belum onboarding: tampilkan pilihan mitra ===
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
            // === Sudah onboarding: baru ambil data XP ===
            $userXp = $user->getXpForBank($user->waste_bank_id);
        }

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
            'mitrasJson'
        ));
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
}