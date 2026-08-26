<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $missions = Mission::all();
        return view('admin.missions.index', compact('missions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.missions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'xp_reward' => 'required|integer|min:0',
            'points_reward' => 'required|integer|min:0',
            'type' => 'required|in:daily,weekly,monthly',
            'category' => 'nullable|string|max:100',
            'target' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Mission::create($request->all());
        return redirect()->route('admin.missions.index')->with('success', 'Misi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mission $mission)
    {
        return view('admin.missions.show', compact('mission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mission $mission)
    {
        return view('admin.missions.edit', compact('mission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mission $mission)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'xp_reward' => 'required|integer|min:0',
            'points_reward' => 'required|integer|min:0',
            'type' => 'required|in:daily,weekly,monthly',
            'category' => 'nullable|string|max:100',
            'target' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $mission->update($request->all());
        return redirect()->route('admin.missions.index')->with('success', 'Misi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mission $mission)
    {
        $mission->delete();
        return redirect()->route('admin.missions.index')->with('success', 'Misi berhasil dihapus');
    }
}
