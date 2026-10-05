<?php

namespace App\Http\Controllers;

use App\Models\UserMission;
use App\Models\User;
use App\Models\Mission;
use App\Services\XpService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserMissionController extends Controller
{
    public function index()
    {
        $missions = Mission::where('status', 'active')
            ->where('bank_id', auth()->user()->waste_bank_id)
            ->get();

        return view('user.user_missions.index', compact('missions'));
    }

    public function create()
    {
        $users = User::where('role', 'warga')->get();
        $missions = Mission::where('status', 'active')->get();

        return view('user.user-missions.create', compact('users', 'missions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mission_id' => 'required|exists:missions,mission_id',
        ]);

        $mission = Mission::findOrFail($request->mission_id);

        if ($mission->status !== 'active') {
            return back()->with('error', 'Misi ini sedang tidak aktif.');
        }

        $today = now()->toDateString();
        if ($today < $mission->start_date || $today > $mission->end_date) {
            return back()->with('error', 'Misi ini sedang di luar periode.');
        }

        $existing = UserMission::where('user_id', auth()->user()->user_id)
            ->where('mission_id', $mission->mission_id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('user.user-missions.show', $mission->mission_id)
                ->with('info', 'Kamu sudah mengikuti misi ini.');
        }

        $uniqueCode = 'MISI-' . $mission->mission_id . '-' . strtoupper(Str::random(4));
        while (UserMission::where('unique_code', $uniqueCode)->exists()) {
            $uniqueCode = 'MISI-' . $mission->mission_id . '-' . strtoupper(Str::random(4));
        }

        UserMission::create([
            'user_id' => auth()->user()->user_id,
            'mission_id' => $request->mission_id,
            'unique_code' => $uniqueCode,
            'status' => 'ongoing',
            'progress' => 0,
        ]);

        return redirect()
            ->route('user.user-missions.show', $mission->mission_id)
            ->with('success', 'Berhasil mengikuti misi! Kode misimu: ' . $uniqueCode);
    }

    public function show(Mission $mission)
    {
        $userMission = UserMission::where('user_id', auth()->user()->user_id)
            ->where('mission_id', $mission->mission_id)
            ->with(['submissions', 'progressLogs'])
            ->first();

        return view('user.user_missions.show', compact('mission', 'userMission'));
    }

    public function buatprogress(UserMission $userMission)
    {
        $userMission->load('user', 'mission', 'progressLogs', 'submissions');

        return view('user.user_missions.show', compact('userMission'));
    }

    public function edit(UserMission $userMission)
    {
        $users = User::where('role', 'warga')->get();
        $missions = Mission::where('status', 'active')->get();

        return view('user.user_missions.edit', compact('userMission', 'users', 'missions'));
    }

    public function update(Request $request, UserMission $userMission)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'mission_id' => 'required|exists:missions,mission_id',
            'status' => 'required|in:ongoing,ready_pickup,picked_up,completed',
            'progress' => 'required|integer|min:0',
        ]);

        $userMission->update($request->all());

        return redirect()->route('user.user-missions.index')
            ->with('success', 'User mission berhasil diupdate');
    }

    public function destroy(UserMission $userMission)
    {
        $userMission->delete();

        return redirect()->route('user.user-missions.index')
            ->with('success', 'User mission berhasil dihapus');
    }

    public function updateStatus(Request $request, $id, XpService $xpService)
    {
        $request->validate([
            'status' => 'required|in:ongoing,ready_pickup,picked_up,completed',
        ]);

        $userMission = UserMission::findOrFail($id);
        $oldStatus = $userMission->status;
        $userMission->status = $request->status;

        if ($request->status === 'picked_up') {
            $userMission->picked_up_at = now();
        }

        if ($request->status === 'completed') {
            $userMission->completed_at = now();
        }

        $userMission->save();

        if ($request->status === 'completed' && $oldStatus !== 'completed') {

            $mission = $userMission->mission;
            $user = $userMission->user;

            if ($mission && $user) {

                DB::transaction(function () use ($user, $mission, $userMission, $xpService) {

                    $user->increment('points', $mission->reward_points);

                    $xpService->addXpFromMission(
                        $user,
                        $mission->bank_id,
                        $userMission->user_mission_id,
                        $mission->title
                    );
                });
            }
        }

        return redirect()->back()->with('success', 'Status user mission berhasil diupdate');
    }
}