<?php

namespace App\Http\Controllers;

use App\Models\MissionProgressLog;
use App\Models\UserMission;
use Illuminate\Http\Request;

class MissionProgressLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = MissionProgressLog::with('userMission')->get();
        return view('admin.mission-progress-logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userMissions = UserMission::all();
        return view('admin.mission-progress-logs.create', compact('userMissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_mission_id' => 'required|exists:user_missions,user_mission_id',
            'progress' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        MissionProgressLog::create($request->all());
        return redirect()->route('admin.mission-progress-logs.index')->with('success', 'Progress log berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MissionProgressLog $missionProgressLog)
    {
        $missionProgressLog->load('userMission');
        return view('admin.mission-progress-logs.show', compact('missionProgressLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MissionProgressLog $missionProgressLog)
    {
        $userMissions = UserMission::all();
        return view('admin.mission-progress-logs.edit', compact('missionProgressLog', 'userMissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MissionProgressLog $missionProgressLog)
    {
        $request->validate([
            'user_mission_id' => 'required|exists:user_missions,user_mission_id',
            'progress' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $missionProgressLog->update($request->all());
        return redirect()->route('admin.mission-progress-logs.index')->with('success', 'Progress log berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MissionProgressLog $missionProgressLog)
    {
        $missionProgressLog->delete();
        return redirect()->route('admin.mission-progress-logs.index')->with('success', 'Progress log berhasil dihapus');
    }
}
