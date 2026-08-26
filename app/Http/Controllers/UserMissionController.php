<?php

namespace App\Http\Controllers;

use App\Models\UserMission;
use App\Models\User;
use App\Models\Mission;
use Illuminate\Http\Request;

class UserMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userMissions = UserMission::with('user', 'mission')->get();
        return view('admin.user-missions.index', compact('userMissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $missions = Mission::where('status', 'active')->get();
        return view('admin.user-missions.create', compact('users', 'missions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'mission_id' => 'required|exists:missions,mission_id',
            'status' => 'required|in:in_progress,completed,failed',
            'progress' => 'required|integer|min:0',
        ]);

        UserMission::create($request->all());
        return redirect()->route('admin.user-missions.index')->with('success', 'User mission berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserMission $userMission)
    {
        $userMission->load('user', 'mission', 'progressLogs');
        return view('admin.user-missions.show', compact('userMission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserMission $userMission)
    {
        $users = User::where('role', 'warga')->get();
        $missions = Mission::where('status', 'active')->get();
        return view('admin.user-missions.edit', compact('userMission', 'users', 'missions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserMission $userMission)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'mission_id' => 'required|exists:missions,mission_id',
            'status' => 'required|in:in_progress,completed,failed',
            'progress' => 'required|integer|min:0',
        ]);

        $userMission->update($request->all());
        return redirect()->route('admin.user-missions.index')->with('success', 'User mission berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserMission $userMission)
    {
        $userMission->delete();
        return redirect()->route('admin.user-missions.index')->with('success', 'User mission berhasil dihapus');
    }

    /**
     * Update status user mission.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed,failed',
        ]);

        $userMission = UserMission::findOrFail($id);
        
        $userMission->update(['status' => $request->status]);

        // Jika completed, berikan reward ke user
        if ($request->status === 'completed') {
            $mission = $userMission->mission;
            $user = $userMission->user;

            if ($mission && $user) {
                $user->increment('xp', $mission->xp_reward);
                $user->increment('points', $mission->points_reward);
            }
        }

        return redirect()->back()->with('success', 'Status user mission berhasil diupdate');
    }

    /**
     * Update progress user mission.
     */
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'progress' => 'required|integer|min:0',
        ]);

        $userMission = UserMission::findOrFail($id);
        $mission = $userMission->mission;

        // Cek apakah progress sudah mencapai target
        if ($mission && $request->progress >= $mission->target) {
            $userMission->update([
                'progress' => $request->progress,
                'status' => 'completed'
            ]);

            // Berikan reward
            $user = $userMission->user;
            if ($user) {
                $user->increment('xp', $mission->xp_reward);
                $user->increment('points', $mission->points_reward);
            }

            return redirect()->back()->with('success', 'Misi selesai! Reward sudah diberikan.');
        }

        $userMission->update(['progress' => $request->progress]);
        return redirect()->back()->with('success', 'Progress berhasil diupdate');
    }
}
