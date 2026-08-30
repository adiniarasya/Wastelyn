<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewards = Reward::all();

        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        Reward::create($request->all());

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        return view('admin.rewards.show', compact('reward'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'point_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:available,unavailable',
        ]);

        $reward->update($request->all());

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        $reward->delete();

        return redirect()
            ->route('admin.rewards.index')
            ->with('success', 'Reward berhasil dihapus');
    }
}