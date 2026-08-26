<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickupRequest;
use App\Models\Transaction;
use App\Models\RewardRedemption;
use App\Models\UserMission;
use App\Models\Notification;

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

        return view('user.dashboard', compact(
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
            'unreadNotifications'
        ));
    }
}
