<?php

namespace App\Http\Controllers;

use App\Models\RewardRedemption;
use App\Models\User;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardRedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $redemptions = RewardRedemption::with('user', 'reward')->get();
        return view('admin.reward-redemptions.index', compact('redemptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $rewards = Reward::where('status', 'active')->get();
        return view('admin.reward-redemptions.create', compact('users', 'rewards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'reward_id' => 'required|exists:rewards,reward_id',
            'points_used' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        RewardRedemption::create($request->all());
        return redirect()->route('admin.reward-redemptions.index')->with('success', 'Redemption berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(RewardRedemption $rewardRedemption)
    {
        $rewardRedemption->load('user', 'reward');
        return view('admin.reward-redemptions.show', compact('rewardRedemption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RewardRedemption $rewardRedemption)
    {
        $users = User::where('role', 'warga')->get();
        $rewards = Reward::where('status', 'active')->get();
        return view('admin.reward-redemptions.edit', compact('rewardRedemption', 'users', 'rewards'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RewardRedemption $rewardRedemption)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'reward_id' => 'required|exists:rewards,reward_id',
            'points_used' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $rewardRedemption->update($request->all());
        return redirect()->route('admin.reward-redemptions.index')->with('success', 'Redemption berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RewardRedemption $rewardRedemption)
    {
        $rewardRedemption->delete();
        return redirect()->route('admin.reward-redemptions.index')->with('success', 'Redemption berhasil dihapus');
    }

    /**
     * Approve redemption.
     */
    public function approve($id)
    {
        $redemption = RewardRedemption::findOrFail($id);
        $redemption->update(['status' => 'approved']);

        // Kurangi stok reward
        $reward = $redemption->reward;
        if ($reward) {
            $reward->decrement('stock');
        }

        return redirect()->back()->with('success', 'Redemption berhasil disetujui');
    }

    /**
     * Reject redemption.
     */
    public function reject($id)
    {
        $redemption = RewardRedemption::findOrFail($id);
        $redemption->update(['status' => 'rejected']);

        // Kembalikan poin ke user
        $user = $redemption->user;
        if ($user) {
            $user->increment('points', $redemption->points_used);
        }

        return redirect()->back()->with('success', 'Redemption ditolak, poin dikembalikan');
    }
}
