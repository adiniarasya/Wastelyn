<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{

    public function index()
    {
        $missions = Mission::all();
        return view('admin.missions.index', compact('missions'));
    }

    public function create()
    {
        return view('admin.missions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Mission::create($request->all());

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil ditambahkan');
    }

    public function show(Mission $mission)
    {
        return view('admin.missions.show', compact('mission'));
    }

    public function edit(Mission $mission)
    {
        return view('admin.missions.edit', compact('mission'));
    }

    public function update(Request $request, Mission $mission)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $mission->update($request->all());

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil diupdate');
    }

    public function destroy(Mission $mission)
    {
        $mission->delete();

        return redirect()
            ->route('admin.missions.index')
            ->with('success', 'Misi berhasil dihapus');
    }


    public function mitraIndex()
    {
        $missions = Mission::all();
        return view('mitra.missions.index', compact('missions'));
    }

    public function mitraCreate()
    {
        return view('mitra.missions.create');
    }

    public function mitraStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Mission::create($request->all());

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil ditambahkan');
    }

    public function mitraShow(Mission $mission)
    {
        return view('mitra.missions.show', compact('mission'));
    }

    public function mitraEdit(Mission $mission)
    {
        return view('mitra.missions.edit', compact('mission'));
    }

    public function mitraUpdate(Request $request, Mission $mission)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target' => 'required|integer|min:1',
            'reward_xp' => 'required|integer|min:0',
            'reward_points' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $mission->update($request->all());

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil diupdate');
    }

    public function mitraDestroy(Mission $mission)
    {
        $mission->delete();

        return redirect()
            ->route('mitra.missions.index')
            ->with('success', 'Misi berhasil dihapus');
    }
}